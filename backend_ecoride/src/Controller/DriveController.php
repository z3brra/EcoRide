<?php

namespace App\Controller;

use App\DTO\Drive\{DriveDTO};

use App\Service\Drive\{
    CreateDriveService,
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
}

?>
