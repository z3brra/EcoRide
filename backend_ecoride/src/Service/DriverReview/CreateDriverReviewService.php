<?php

namespace App\Service\DriverReview;

use App\Entity\{DriverReview};

use App\DTO\DriverReview\{DriverReviewDTO, DriverReviewReadDTO};

use App\Enum\DriverReviewEnum;

use App\Repository\{DriverReviewRepository, DriveRepository};

use App\Service\ValidationService;
use App\Service\Access\AccessControlService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\{
    BadRequestHttpException,
    NotFoundHttpException
};

class CreateDriverReviewService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DriverReviewRepository $reviewRepository,
        private DriveRepository $driveRepository,
        private ValidationService $validationService,
        private AccessControlService $accessControl
    ) {}

    public function create(DriverReviewDTO $reviewCreateDTO): DriverReviewReadDTO
    {
        if ($reviewCreateDTO->isEmpty()) {
            throw new BadRequestHttpException("No data to update");
        }

        $this->validationService->validate($reviewCreateDTO, ['create']);

        $drive = $this->driveRepository->findOneByUuid($reviewCreateDTO->driveUuid);
        if (!$drive) {
            throw new NotFoundHttpException("Drive not found or does not exist");
        }

        $author = $this->accessControl->getUser();

        if ($this->accessControl->isOwnerByEntityRelation($drive)) {
            throw new BadRequestHttpException("Owner can't leave review on his own drive");
        }

        if (!$drive->getParticipants()->contains($author)) {
            throw new BadRequestHttpException("User is not participant of the drive");
        }

        if ($this->reviewRepository->existsForDriveAndAuthor($drive, $author)) {
            throw new BadRequestHttpException("A review already exist for this drive");
        }

        $driver = $drive->getOwner();

        $review = new DriverReview();
        $review->setDriver($driver)
               ->setAuthor($author)
               ->setDrive($drive)
               ->setRate($reviewCreateDTO->rate)
               ->setComment($reviewCreateDTO->comment)
               ->setStatus(DriverReviewEnum::PENDING)
               ->setCreatedAt(new DateTimeImmutable());

        $this->entityManager->persist($review);
        $this->entityManager->flush();

        return DriverReviewReadDTO::fromEntity($review);
    }
}

?>