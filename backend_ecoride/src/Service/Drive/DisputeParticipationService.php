<?php

namespace App\Service\Drive;

use App\Document\Credit;
use App\Enum\CreditStatusEnum;
use App\Entity\Drive;
use App\Enum\DriveStatusEnum;
use App\Repository\{CreditRepository, DriveRepository};

use App\Service\Access\AccessControlService;
use App\Service\StringHelper;
use DateTimeImmutable;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpKernel\Exception\{
    AccessDeniedHttpException,
    BadRequestHttpException,
    NotFoundHttpException
};
use Throwable;


class DisputeParticipationService
{
    public function __construct(
        private DocumentManager $documentManager,
        private CreditRepository $creditRepository,
        private DriveRepository $driveRepository,
        private AccessControlService $accessControl,
        private StringHelper $stringHelper,
    ) {}

    public function dispute(string $identifier, ?string $comment = null): void
    {
        if ($this->stringHelper->isUuid($identifier)) {
            $drive = $this->driveRepository->findOneByUuid($identifier);
        } else {
            $drive = $this->driveRepository->findOneByReference($identifier);
        }
        if (!$drive instanceof Drive) {
            throw new NotFoundHttpException("Drive not found or does not exist");
        }

        $user = $this->accessControl->getUser();
        $driver = $drive->getOwner();

        if ($this->accessControl->isOwnerByEntityRelation($drive)) {
            throw new AccessDeniedHttpException("Owner cannot confirm his own drive");
        }

        if (!$drive->getParticipants()->contains($user)) {
            throw new AccessDeniedHttpException("User is not participant");
        }

        if ($drive->getStatus() !== DriveStatusEnum::FINISHED->value) {
            throw new BadRequestHttpException("Drive must be finished to confirm");
        }

        $existing = $this->creditRepository->findOneByDriveAndParticipant($drive->getUuid(), $user->getUuid());
        if ($existing instanceof Credit) {
            $existingStatus = $existing->getStatus();
            if ($existingStatus === CreditStatusEnum::CONFIRMED->value || $existingStatus === CreditStatusEnum::REFUNDED->value) {
                throw new BadRequestHttpException("The dispute is closed");
            }
            if ($existingStatus === CreditStatusEnum::PENDING->value && $comment !== null && $comment !== '') {
                $existing->setComment($comment);
                $this->documentManager->flush();
            }
            return;
        }

        $credit = new Credit();
        $credit->setDriveUuid($drive->getUuid())
               ->setOwnerUuid($driver->getUuid())
               ->setParticipantUuid($user->getUuid())
               ->setAmount(0)
               ->setFee(0)
               ->setOccurredAt(new DateTimeImmutable())
               ->setStatus(CreditStatusEnum::PENDING);
        if ($comment !== null) {
            $credit->setComment($comment);
        }

        try {
            $this->documentManager->persist($credit);
            $this->documentManager->flush();
        } catch (Throwable $e) {
            var_dump('ici');
            throw $e;
        }
    }
}

?>