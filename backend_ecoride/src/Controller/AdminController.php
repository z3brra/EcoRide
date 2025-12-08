<?php

namespace App\Controller;

use App\DTO\User\{UserDTO, UserReadDTO};
use App\Service\Admin\{
    CreateUserService,
    BanUserService,
    ListEmployeeService
};

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, JsonResponse};

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\HttpKernel\Exception\{NotFoundHttpException, BadRequestHttpException, ConflictHttpException};
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

    #[Route('/ban-user', name: 'ban_user', methods: 'POST')]
    public function banUser(
        Request $request,
        BanUserService $banUserService
    ): JsonResponse {
        try {
            $userUuid = $request->query->get('userUuid', null);
            if (!$userUuid) {
                throw new BadRequestHttpException("userUuid is required");
            }

            $banUserService->banUser($userUuid);

            return new JsonResponse(
                data: ['message' => 'User successfully banned'],
                status: JsonResponse::HTTP_OK
            );

        } catch (NotFoundHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_NOT_FOUND
            );
        } catch (BadRequestHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route('/unban-user', name: 'unban_user', methods: 'POST')]
    public function unbanUser(
        Request $request,
        BanUserService $banUserService
    ): JsonResponse {
        try {
            $userUuid = $request->query->get('userUuid', null);
            if (!$userUuid) {
                throw new BadRequestHttpException("userUuid is required");
            }

            $banUserService->unbanUser($userUuid);

            return new JsonResponse(
                data: ['message' => 'User successfully unbanned'],
                status: JsonResponse::HTTP_OK
            );

        } catch (NotFoundHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_NOT_FOUND
            );
        } catch (BadRequestHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route('/employee', name: 'list_employee', methods: 'GET')]
    public function listEmployee(
        Request $request,
        ListEmployeeService $listEmployeeService
    ): JsonResponse {
        try {
            $page = max(1, (int) $request->query->get('page', 1));
            $limit = max(1, (int) $request->query->get('limit', 10));

            $employeePaginated = $listEmployeeService->listEmployeePaginated($page, $limit);

            $responseData = $this->serializer->serialize(
                data: $employeePaginated,
                format: 'json',
                context: ['grous' => ['user:list']]
            );

            return new JsonResponse(
                data: $responseData,
                status: JsonResponse::HTTP_OK,
                json: true
            );

        } catch (BadRequestHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_BAD_REQUEST
            );
        }
    }
}

?>