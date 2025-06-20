<?php

namespace App\Service\Vehicle;

use App\Entity\User;

use App\Repository\VehicleRepository;
use App\DTO\Vehicle\{VehicleDTO, VehicleReadDTO};

use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use DateTimeImmutable;

use LogicException;
use Symfony\Component\HttpKernel\Exception\{
    NotFoundHttpException,
    AccessDeniedHttpException,
    BadRequestHttpException,
    ConflictHttpException,
};

class UpdateVehicleService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private VehicleRepository $vehicleRepository,
        private ValidationService $validationService
    ) {}

    public function updateVehicle(UserInterface $user, string $uuid, VehicleDTO $vehicleUpdateDTO): VehicleReadDTO
    {
        if (!$user instanceof User) {
            throw new LogicException("Invalid user type");
        }

        if ($user->isBanned()) {
            throw new AccessDeniedHttpException("This account is banned");
        }

        $vehicle = $this->vehicleRepository->findOneByUuid($uuid);
        if (!$vehicle) {
            throw new NotFoundHttpException("Vehicle not found or does not exist");
        }

        if ($vehicleUpdateDTO->isEmpty()) {
            throw new BadRequestHttpException("No data to update");
        }

        if ($user->getUuid() !== $vehicle->getOwner()->getUuid()) {
            throw new AccessDeniedHttpException("This vehicle does not belong to the current user");
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