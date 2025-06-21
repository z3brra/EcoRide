<?php

namespace App\Service\Vehicle;

use App\Entity\User;

use App\Repository\VehicleRepository;
use App\Service\Access\AccessControlService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;


use LogicException;
use Symfony\Component\HttpKernel\Exception\{
    NotFoundHttpException,
    AccessDeniedHttpException,
    BadRequestHttpException,
    ConflictHttpException,
};

class DeleteVehicleService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private VehicleRepository $vehicleRepository,
        private AccessControlService $accessControl,
    ) {}

    public function deleteVehicle(UserInterface $user, string $uuid): void
    {
        // if (!$user instanceof User) {
        //     throw new LogicException("Invalid user type");
        // }

        // if ($user->isBanned()) {
        //     throw new AccessDeniedHttpException("This account is banned");
        // }

        $vehicle = $this->vehicleRepository->findOneByUuid($uuid);
        if (!$vehicle) {
            throw new NotFoundHttpException("Vehicle not found or does not exist");
        }

        $this->accessControl->denyUnlessOwnerByRelation($vehicle);

        // if ($user->getUuid() !== $vehicle->getOwner()->getUuid()) {
        //     throw new AccessDeniedHttpException("This vehicle does not belong to the current user");
        // }


        $this->entityManager->remove($vehicle);
        $this->entityManager->flush();
    }
}

?>