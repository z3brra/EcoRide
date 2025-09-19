<?php

namespace App\Service\Drive;

use App\Entity\Drive;
use App\DTO\Drive\DriveReadDTO;
use App\Repository\DriveRepository;

use App\Service\Access\AccessControlService;
use App\Service\Billing\RefundService;
use App\Service\StringHelper;
use App\Service\Workflow\TransitionHelper;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Workflow\Registry;

use Symfony\Component\HttpKernel\Exception\{NotFoundHttpException};
use Throwable;

class CancelDriveService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DriveRepository $driveRepository,
        private AccessControlService $accessControl,
        private RefundService $refundService,
        private StringHelper $stringHelper,
        private Registry $workflowRegistry,
        private TransitionHelper $transitionHelper,
    ) {}

    public function cancel(string $identifier): void
    {
        if ($this->stringHelper->isUuid($identifier)) {
            $drive = $this->driveRepository->findOneByUuid($identifier);
        } else {
            $drive = $this->driveRepository->findOneByReference($identifier);
        }
        if (!$drive instanceof Drive) {
            throw new NotFoundHttpException("Drive not found or does not exist");
        }

        $this->accessControl->denyUnlessOwnerByRelation($drive);

        $this->entityManager->beginTransaction();
        try {
            $this->entityManager->lock($drive, LockMode::PESSIMISTIC_WRITE);

            $workflow = $this->workflowRegistry->get($drive, 'drive');
            $this->transitionHelper->guardAndApply($workflow, $drive, 'cancel');

            $price = $drive->getPrice() ?? 0;
            if ($price > 0) {
                foreach ($drive->getParticipants() as $participant) {
                    $this->refundService->refund($participant, $price);
                }
            }

            $this->entityManager->flush();
            $this->entityManager->commit();

        } catch (Throwable $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }
}


?>