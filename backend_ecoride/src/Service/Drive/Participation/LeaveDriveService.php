<?php

namespace App\Service\Drive\Participation;

use App\Entity\Drive;
use App\Repository\DriveRepository;
use App\DTO\Drive\DriveReadDTO;

use App\Service\Access\AccessControlService;
use App\Service\Billing\CreditService;
use App\Service\StringHelper;
use App\Service\Workflow\TransitionHelper;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\LockMode;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException ;
use Symfony\Component\Workflow\Registry;

use Throwable;

class LeaveDriveService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DriveRepository $driveRepository,
        private AccessControlService $accessControl,
        private CreditService $creditService,
        private StringHelper $stringHelper,
        private Registry $workflowRegistry,
        private TransitionHelper $transitionHelper
    ) {}

    public function leave(string $identifier): DriveReadDTO
    {
        if ($this->stringHelper->isUuid($identifier)) {
            $drive = $this->driveRepository->findOneByUuid($identifier);
        } else {
            $drive = $this->driveRepository->findOneByReference($identifier);
        }
        if (!$drive instanceof Drive) {
            throw new NotFoundHttpException("Drive not found or does not exist");
        }

        $this->entityManager->beginTransaction();

        try {
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();

            $user = $this->accessControl->getUser();
            $this->entityManager->lock($user, LockMode::PESSIMISTIC_WRITE);

            $this->entityManager->lock($drive, LockMode::PESSIMISTIC_WRITE);

            $workflow = $this->workflowRegistry->get($drive, 'drive');
            $this->transitionHelper->guardAndApply($workflow, $drive, 'leave');

            $price = $drive->getPrice() ?? 0;
            if ($price > 0) {
                $this->creditService->credit($user, $price);
            }

            $drive->removeParticipant($user);

            $this->entityManager->flush();
            $this->entityManager->commit();

        } catch (Throwable $e) {
            $this->entityManager->rollback();
            throw $e;
        }

        return DriveReadDTO::fromEntity($drive);
    }
}

?>