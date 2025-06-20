<?php

namespace App\Controller;

use App\DTO\User\{UserLoginDTO, UserRegisterDTO};

use App\Service\User\{
    LoginUserService,
    RegisterUserService,
};
use App\Service\AuthCookieService;
use App\Service\Access\AccessControlService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, JsonResponse, Cookie};
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\HttpKernel\Exception\{
    AccessDeniedHttpException,
    BadRequestHttpException,
    TooManyRequestsHttpException,
    ConflictHttpException
};

use DateTimeImmutable;


#[Route('/api/auth', name: 'app_api_auth_')]
final class SecurityController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private AuthCookieService $cookieService,
        private AccessControlService $accessControl,
    ) {}

    #[Route('/login', name: 'login', methods: 'POST')]
    public function login(
        Request $request,
        RateLimiterFactory $loginLimiter,
        LoginUserService $loginUserService
    ): JsonResponse {
        try {
            $limiter = $loginLimiter->create($request->getClientIp());
            $limit = $limiter->consume();
            if (!$limit->isAccepted()) {
                $retryAt = $limit->getRetryAfter()->getTimestamp();
                $current = time();
                $retryAfter = max(0, $retryAt - $current);
                throw new TooManyRequestsHttpException($retryAfter, "Too many attempts");
            }

            try {
                $userLoginDTO = $this->serializer->deserialize(
                    data: $request->getContent(),
                    type: UserLoginDTO::class,
                    format: 'json'
                );
            } catch (\Exception $e) {
                throw new BadRequestHttpException("Invalid JSON format");
            }

            $userReadDTO = $loginUserService->handleLogin($userLoginDTO);

            $token = $userReadDTO->apiToken;

            $cookie = $this->cookieService->createAccessTokenCookie($token, $request);

            $responseData = $this->serializer->serialize(
                data: $userReadDTO,
                format: 'json',
                context: ['groups' => ['user:login']]
            );

            $response = new JsonResponse(
                data: $responseData,
                status: JsonResponse::HTTP_OK,
                json: true
            );
            $response->headers->setCookie($cookie);
            $limiter->reset();

            return $response;

        } catch (TooManyRequestsHttpException $e) {
            $headers = $e->getHeaders();
            $retryAfter = $headers['Retry-After'] ?? null;

            return new JsonResponse(
                data: [
                    'error' => $e->getMessage(),
                    'retryAfter' => $retryAfter
                ],
                status: JsonResponse::HTTP_TOO_MANY_REQUESTS,
                headers: ['Retry-After' => $retryAfter]
            );
        } catch (BadCredentialsException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_UNAUTHORIZED
            );
        } catch (AccessDeniedHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_FORBIDDEN
            );
        } catch (BadRequestHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route('/register', name: 'register', methods: 'POST')]
    public function register(
        Request $request,
        RegisterUserService $registerService
    ): JsonResponse {
        try {
            try {
                $userRegisterDTO = $this->serializer->deserialize(
                    data: $request->getContent(),
                    type: UserRegisterDTO::class,
                    format: 'json'
                );
            } catch (\Exception $e) {
                throw new BadRequestHttpException("Invalid JSON format");
            }

            $userReadDTO = $registerService->handleRegister($userRegisterDTO);

            $cookie = $this->cookieService->createAccessTokenCookie($userReadDTO->apiToken, $request);

            $responseData = $this->serializer->serialize(
                data: $userReadDTO,
                format: 'json',
                context: ['groups' => ['user:read']]
            );

            $response = new JsonResponse(
                data: $responseData,
                status: JsonResponse::HTTP_CREATED,
                json: true
            );
            $response->headers->setCookie($cookie);

            return $response;

        } catch (ConflictHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_CONFLICT
            );
        } catch (BadRequestHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route('/logout', name: 'logout', methods: 'POST')]
    public function logout(): JsonResponse
    {
        try {
            $this->accessControl->denyUnlessLogged();

            $cookie = $this->cookieService->revokeAccessTokenCookie();

            $response = new JsonResponse(
                data: ['message' => 'User successfully logged out'],
                status: JsonResponse::HTTP_OK
            );
            $response->headers->setCookie($cookie);
            return $response;
        } catch (AccessDeniedHttpException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_FORBIDDEN
            );
        }
    }
}

?>