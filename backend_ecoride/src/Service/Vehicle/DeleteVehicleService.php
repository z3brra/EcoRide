<?php

namespace App\Service\Vehicle;

use App\Repository\VehicleRepository;
use App\Service\Access\AccessControlService;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpKernel\Exception\{NotFoundHttpException};

class DeleteVehicleService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private VehicleRepository $vehicleRepository,
        private AccessControlService $accessControl,
    ) {}

    public function deleteVehicle(string $uuid): void
    {
        $vehicle = $this->vehicleRepository->findOneByUuid($uuid);
        if (!$vehicle) {
            throw new NotFoundHttpException("Vehicle not found or does not exist");
        }

        $this->accessControl->denyUnlessOwnerByRelation($vehicle);

        $this->entityManager->remove($vehicle);
        $this->entityManager->flush();
    }
}

?>