<?php

namespace App\Service\Drive\Participation;

use App\Document\Credit;
use App\Enum\CreditStatusEnum;
use App\Entity\Drive;
use App\Enum\DriveStatusEnum;
use App\Repository\{CreditRepository, DriveRepository};

use App\Service\Billing\CreditService;
use App\Service\Access\AccessControlService;
use App\Service\StringHelper;
use DateTimeImmutable;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\LockMode;

use Symfony\Component\HttpKernel\Exception\{
    AccessDeniedHttpException,
    BadRequestHttpException,
    NotFoundHttpException
};
use Throwable;

class ConfirmParticipationService
{
    private const PLATFORM_FEE = 2;

    public function __construct(
        private DocumentManager $documentManager,
        private EntityManagerInterface $entityManager,
        private CreditRepository $creditRepository,
        private DriveRepository $driveRepository,
        private CreditService $creditService,
        private AccessControlService $accessControl,
        private StringHelper $stringHelper,
    ) {}

    public function confirm(string $identifier): void
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

        if ($this->creditRepository->existsForDriveAndParticipant($drive->getUuid(), $user->getUuid())) {
            throw new BadRequestHttpException("Drive is already confirmed");
        }

        $price = max(0, (int) $drive->getPrice());
        $fee = min(self::PLATFORM_FEE, $price);
        $netAmount = $price - $fee;

        $credit = new Credit();
        $credit->setDriveUuid($drive->getUuid())
               ->setOwnerUuid($driver->getUuid())
               ->setParticipantUuid($user->getUuid())
               ->setAmount($netAmount)
               ->setFee($fee)
               ->setOccurredAt(new DateTimeImmutable())
               ->setStatus(CreditStatusEnum::CONFIRMED);

        try {
            $this->documentManager->persist($credit);
            $this->documentManager->flush();
        } catch (Throwable $e) {
            throw $e;
        }

        $this->entityManager->beginTransaction();
        try {
            $this->entityManager->lock($driver, LockMode::PESSIMISTIC_WRITE);
            // $driver->setCredits($driver->getCredits() ?? 0 + $netAmount);
            $this->creditService->credit($driver, $netAmount);

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Throwable $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }
}
?>