<?php

namespace App\Service\Vehicle;

use App\Entity\User;

use App\Repository\VehicleRepository;
use App\DTO\Vehicle\{VehicleDTO, VehicleReadDTO};
use App\Service\Access\AccessControlService;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;

use DateTimeImmutable;

use Symfony\Component\HttpKernel\Exception\{
    NotFoundHttpException,
    BadRequestHttpException,
};

class UpdateVehicleService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private VehicleRepository $vehicleRepository,
        private ValidationService $validationService,
        private AccessControlService $accessControl
    ) {}

    public function updateVehicle(User $user, string $uuid, VehicleDTO $vehicleUpdateDTO): VehicleReadDTO
    {
        $vehicle = $this->vehicleRepository->findOneByUuid($uuid);
        if (!$vehicle) {
            throw new NotFoundHttpException("Vehicle not found or does not exist");
        }

        $this->accessControl->denyUnlessOwnerByRelation($vehicle);

        if ($vehicleUpdateDTO->isEmpty()) {
            throw new BadRequestHttpException("No data to update");
        }

        $this->validationService->validate($vehicleUpdateDTO, ['update']);

        $firstLicenseDate = $vehicleUpdateDTO->firstLicenseDate;
        $isElectric = $vehicleUpdateDTO->isElectric;
        $color = $vehicleUpdateDTO->color;
        $seats = $vehicleUpdateDTO->seats;

        if ($firstLicenseDate !== null) {
            $vehicle->setFirstLicenseDate($firstLicenseDate);
        }
        if ($isElectric !== null) {
            $vehicle->setIsElectric($isElectric);
        }
        if ($color !== null) {
            $vehicle->setColor($color);
        }
        if ($seats !== null) {
            $vehicle->setSeats($seats);
        }
        $vehicle->setUpdatedAt(new DateTimeImmutable());

        $this->entityManager->flush();

        return VehicleReadDTO::fromEntity($vehicle);

    }
}

?>