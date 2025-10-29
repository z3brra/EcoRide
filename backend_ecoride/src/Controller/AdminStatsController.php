<?php

namespace App\Controller;

use App\Service\Admin\Stats\AdminCreditStatsService;
use App\Service\Access\AccessControlService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, JsonResponse};
use Symfony\Component\Routing\Attribute\Route;

use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\{
    AccessDeniedHttpException,
    BadRequestHttpException,
};

#[Route('/api/admin/stats', name: 'app_api_admin_stats')]
final class AdminStatsController extends AbstractController
{
    public function __construct(
        private AccessControlService $accessControl
    ) {}

    #[Route('/platform-fee', name: 'platform_fee', methods: 'GET')]
    public function platformStats(
        Request $request,
        AdminCreditStatsService $statsService
    ): JsonResponse {
        try {
             $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyUnlessAdmin();
            $this->accessControl->denyIfBanned();

            $range = (string) $request->get('range', 'today');
            $yearParam = $request->get('year');
            $year = $yearParam !== null ? (int) $yearParam : null;

            $responseData = $statsService->getStats($range, $year);

            return new JsonResponse(
                data: $responseData,
                status: JsonResponse::HTTP_OK
            );
        } catch (AccessDeniedHttpException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_FORBIDDEN
            );
        } catch (InvalidArgumentException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_BAD_REQUEST
            );
        } 
    }
}



?>