<?php

namespace App\Controller;

use App\DTO\User\UserLoginDTO;
use App\DTO\User\UserReadDTO;

use App\Service\User\{
    LoginUserService
};

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, JsonResponse, Cookie};
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\HttpKernel\Exception\{AccessDeniedHttpException, BadRequestHttpException, TooManyRequestsHttpException};

use DateTimeImmutable;


#[Route('/api/auth', name: 'app_api_auth_')]
final class SecurityController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer
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

            $cookie = Cookie::create('access_token')
                ->withValue($token)
                ->withHttpOnly(true)
                ->withSecure($request->isSecure())
                ->withSameSite('strict')
                ->withExpires(new DateTimeImmutable('+7 days'));

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
        } catch (BadRequestHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_BAD_REQUEST
            );
        } catch (AccessDeniedHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_FORBIDDEN
            );
        }
    }

    #[Route('/logout', name: 'logout', methods: 'POST')]
    public function logout(): JsonResponse
    {
        try {
            $user = $this->getUser();
            if (!$user) {
                throw new AccessDeniedHttpException("User is not authenticated");
            }

            $cookie = Cookie::create('access_token')
                ->withValue('')
                ->withExpires(new DateTimeImmutable('-1 hour'))
                ->withHttpOnly(true)
                ->withSecure(true)
                ->withSameSite('strict');

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