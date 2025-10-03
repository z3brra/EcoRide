<?php

namespace App\Controller;

use App\DTO\DriverReview\{DriverReviewDTO, DriverReviewReadDTO};

use App\Service\DriverReview\Manage\{
    CreateDriverReviewService
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
}


?>
