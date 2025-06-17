<?php

namespace App\Controller;

use App\DTO\User\{UserEditDTO};

use App\Service\User\{
    ReadUserProfileService,
    EditUserProfileService,
    DeleteUserProfileService
};
use App\Service\AuthCookieService;
use Exception;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, JsonResponse};
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\HttpKernel\Exception\{AccessDeniedHttpException, BadRequestHttpException};
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

#[Route('/api/user', name: 'app_api_user_')]
final class UserController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private AuthCookieService $cookieService
    ) {}

    #[Route('', name: 'me', methods: 'GET')]
    public function me(
        ReadUserProfileService $readUserService
    ): JsonResponse {
        try {

            $user = $this->getUser();
            if (!$user) {
                throw new AccessDeniedHttpException("User is not authenticated");
            }

            $readUserDTO = $readUserService->getProfile($user);

            $responseData = $this->serializer->serialize(
                data: $readUserDTO,
                format: 'json',
                context: ['groups' => ['user:read']]
            );

            return new JsonResponse(
                data: $responseData,
                status: JsonResponse::HTTP_OK,
                json: true
            );

        } catch (AccessDeniedHttpException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_FORBIDDEN
            );
        } catch (LogicException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                data: ['error' => "An internal server error as occured"],
                status: JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('', name: 'edit', methods: 'PUT')]
    public function edit(
        Request $request,
        EditUserProfileService $editUserService
    ): JsonResponse {
        try {
            $user = $this->getUser();
            if (!$user) {
                throw new AccessDeniedHttpException("User is not authenticated");
            }

            try {
                $userEditDTO = $this->serializer->deserialize(
                    data: $request->getContent(),
                    type: UserEditDTO::class,
                    format: 'json'
                );
            } catch (\Exception $e) {
                throw new BadRequestHttpException("Invalid JSON format");
            }

            $userReadDTO = $editUserService->editUser($user, $userEditDTO);

            $responseData = $this->serializer->serialize(
                data: $userReadDTO,
                format: 'json',
                context: ['groups' => ['user:read']]
            );

            return new JsonResponse(
                data: $responseData,
                status: JsonResponse::HTTP_OK,
                json: true
            );


        } catch (BadCredentialsException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_UNAUTHORIZED
            );
        } catch (BadRequestHttpException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_BAD_REQUEST
            );
        } catch (AccessDeniedHttpException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_FORBIDDEN
            );
        } catch (LogicException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        // catch (\Exception $e) {
        //     return new JsonResponse(
        //         data: ['error' => "An internal server error as occured"],
        //         status: JsonResponse::HTTP_INTERNAL_SERVER_ERROR
        //     );
        // }
    }

    #[Route('', name: 'remove', methods: 'DELETE')]
    public function delete(
        DeleteUserProfileService $deleteUserService
    ): JsonResponse {
        try {
            $user = $this->getUser();
            if (!$user) {
                throw new AccessDeniedHttpException("User is not authenticated");
            }

            $deleteUserService->deleteUser($user);
            $cookie = $this->cookieService->revokeAccessTokenCookie();

            $response = new JsonResponse(
                data: ['message' => 'User successfully deleted'],
                status: JsonResponse::HTTP_OK
            );
            $response->headers->setCookie($cookie);
            return $response;

        } catch (AccessDeniedHttpException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_FORBIDDEN
            );
        } catch (LogicException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                data: ['error' => "An internal server error as occured"],
                status: JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}

?>