<?php

namespace App\Service\Drive;

use App\Entity\Drive;
use App\Repository\DriveRepository;
use App\DTO\Drive\DriveReadDTO;

use App\Enum\DriveStatus;

use App\Service\Access\AccessControlService;
use App\Service\StringHelper;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\{
    BadRequestHttpException,
    ConflictHttpException,
    NotFoundHttpException
};

class StartDriveService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DriveRepository $driveRepository,
        private AccessControlService $accessControl,
        private StringHelper $stringHelper,
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

        if ($drive->getStatus() !== DriveStatus::OPEN) {
            throw new ConflictHttpException("Drive already started or closed");
        }

        if ($drive->getParticipants()->isEmpty()) {
            throw new BadRequestHttpException("Cannot start a drive whit no participants");
        }

        $drive->setStatus(DriveStatus::IN_PROGRESS);
        $this->entityManager->flush();

        return DriveReadDTO::fromEntity($drive);
    }
}


?>