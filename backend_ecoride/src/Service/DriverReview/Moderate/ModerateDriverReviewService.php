<?php

namespace App\Service\DriverReview\Moderate;

use App\Entity\DriverReview;

use App\Enum\{DriverReviewEnum, DriverReviewActionEnum};

use App\Repository\DriverReviewRepository;

use App\Service\Access\AccessControlService;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpKernel\Exception\{
    BadRequestHttpException,
    NotFoundHttpException
};

class ModerateDriverReviewService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DriverReviewRepository $reviewRepository,
        private AccessControlService $accessControl,
    ) {}

    public function moderate(string $uuid, string $action): void
    {
        $this->accessControl->denyUnlessEmployee();

        $review = $this->reviewRepository->findOneByUuid($uuid);
        if (!$review instanceof DriverReview) {
            throw new NotFoundHttpException("Review not found or does not exist");
        }

        if ($review->getStatus() !== DriverReviewEnum::PENDING->value) {
            throw new BadRequestHttpException("Review already moderated");
        }

        $action = strtolower(trim($action));

        switch ($action) {
            case DriverReviewActionEnum::VALIDATE->value:
                $review->setStatus(DriverReviewEnum::VALIDATED);

                $this->entityManager->flush();
                break;

            case DriverReviewActionEnum::REFUSE->value:
                $review->setStatus(DriverReviewEnum::REFUSED);

                $this->entityManager->flush();
                break;

            default:
                throw new BadRequestHttpException("Unknown action. 'refuse' or 'validate' only");
                break;
        }
    }
}

?>