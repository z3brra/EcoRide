<?php

namespace App\Controller;

use App\Service\DriverReview\Moderate\ModerateDriverReviewService;
use App\Service\DriverReview\Manage\ListDriverReviewService;
use App\Service\Access\AccessControlService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, JsonResponse};
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\HttpKernel\Exception\{
    NotFoundHttpException,
    AccessDeniedHttpException,
    BadRequestHttpException,
};

#[Route('/api/employee/reviews', name: 'app_api_employee_reviews_')]
final class DriverReviewEmployeeController extends AbstractController
{
    public function __construct(
        private AccessControlService $accessControl,
        private SerializerInterface $serializer,
    ) {}

    #[Route('/{uuid}', name: 'moderate', methods: 'POST')]
    public function moderate(
        string $uuid,
        Request $request,
        ModerateDriverReviewService $moderateReviewService,
    ): JsonResponse {
        try {
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();
            $this->accessControl->denyUnlessEmployee();

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

            if (!array_key_exists('action', $payload)) {
                throw new BadRequestHttpException("Field 'action' is required");
            }
            if (!is_string($payload['action'])) {
                throw new BadRequestHttpException("Field 'action' must be string");
            }

            $action = trim($payload['action']);
            if ($action === '') {
                throw new BadRequestHttpException("Field 'action' cannot be empty");
            }

            $moderateReviewService->moderate($uuid, $action);

            return new JsonResponse(
                ['message' => 'Review successfully moderated'],
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

    #[Route('', name: 'list_pending', methods: 'GET')]
    public function listForEmployee(
        Request $request,
        ListDriverReviewService $listReviewService
    ): JsonResponse {
        try {
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();
            $this->accessControl->denyUnlessEmployee();

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

            $reviewPaginated = $listReviewService->listForEmployee($sortDir, $page, $limit);

            $responseData = $this->serializer->serialize(
                data: $reviewPaginated,
                format: 'json',
                context: ['groups' => ['review:employee']]
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
        } catch (\Exception $e) {
            return new JsonResponse(
                data: ['error' => "An internal server error as occured"],
                status: JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

}

?>
