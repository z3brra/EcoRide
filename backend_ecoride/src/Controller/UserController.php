<?php

namespace App\Controller;

use App\DTO\User\{UserReadDTO};

use App\Service\User\{
    ReadUserProfileService
};
use Exception;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, JsonResponse};
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

#[Route('/api/user', name: 'app_api_user_')]
final class UserController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer
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
}


?>