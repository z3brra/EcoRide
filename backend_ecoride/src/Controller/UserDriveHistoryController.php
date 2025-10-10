<?php

namespace App\Controller;

use App\DTO\Drive\{DriveJoinedHistoryDTO, DriveOwnedHistoryDTO};

use App\Service\Drive\Query\ListDrivePaginatedService;

use App\Service\Access\AccessControlService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, JsonResponse};
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\HttpKernel\Exception\{
    AccessDeniedHttpException,
    BadRequestHttpException,
};


#[Route('/api/user/drives', name: 'app_api_user_drives_history_')]
final class UserDriveHistoryController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private AccessControlService $accessControl,
    ) {}

    #[Route('/owned', name: 'owned', methods: 'GET')]
    public function owned(
        Request $request,
        ListDrivePaginatedService $listDriveService,
    ): JsonResponse
    {
        try {
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyUnlessDriver();
            $this->accessControl->denyIfBanned();

            $page = max(1, (int) $request->query->get('page', 1));
            $limit = max(1, (int) $request->query->get('limit', 10));

            $raw = trim((string) $request->getContent());

            if ($raw === '') {
                $driveOwnedDTO = DriveOwnedHistoryDTO::fromQuery($request->query->all());
            } else {
                $driveOwnedDTO = $this->serializer->deserialize(
                    data: $raw,
                    type: DriveOwnedHistoryDTO::class,
                    format: 'json',
                );
            }

            $drivePaginated = $listDriveService->listOwned($driveOwnedDTO, $page, $limit);

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
        } catch (BadRequestHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route('/joined', name: 'joined', methods: 'GET')]
    public function joined(
        Request $request,
        ListDrivePaginatedService $listDriveService
    ): JsonResponse {
        try {
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();

            $page = max(1, (int) $request->query->get('page', 1));
            $limit = max(1, (int) $request->query->get('limit', 10));

            $raw = trim((string) $request->getContent());

            if ($raw === '') {
                $driveJoinedDTO = DriveJoinedHistoryDTO::fromQuery($request->query->all());
            } else {
                $driveJoinedDTO = $this->serializer->deserialize(
                    data: $raw,
                    type: DriveJoinedHistoryDTO::class,
                    format: 'json',
                );
            }

            $drivePaginated = $listDriveService->listJoined($driveJoinedDTO, $page, $limit);

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
        } catch (BadRequestHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_BAD_REQUEST
            );
        }
    }


}

?>