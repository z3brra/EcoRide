<?php

namespace App\Service\Drive;

use App\DTO\Drive\DriveReadDTO;
use App\DTO\Drive\DriveSearchDTO;

use App\Repository\DriveRepository;
use App\Service\ValidationService;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SearchDriveService
{
    public function __construct(
        private DriveRepository $driveRepository,
        private ValidationService $validationService
    ) {}

    public function search(DriveSearchDTO $driveSearchDTO, int $page, int $limit): array
    {
        if ($driveSearchDTO->isEmpty()) {
            throw new BadRequestHttpException("No data to search");
        }

        $this->validationService->validate($driveSearchDTO, ['search']);

        $result = $this->driveRepository->findPaginated(
            depart: $driveSearchDTO->depart,
            arrived: $driveSearchDTO->arrived,
            departAt: $driveSearchDTO->departAt,
            isElectric: $driveSearchDTO->isElectric,
            maxPrice: $driveSearchDTO->maxPrice,
            maxDuration: $driveSearchDTO->maxDuration,
            animals: $driveSearchDTO->animals,
            smoke: $driveSearchDTO->smoke,
            page: $page,
            limit: $limit,
            sortBy: $driveSearchDTO->sortBy,
            sortDir: $driveSearchDTO->sortDir
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
            'sortBy' => $result['sortBy'],
            'sortDir' => $result['sortDir'],
        ];
    }
}

?>