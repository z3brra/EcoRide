<?php

namespace App\Service\Vehicle;

use App\Entity\User;

use App\Repository\VehicleRepository;
use App\DTO\Vehicle\VehicleReadDTO;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use LogicException;
use Symfony\Component\HttpKernel\Exception\{
    AccessDeniedHttpException,
};

class ListVehiclePaginatedService
{
    public function __construct(
        private EntityManagerInterface $entityManager,

        private VehicleRepository $vehicleRepository,
    ) {}

    public function listVehiclePaginatedByUser(UserInterface $user, int $page, int $limit): array
    {
        if (!$user instanceof User) {
            throw new LogicException("Invalid user type");
        }

        if ($user->isBanned()) {
            throw new AccessDeniedHttpException("This account is banned");
        }

        $result = $this->vehicleRepository->findCurrentUserPaginated($user, $page, $limit);

        $vehicleDTOs = [];
        foreach ($result['data'] as $vehicle) {
            $vehicleDTOs[] = VehicleReadDTO::fromEntity($vehicle);
        }

        return [
            'data' => $vehicleDTOs,
            'total' => $result['total'],
            'totalPages' => $result['totalPages'],
            'currentPage' => $result['currentPage'],
            'perPage' => $result['perPage'],
        ];
    }
}


?>