<?php

namespace App\Service\Drive;

use App\Entity\Drive;
use App\Repository\DriveRepository;
use App\DTO\Drive\DriveReadDTO;

use App\Service\Access\AccessControlService;
use App\Service\StringHelper;
use App\Service\Workflow\TransitionHelper;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\LockMode;
use Symfony\Component\Workflow\Registry;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class JoinDriveService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DriveRepository $driveRepository,
        private AccessControlService $accessControl,
        private StringHelper $stringHelper,
        private Registry $workflowRegistry,
        private TransitionHelper $transitionHelper,
    ) {}

    public function join(string $identifier): DriveReadDTO
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
            $this->entityManager->lock($drive, LockMode::PESSIMISTIC_WRITE);

            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();

            $workflow = $this->workflowRegistry->get($drive, 'drive');
            $this->transitionHelper->guardAndApply($workflow, $drive, 'join');

            $user = $this->accessControl->getUser();
            $drive->addParticipant($user);

        // $availableSeats = $drive->getAvailableSeats();
        // $participantCount = $drive->getParticipants()->count();
        // $drive->setAvailableSeats($availableSeats - $participantCount);

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