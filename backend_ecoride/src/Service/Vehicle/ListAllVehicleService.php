<?php

namespace App\Service\Vehicle;

use App\Entity\User;

use App\Repository\VehicleRepository;
use App\DTO\Vehicle\VehicleReadDTO;

use Symfony\Component\Security\Core\User\UserInterface;

use LogicException;
use Symfony\Component\HttpKernel\Exception\{
    AccessDeniedHttpException,
};

class ListAllVehicleService
{
    public function __construct(
        private VehicleRepository $vehicleRepository,
    ) {}

    public function listAllVehicle(UserInterface $user): array
    {
        if (!$user instanceof User) {
            throw new LogicException("Invalid user type");
        }

        if ($user->isBanned()) {
            throw new AccessDeniedHttpException("This account is banned");
        }

        $vehicles = $this->vehicleRepository->findAllCurrentUser($user);

        $vehicleDTOs = [];
        foreach ($vehicles as $vehicle) {
            $vehicleDTOs[] = VehicleReadDTO::fromEntity($vehicle);
        }
        return [
            "data" => $vehicleDTOs,
            "total" => count($vehicleDTOs)
        ];
    }
}


?>