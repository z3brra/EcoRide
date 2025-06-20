<?php

namespace App\Controller;

use App\DTO\User\{UserEditDTO};

use App\Service\User\{
    ReadUserProfileService,
    EditUserProfileService,
    DeleteUserProfileService,
    BecomeDriverService,
};
use App\Service\AuthCookieService;

use App\Service\Access\AccessControlService;

use Exception;
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
        private AuthCookieService $cookieService,
        private AccessControlService $accessControl,
    ) {}

    #[Route('', name: 'me', methods: 'GET')]
    public function me(
        ReadUserProfileService $readUserService
    ): JsonResponse {
        try {

            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();

            $user = $this->accessControl->getUser();

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
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();

            $user = $this->accessControl->getUser();

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
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();

            $user = $this->accessControl->getUser();

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
        } catch (\Exception $e) {
            return new JsonResponse(
                data: ['error' => "An internal server error as occured"],
                status: JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/driver', name: 'become_driver', methods: 'POST')]
    public function becomeDriver(
        BecomeDriverService $driverService
    ): JsonResponse {
        try {
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();

            $user = $this->accessControl->getUser();

            $userReadDTO = $driverService->becomeDriver($user);

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
        } catch (AccessDeniedHttpException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_FORBIDDEN
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