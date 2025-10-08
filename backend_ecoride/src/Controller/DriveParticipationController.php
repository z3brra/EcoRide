<?php

namespace App\Controller;

use App\Service\Drive\Participation\{
    ConfirmParticipationService,
    DisputeParticipationService,
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

use App\Security\Attribute\BypassSettlementLock;

#[Route('/api/drives', name: 'app_api_drives_participation')]
final class DriveParticipationController extends AbstractController
{
    public function __construct(
        private AccessControlService $accessControl,
    ) {}

    #[BypassSettlementLock]
    #[Route('/{identifier}/confirm', name: 'confirm', requirements: ['identifier' => '.+'], methods: 'POST')]
    public function confirm(
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

    #[BypassSettlementLock]
    #[Route('/{identifier}/dispute', name: 'dispute', requirements: ['identifier' => '.+'], methods: 'POST')]
    public function dispute(
        string $identifier,
        Request $request,
        DisputeParticipationService $disputeService
    ): JsonResponse {
        try {
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();

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