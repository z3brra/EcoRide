<?php

namespace App\Controller;

use App\DTO\User\{UserDTO, UserReadDTO};
use App\Service\Admin\{
    CreateUserService
};

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, JsonResponse};

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\HttpKernel\Exception\{BadRequestHttpException, ConflictHttpException};
use Symfony\Component\Serializer\Annotation\Context;

#[Route('/api/admin', name: 'app_api_admin_')]
final class AdminController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
    ) {}

    #[Route('/create-user', name: 'create_user', methods: 'POST')]
    public function createUser(
        Request $request,
        CreateUserService $createUserService

    ): JsonResponse {
        try {
            try {
                $userCreateDTO = $this->serializer->deserialize(
                    data: $request->getContent(),
                    type: UserDTO::class,
                    format: 'json'
                );
            } catch (\Exception $e) {
                throw new BadRequestHttpException("Invalid JSON format");
            }

            $userReadDTO = $createUserService->createUser($userCreateDTO);

            $responseData = $this->serializer->serialize(
                data: $userReadDTO,
                format: 'json',
                context: ['groups' => ['user:read', 'user:create']]
            );

            return new JsonResponse(
                data: $responseData,
                status: JsonResponse::HTTP_CREATED,
                json: true
            );

        } catch (BadRequestHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_BAD_REQUEST
            );
        } catch (ConflictHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_CONFLICT
            );
        }
    }
}

?>