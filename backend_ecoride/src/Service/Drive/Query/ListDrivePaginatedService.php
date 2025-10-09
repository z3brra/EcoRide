<?php

namespace App\Service\Drive\Query;

use App\DTO\Drive\DriveOwnedHistoryDTO;

use App\Repository\DriveRepository;
use App\DTO\Drive\DriveReadDTO;

use App\Service\Access\AccessControlService;
use App\Service\ValidationService;

use Doctrine\ORM\EntityManagerInterface;

class ListDrivePaginatedService
{
    public function __construct(
        private EntityManagerInterface $entityManager,

        private DriveRepository $driveRepository,
        private ValidationService $validationService,
        private AccessControlService $accessControl,
    ) {}

    public function listOwned(DriveOwnedHistoryDTO $driveOwnedDTO, int $page, int $limit): array
    {
        $this->validationService->validate($driveOwnedDTO, ['drive:history']);
        $owner = $this->accessControl->getUser();

        $result = $this->driveRepository->findOwnedPaginated(
            owner: $owner,
            status: $driveOwnedDTO->status,
            depart: $driveOwnedDTO->depart,
            arrived: $driveOwnedDTO->arrived,
            includeCancelled: $driveOwnedDTO->includeCancelled,
            page: $page,
            limit: $limit,
            sortDir: $driveOwnedDTO->sortDir
        );

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
            'sortDir' => $result['sortDir'],
        ];
    }

    // public function listJoined(): array
    // {

    // }

    // public function listDrivePaginatedByUser(UserInterface $user, int $page, int $limit): array
    // {
    //     if (!$user instanceof User) {
    //         throw new LogicException("Invalid user type");
    //     }

    //     if ($user->isBanned()) {
    //         throw new AccessDeniedHttpException("This account is banned");
    //     }

    //     $result = $this->driveRepository->findCurrentUserPaginated($user, $page, $limit);

    //     $driveDTOs = [];
    //     foreach ($result['data'] as $drive) {
    //         $driveDTOs[] = DriveReadDTO::fromEntity($drive);
    //     }

    //     return [
    //         'data' => $driveDTOs,
    //         'total' => $result['total'],
    //         'totalPages' => $result['totalPages'],
    //         'currentPage' => $result['currentPage'],
    //         'perPage' => $result['perPage'],
    //     ];
    // }
}

?>
