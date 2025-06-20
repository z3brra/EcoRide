<?php

namespace App\Service\Vehicle;

use App\Repository\VehicleRepository;
use App\DTO\Vehicle\VehicleReadDTO;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReadVehicleService
{
    public function __construct(
        private VehicleRepository $vehicleRepository
    ) {}

    public function getVehicle(string $uuid): VehicleReadDTO
    {
        $vehicle = $this->vehicleRepository->findOneByUuid($uuid);
        if (!$vehicle) {
            throw new NotFoundHttpException("Vehicle not found or does not exist");
        }
        return VehicleReadDTO::fromEntity($vehicle);
    }
}

?>