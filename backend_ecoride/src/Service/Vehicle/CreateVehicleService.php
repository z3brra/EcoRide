<?php

namespace App\Service\Vehicle;

use App\Entity\Vehicle;
use App\Entity\User;

use App\Repository\VehicleRepository;
use App\DTO\Vehicle\{VehicleDTO, VehicleReadDTO};

use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use DateTimeImmutable;

use Symfony\Component\HttpKernel\Exception\{BadRequestHttpException, ConflictHttpException};


class CreateVehicleService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private VehicleRepository $vehicleRepository,
        private ValidationService $validationService
    ) {}

    public function createVehicle(UserInterface $user, VehicleDTO $vehicleCreateDTO): VehicleReadDTO
    {
        if ($vehicleCreateDTO->isEmpty()) {
            throw new BadRequestHttpException("No data to update");
        }

        $this->validationService->validate($vehicleCreateDTO, ['create']);

        if ($this->vehicleRepository->findOneByLicensePlate($vehicleCreateDTO->licensePlate)) {
            throw new ConflictHttpException("License plate already exist");
        }

        $vehicle = new Vehicle();
        $vehicle->setLicensePlate($vehicleCreateDTO->licensePlate)
                ->setFirstLicenseDate($vehicleCreateDTO->firstLicenseDate)
                ->setIsElectric($vehicleCreateDTO->isElectric)
                ->setColor($vehicleCreateDTO->color)
                ->setSeats($vehicleCreateDTO->seats)
                ->setCreatedAt(new DateTimeImmutable())
                ->setOwner($user);

        $this->entityManager->persist($vehicle);
        $this->entityManager->flush();

        return VehicleReadDTO::fromEntity($vehicle);
    }
}

?>


