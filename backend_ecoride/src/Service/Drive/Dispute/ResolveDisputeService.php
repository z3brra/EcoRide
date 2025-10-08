<?php

namespace App\Service\Drive\Dispute;

use App\Document\Credit;
use App\Entity\{Drive, User};
use App\Enum\{CreditStatusEnum, CreditActionEnum, DriveStatusEnum};
use App\Repository\{CreditRepository, DriveRepository, UserRepository};

use App\Service\Billing\CreditService;
use App\Service\Access\AccessControlService;
use App\Service\StringHelper;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\LockMode;

use Symfony\Component\HttpKernel\Exception\{
    AccessDeniedHttpException,
    BadRequestHttpException,
    NotFoundHttpException
};
use Throwable;

class ResolveDisputeService
{
    private const PLATFORM_FEE = 2;

    public function __construct(
        private DocumentManager $documentManager,
        private EntityManagerInterface $entityManager,
        private CreditRepository $creditRepository,
        private DriveRepository $driveRepository,
        private UserRepository $userRepository,
        private CreditService $creditService,
        private AccessControlService $accessControl,
        private StringHelper $stringHelper,
    ) {}

    public function resolve(string $identifier, string $participantUid, string $action, ?string $comment = null): void
    {
        $this->accessControl->denyUnlessEmployee();

        if ($this->stringHelper->isUuid($identifier)) {
            $drive = $this->driveRepository->findOneByUuid($identifier);
        } else {
            $drive = $this->driveRepository->findOneByReference($identifier);
        }
        if (!$drive instanceof Drive) {
            throw new NotFoundHttpException("Drive not found or does not exist");
        }

        $participant = $this->userRepository->findOneByUuid($participantUid);
        if (!$participant instanceof User) {
            throw new NotFoundHttpException("Participant not found or does not exist");
        }

        if (!$drive->getParticipants()->contains($participant)) {
            throw new AccessDeniedHttpException("User is not participant");
        }

        if ($drive->getStatus() !== DriveStatusEnum::FINISHED->value) {
            throw new BadRequestHttpException("Drive must be finished to confirm");
        }

        $existingCredit = $this->creditRepository->findOneByDriveAndParticipant($drive->getUuid(), $participant->getUuid());
        if (!$existingCredit instanceof Credit) {
            throw new NotFoundHttpException("No pending dispute found for this participant");
        }

        if ($existingCredit->getStatus() !== CreditStatusEnum::PENDING->value) {
            throw new BadRequestHttpException("Dispute already resolved");
        }

        $action = strtolower(trim($action));
        $drivePrice = max(0, (int) $drive->getPrice());

        switch ($action) {
            case CreditActionEnum::CONFIRM->value:
                $fee = min(self::PLATFORM_FEE, $drivePrice);
                $netAmount = $drivePrice - $fee;

                $driver = $drive->getOwner();

                $this->entityManager->beginTransaction();
                try {
                    $this->entityManager->lock($driver, LockMode::PESSIMISTIC_WRITE);
                    $this->creditService->credit($driver, $netAmount);

                    $this->entityManager->flush();
                    $this->entityManager->commit();
                } catch (Throwable $e) {
                    $this->entityManager->rollback();
                    throw $e;
                }

                $existingCredit->setAmount($netAmount)
                               ->setFee($fee)
                               ->setStatus(CreditStatusEnum::CONFIRMED);
                if ($comment !== null) {
                    $existingCredit->setComment($comment);
                }

                $this->documentManager->flush();
                break;
            case CreditActionEnum::REFUND->value:
                $this->entityManager->beginTransaction();
                try {
                    $this->entityManager->lock($participant, LockMode::PESSIMISTIC_WRITE);
                    $this->creditService->credit($participant, $drivePrice);

                    $this->entityManager->flush();
                    $this->entityManager->commit();
                } catch (Throwable $e) {
                    $this->entityManager->rollback();
                    throw $e;
                }

                $existingCredit->setAmount(0)
                               ->setFee(0)
                               ->setStatus(CreditStatusEnum::REFUNDED);
                if ($comment !== null) {
                    $existingCredit->setComment($comment);
                }

                $this->documentManager->flush();
                break;
            default:
                throw new BadRequestHttpException("Unknown action. User 'confirm' or 'refund'");
                break;
        }
    }
}

?>