<?php

namespace  App\Service\Drive;

use App\Entity\{Drive, User};

use App\DTO\Drive\{DriveDTO, DriveReadDTO};
use App\Enum\DriveStatus;

use App\Repository\VehicleRepository;

use App\Service\ValidationService;
use App\Service\Access\AccessControlService;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreateDriveService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private VehicleRepository $vehicleRepository,
        private ValidationService $validationService,
        private AccessControlService $accessControl,
    ) {}

    public function create(User $driver, DriveDTO $driveCreateDTO): DriveReadDTO
    {
        if ($driveCreateDTO->isEmpty()) {
            throw new BadRequestHttpException("No data to update");
        }

        $this->validationService->validate($driveCreateDTO, ['create']);

        $vehicle = $this->vehicleRepository->findOneByUuid($driveCreateDTO->vehicleUuid);
        if (!$vehicle) {
            throw new NotFoundHttpException("Vehicle not found or does not exist");
        }

        $this->accessControl->denyUnlessOwnerByRelation($vehicle);

        $vehicleSeats = $vehicle->getSeats();
        $seats = $driveCreateDTO->availableSeats ?? $vehicleSeats;

        if ($seats < 1 || $seats > $vehicleSeats) {
            throw new BadRequestHttpException("Available seats can't exceed vehicle seats");
        }

        if ($driveCreateDTO->departAt >= $driveCreateDTO->arrivedAt) {
            throw new BadRequestHttpException("Depart at must be earlier than Arrived at");
        }

        $drive = new Drive();
        $drive->setOwner($driver)
              ->setVehicle($vehicle)
              ->setAvailableSeats($seats)
              ->setPrice($driveCreateDTO->price)
              ->setDistance($driveCreateDTO->distance)
              ->setDepart($driveCreateDTO->depart)
              ->setDepartAt($driveCreateDTO->departAt)
              ->setArrived($driveCreateDTO->arrived)
              ->setArrivedAt($driveCreateDTO->arrivedAt)
              ->setStatus(DriveStatus::OPEN);

        $this->entityManager->persist($drive);
        $this->entityManager->flush();

        return DriveReadDTO::fromEntity($drive);
    }
}

?>