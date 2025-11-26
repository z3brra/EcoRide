<?php

namespace App\Service\Settlement;

use App\Entity\User;
use App\Entity\Drive;
use App\Repository\{DriveRepository, CreditRepository};

class SettlementLockService
{
    public function __construct(
        private DriveRepository $driveRepository,
        private CreditRepository $creditRepository
    ) {}

    // public function hasBlocking(User $user): ?Drive
    public function getBlockingDrive(User $user): ?Drive
    {
        $finishedDrives = $this->driveRepository->findFinishedWithParticipant($user);

        foreach ($finishedDrives as $drive) {
            if (!$this->creditRepository->existsForDriveAndParticipant($drive->getUuid(), $user->getUuid())) {
                // return true;
                return $drive;
            }
        }
        // return false;
        return null;
    }
}

?>