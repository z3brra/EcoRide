<?php

namespace App\Controller;

use App\DTO\Drive\{DriveDTO, DriveSearchDTO};

use App\Service\Drive\{
    CreateDriveService,
    ReadDriveService,
    UpdateDriveService,
    ListDrivePaginatedService,
    JoinDriveService,
    LeaveDriveService,
    StartDriveService,
    FinishDriveService,
    CancelDriveService,
    SearchDriveService,

    ConfirmParticipationService,
    DisputeParticipationService,
};

use App\Service\Access\AccessControlService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, JsonResponse};
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use LogicException;
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

    #[Route('/own', name: 'list', methods: 'GET')]
    public function list(
        Request $request,
        ListDrivePaginatedService $listDriveService
    ): JsonResponse {
        try {
            $page = max(1, (int) $request->query->get('page', 1));
            $limit = max(1, (int) $request->query->get('limit', 10));

            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();
            $this->accessControl->denyUnlessDriver();

            $user = $this->getUser();

            $drivePaginated = $listDriveService->listDrivePaginatedByUser($user, $page, $limit);

            $responseData = $this->serializer->serialize(
                data: $drivePaginated,
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

    #[Route('/{identifier}', name: 'read', requirements: ['identifier' => '.+'], methods: 'GET')]
    public function read(
        string $identifier,
        ReadDriveService $readDriveService,
    ): JsonResponse {
        try {
            // $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();
            // $this->accessControl->denyUnlessDriver();

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

    #[Route('/{identifier}/finish', name: 'finish', requirements: ['identifier' => '.+'], methods: 'POST')]
    public function finish(
        string $identifier,
        FinishDriveService $finishDriveService
    ): JsonResponse {
        try {
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyUnlessDriver();
            $this->accessControl->denyIfBanned();

            $finishDriveService->finish($identifier);

            return new JsonResponse(
                data: ['message' => 'Drive successfully finished'],
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
        } 
        // catch (\Exception $e) {
        //     return new JsonResponse(
        //         data: ['error' => "An internal server error as occured"],
        //         status: JsonResponse::HTTP_INTERNAL_SERVER_ERROR
        //     );
        // }
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

    #[Route('/search', name: 'search', methods: 'POST')]
    public function search(
        Request $request,
        SearchDriveService $searchDriveService
    ): JsonResponse {
        try {
            $this->accessControl->denyIfBanned();

            $page = max(1, (int) $request->query->get('page', 1));
            $limit = max(1, (int) $request->query->get('limit', 10));

            try {
                $searchDriveDTO = $this->serializer->deserialize(
                    data: $request->getContent(),
                    type: DriveSearchDTO::class,
                    format: 'json'
                );
            } catch (\Exception $e) {
                throw new BadRequestHttpException("Invalid JSON format");
            }

            $searchDrivePaginated = $searchDriveService->search($searchDriveDTO, $page, $limit);

            $responseData = $this->serializer->serialize(
                data: $searchDrivePaginated,
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
        } catch (LogicException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/{identifier}/confirm', name: 'confirm_participation', requirements: ['identifier' => '.+'], methods: 'POST')]
    public function confirmParticipation(
        string $identifier,
        ConfirmParticipationService $confirmService,
    ): JsonResponse {
        try {
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();

            $confirmService->confirm($identifier);

            return new JsonResponse(
                data: ['message' => 'Participation confirmed'],
                status: JsonResponse::HTTP_OK
            );

        } catch (AccessDeniedHttpException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_FORBIDDEN
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
        } catch (\Exception $e) {
            return new JsonResponse(
                data: ['error' => "An internal server error as occured"],
                status: JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/{identifier}/dispute', name: 'dispute_participation', requirements: ['identifier' => '.+'], methods: 'POST')]
    public function dispute(
        string $identifier,
        Request $request,
        DisputeParticipationService $disputeService
    ): JsonResponse {
        try {
            $raw = $request->getContent();
            if ($raw === null || $raw === '') {
                $payload = [];
            } else {
                $payload = json_decode($raw, true);
                if ($payload === null && json_last_error() !== JSON_ERROR_NONE) {
                    throw new BadRequestHttpException("Invalid JSON format");
                }
                if (!is_array($payload)) {
                    throw new BadRequestHttpException("Invalid JSON format");
                }
            }

            $comment = null;
            if (array_key_exists('comment', $payload)) {
                if (!is_string($payload['comment'])) {
                    throw new BadRequestHttpException("Comment must be string");
                }
                $comment = trim($payload['comment']);
                if ($comment === '') {
                    $comment = null;
                }
            }

            $disputeService->dispute($identifier, $comment);

            return new JsonResponse(
                data: ['message' => 'Dispute opened'],
                status: JsonResponse::HTTP_OK
            );

        } catch (AccessDeniedHttpException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_FORBIDDEN
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
        } catch (\Exception $e) {
            return new JsonResponse(
                data: ['error' => "An internal server error as occured"],
                status: JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}

?>
