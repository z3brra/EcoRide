<?php

namespace App\Service\Drive;

use App\Entity\Drive;
use App\Repository\DriveRepository;
use App\DTO\Drive\DriveReadDTO;

use App\Service\Access\AccessControlService;
use App\Service\StringHelper;
use App\Service\Workflow\TransitionHelper;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Workflow\Registry;


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StartDriveService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DriveRepository $driveRepository,
        private AccessControlService $accessControl,
        private StringHelper $stringHelper,
        private Registry $workflowRegistry,
        private TransitionHelper $transitionHelper,
    ) {}

    public function start(string $identifier): DriveReadDTO
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

        $workflow = $this->workflowRegistry->get($drive, 'drive');
        $this->transitionHelper->guardAndApply($workflow, $drive, 'start');

        $this->entityManager->flush();

        return DriveReadDTO::fromEntity($drive);
    }
}


?>