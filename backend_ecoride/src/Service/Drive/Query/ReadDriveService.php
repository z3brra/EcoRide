<?php

namespace App\Service\Drive\Query;

use App\Entity\Drive;
use App\DTO\Drive\DriveReadDTO;
use App\Repository\DriveRepository;

use App\Service\Access\AccessControlService;
use App\Service\StringHelper;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReadDriveService
{
    public function __construct(
        private DriveRepository $driveRepository,
        private AccessControlService $accessControl,
        private StringHelper $stringHelper,
    ) {}

    public function getDrive(string $identifier): DriveReadDTO
    {
        if ($this->stringHelper->isUuid($identifier)) {
            $drive = $this->driveRepository->findOneByUuid($identifier);
        } else {
            $drive = $this->driveRepository->findOneByReference($identifier);
        }

        if (!$drive instanceof Drive) {
            throw new NotFoundHttpException("Drive not found or does not exist");
        }

        // $this->accessControl->denyUnlessOwnerByRelation($drive);
        // $this->accessControl->denyIfBanned();
        // $this->accessControl->denyUnlessLogged()

        return DriveReadDTO::fromEntity($drive);
    }
}

?>