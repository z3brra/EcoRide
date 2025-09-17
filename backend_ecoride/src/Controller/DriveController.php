<?php

namespace App\Controller;

use App\DTO\Drive\{DriveDTO};

use App\Service\Drive\{
    CreateDriveService,
    ReadDriveService,
    UpdateDriveService,
    JoinDriveService,
    LeaveDriveService,
    StartDriveService,
    CancelDriveService,
};

use App\Service\Access\AccessControlService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, JsonResponse};
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\HttpKernel\Exception\{
    NotFoundHttpException,
    AccessDeniedHttpException,
    BadRequestHttpException,
    ConflictHttpException
};

#[Route('/api/drives', name: 'app_api_drives_')]
final class DriveController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private AccessControlService $accessControl,
    ) {}

    #[Route('', name: 'create', methods: 'POST')]
    public function create(
        Request $request,
        CreateDriveService $createDriveService,
    ): JsonResponse {
        try {
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyUnlessDriver();
            $this->accessControl->denyIfBanned();

            $user = $this->accessControl->getUser();

            try {
                $createDriveDTO = $this->serializer->deserialize(
                    data: $request->getContent(),
                    type: DriveDTO::class,
                    format: 'json'
                );
            } catch (\Exception $e) {
                throw new BadRequestHttpException("Invalid JSON format");
            }

            $readDriveDTO = $createDriveService->create($user, $createDriveDTO);

            $responseData = $this->serializer->serialize(
                data: $readDriveDTO,
                format: 'json',
                context: ['groups' => ['drive:read']]
            );

            return new JsonResponse(
                data: $responseData,
                status: JsonResponse::HTTP_CREATED,
                json: true
            );

        } catch (AccessDeniedHttpException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_FORBIDDEN
            );
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
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_NOT_FOUND
            );
        }
    }

    #[Route('/{identifier}', name: 'read', requirements: ['identifier' => '.+'], methods: 'GET')]
    public function read(
        string $identifier,
        ReadDriveService $readDriveService,
    ): JsonResponse {
        try {
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();
            $this->accessControl->denyUnlessDriver();

            $readDriveDTO = $readDriveService->getDrive($identifier);

            $responseData = $this->serializer->serialize(
                data: $readDriveDTO,
                format: 'json',
                context: ['groups' => ['drive:read']]
            );

            return new JsonResponse(
                data: $responseData,
                status: JsonResponse::HTTP_OK,
                json: true
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
        } catch (AccessDeniedHttpException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_FORBIDDEN
            );
        }
    }

    #[Route('/{identifier}', name: 'update', requirements: ['identifier' => '.+'], methods: 'PUT')]
    public function update(
        string $identifier,
        Request $request,
        UpdateDriveService $updateDriveService,
    ): JsonResponse {
        try {
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyUnlessDriver();
            $this->accessControl->denyIfBanned();

            try {
                $updateDriveDTO = $this->serializer->deserialize(
                    data: $request->getContent(),
                    type: DriveDTO::class,
                    format: 'json'
                );
            } catch (\Exception $e) {
                throw new BadRequestHttpException("Invalid JSON format");
            }

            $readDriveDTO = $updateDriveService->update($identifier, $updateDriveDTO);

            $responseData = $this->serializer->serialize(
                data: $readDriveDTO,
                format: 'json',
                context: ['groups' => ['drive:read']]
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
        } catch (BadRequestHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_BAD_REQUEST
            );
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_NOT_FOUND
            );
        }
    }

    #[Route('/{identifier}', name: 'cancel', requirements: ['identifier' => '.+'], methods: 'DELETE')]
    public function cancel(
        string $identifier,
        CancelDriveService $cancelDriveService,
    ): JsonResponse {
        try {
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyUnlessDriver();
            $this->accessControl->denyIfBanned();

            $cancelDriveService->cancel($identifier);

            return new JsonResponse(
                data: ['message' => 'Drive successfully deleted'],
                status: JsonResponse::HTTP_OK,
            );

        } catch (AccessDeniedHttpException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_FORBIDDEN
            );
        } catch (BadRequestHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_BAD_REQUEST
            );
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_NOT_FOUND
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                data: ['error' => "An internal server error as occured"],
                status: JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/{identifier}/join', name: 'join', requirements: ['identifier' => '.+'], methods: 'POST')]
    public function join(
        string $identifier,
        JoinDriveService $joinDriveService
    ): JsonResponse {
        try {
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();

            $readDriveDTO = $joinDriveService->join($identifier);

            $responseData = $this->serializer->serialize(
                data: $readDriveDTO,
                format: 'json',
                context: ['groups' => ['drive:read']]
            );

            return new JsonResponse(
                data: $responseData,
                status: JsonResponse::HTTP_OK,
                json: true
            );
        }
        catch (NotFoundHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_NOT_FOUND
            );
        }
    }

    #[Route('/{identifier}/leave', name: 'leave', requirements: ['identifier' => '.+'], methods: 'POST')]
    public function leave(
        string $identifier,
        LeaveDriveService $leaveDriveService
    ): JsonResponse {
        try {
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();

            $readDriveDTO = $leaveDriveService->leave($identifier);

            $responseData = $this->serializer->serialize(
                data: $readDriveDTO,
                format: 'json',
                context: ['groups' => ['drive:read']]
            );

            return new JsonResponse(
                data: $responseData,
                status: JsonResponse::HTTP_OK,
                json: true
            );
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_NOT_FOUND
            );
        }
    }

    #[Route('/{identifier}/start', name: 'start', requirements: ['identifier' => '.+'], methods: 'POST')]
    public function start(
        string $identifier,
        StartDriveService $startDriveService
    ): JsonResponse {
        try {
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();
            $this->accessControl->denyUnlessDriver();

            $readDriveDTO = $startDriveService->start($identifier);

            $responseData = $this->serializer->serialize(
                data: $readDriveDTO,
                format: 'json',
                context: ['groups' => ['drive:read']]
            );

            return new JsonResponse(
                data: $responseData,
                status: JsonResponse::HTTP_OK,
                json: true
            );

        }
        catch (NotFoundHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_NOT_FOUND
            );
        }
    }

}

?>
