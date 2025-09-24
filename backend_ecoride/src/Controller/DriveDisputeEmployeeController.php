<?php

namespace App\Controller;

use App\Service\Drive\Dispute\{
    ResolveDisputeService,
};

use App\Service\Access\AccessControlService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, JsonResponse};
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\HttpKernel\Exception\{
    NotFoundHttpException,
    AccessDeniedHttpException,
    BadRequestHttpException,
};

#[Route('/api/employee/drives', name: 'app_api_employee_drives_disputes_')]
final class DriveDisputeEmployeeController extends AbstractController
{
    public function __construct(
        private AccessControlService $accessControl,
    ) {}

    #[Route('/{identifier}/disputes/{participantUuid}/resolve', name: 'resolve', requirements: ['identifier' => '.+'], methods: 'POST')]
    public function resolve(
        string $identifier,
        string $participantUuid,
        Request $request,
        ResolveDisputeService $resolveDisputeService,
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

            $resolveDisputeService->resolve($identifier, $participantUuid, $action, $comment);

            return new JsonResponse(
                ['message' => 'Dispute resolved'],
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