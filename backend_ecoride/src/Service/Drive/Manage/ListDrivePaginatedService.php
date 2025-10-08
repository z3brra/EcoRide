<?php

namespace App\Service\Drive\Manage;

use App\Entity\User;

use App\Repository\DriveRepository;
use App\DTO\Drive\DriveReadDTO;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use LogicException;
use Symfony\Component\HttpKernel\Exception\{
    AccessDeniedHttpException,
};

class ListDrivePaginatedService
{
    public function __construct(
        private EntityManagerInterface $entityManager,

        private DriveRepository $driveRepository,
    ) {}

    public function listDrivePaginatedByUser(UserInterface $user, int $page, int $limit): array
    {
        if (!$user instanceof User) {
            throw new LogicException("Invalid user type");
        }

        if ($user->isBanned()) {
            throw new AccessDeniedHttpException("This account is banned");
        }

        $result = $this->driveRepository->findCurrentUserPaginated($user, $page, $limit);

        $driveDTOs = [];
        foreach ($result['data'] as $drive) {
            $driveDTOs[] = DriveReadDTO::fromEntity($drive);
        }

        return [
            'data' => $driveDTOs,
            'total' => $result['total'],
            'totalPages' => $result['totalPages'],
            'currentPage' => $result['currentPage'],
            'perPage' => $result['perPage'],
        ];
    }
}

?>
