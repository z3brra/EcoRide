<?php

namespace App\Controller;

use App\DTO\DriverReview\{DriverReviewDTO, DriverReviewReadDTO};

use App\Service\DriverReview\Manage\{
    CreateDriverReviewService,
    ListDriverReviewService
};

use App\Service\Access\AccessControlService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, JsonResponse};
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\HttpKernel\Exception\{
    NotFoundHttpException,
    BadRequestHttpException,
    AccessDeniedHttpException
};

#[Route('/api/reviews', name: 'app_api_reviews_')]
final class DriverReviewController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private AccessControlService $accessControl
    ) {}

    #[Route('', name: 'create', methods: 'POST')]
    public function create(
        Request $request,
        CreateDriverReviewService $createReviewService
    ): JsonResponse {
        try {
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();

            try {
                $createReviewDTO = $this->serializer->deserialize(
                    data: $request->getContent(),
                    type: DriverReviewDTO::class,
                    format: 'json'
                );
            } catch (\Exception $e) {
                throw new BadRequestHttpException("Invalid JSON format");
            }

            $readReviewDTO = $createReviewService->create($createReviewDTO);

            $responseData = $this->serializer->serialize(
                data: $readReviewDTO,
                format: 'json',
                context: ['groups' => ['review:author']]
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

    #[Route('/{driverUuid}', name: 'list_validated', methods: 'GET')]
    public function listValidatedForPublic(
        string $driverUuid,
        Request $request,
        ListDriverReviewService $listReviewService
    ): JsonResponse {
        try {
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();

            $page = max(1, (int) $request->get('page', 1));
            $limit = max(1, (int) $request->get('limit', 10));

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

            if (array_key_exists('sortDir', $payload)) {
                if (!is_string($payload['sortDir'])) {
                    throw new BadRequestHttpException("Field 'sortDir' must be string");
                }
                $sortDir = trim($payload['sortDir']);
                if ($sortDir === '') {
                    $sortDir = 'ASC';
                }
            } else {
                $sortDir = 'ASC';
            }

            $reviewPaginated = $listReviewService->listForPublic($driverUuid, $sortDir, $page, $limit);

            $responseData = $this->serializer->serialize(
                data: $reviewPaginated,
                format: 'json',
                context: ['groups' => ['review:public']]
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
}


?>
