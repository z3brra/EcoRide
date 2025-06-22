<?php

namespace App\Service\Drive;

use App\Entity\User;

use App\Entity\Drive;
use App\DTO\Drive\{DriveDTO, DriveReadDTO};

use App\Repository\{DriveRepository, VehicleRepository};

use App\Service\{ValidationService, StringHelper};
use App\Service\Access\AccessControlService;


use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpKernel\Exception\{
    BadRequestHttpException,
    NotFoundHttpException
};

use DateTimeImmutable;

class UpdateDriveService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DriveRepository $driveRepository,
        private VehicleRepository $vehicleRepository,
        private ValidationService $validationService,
        private AccessControlService $accessControl,
        private StringHelper $stringHelper,
    ) {}

    public function update(string $identifier, DriveDTO $updateDriveDTO): DriveReadDTO
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

        $this->validationService->validate($updateDriveDTO, ['update']);

        /**
         * To see when user can change Vehicle for X reason (COULD )
         * 
         * $vehicleUuid = $updateDriveDTO->vehicleUuid;
         * if ($vehicleUuid !== null) {
         *     $vehicle = $this->vehicleRepository->findOneByUuid($vehicleUuid);
         *     if (!$vehicle) {
         *         throw new NotFoundHttpException("Vehicle not found or does not exist");
         *     }
         *     $this->accessControl->denyUnlessOwnerByRelation($vehicle);
         *     $drive->setVehicle($vehicle);
         * }
         */

        $vehicleSeats = $drive->getVehicle()->getSeats();
        if ($updateDriveDTO->availableSeats !== null) {
            if ($updateDriveDTO->availableSeats < 1 || $updateDriveDTO->availableSeats > $vehicleSeats) {
                throw new BadRequestHttpException("Available seats can't exceed vehicle seats");
            }
            $drive->setAvailableSeats($updateDriveDTO->availableSeats);
        }

        $departAt = $updateDriveDTO->departAt;
        if ($departAt !== null) {
            $drive->setDepartAt($departAt);
        }

        $arrivedAt = $updateDriveDTO->arrivedAt;
        if ($arrivedAt !== null) {
            $drive->setArrivedAt($arrivedAt);
        }

        $this->entityManager->flush();

        return DriveReadDTO::fromEntity($drive);
    }
}



?>