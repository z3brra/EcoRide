<?php

namespace App\Service\Settlement;

use App\Entity\User;
use App\Repository\{DriveRepository, CreditRepository};

class SettlementLockService
{
    public function __construct(
        private DriveRepository $driveRepository,
        private CreditRepository $creditRepository
    ) {}

    public function hasBlocking(User $user): bool
    {
        $finishedDrives = $this->driveRepository->findFinishedWithParticipant($user);

        foreach ($finishedDrives as $drive) {
            if (!$this->creditRepository->existsForDriveAndParticipant($drive->getUuid(), $user->getUuid())) {
                return true;
            }
        }
        return false;
    }
}

?>