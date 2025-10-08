<?php

namespace App\Service\DriverReview\Manage;

use App\DTO\DriverReview\DriverReviewReadDTO;
use App\Enum\DriverReviewEnum;
use App\Repository\{DriverReviewRepository, UserRepository};

use App\Service\Access\AccessControlService;

use Symfony\Component\HttpKernel\Exception\{
    BadRequestHttpException,
    NotFoundHttpException
};

class ListDriverReviewService
{
    public function __construct(
        private DriverReviewRepository $reviewRepository,
        private UserRepository $userRepository,
        private AccessControlService $accessControl,
    ) {}


    public function listForPublic(string $driverUuid, string $sortDir, int $page, int $limit): array
    {
        $driver = $this->userRepository->findOneByUuid($driverUuid);
        if (!$driver) {
            throw new NotFoundHttpException("Driver not found or does not exist");
        }

        $sortDir = strtolower($sortDir) === 'desc' ? 'DESC' : 'ASC';

        $result = $this->reviewRepository->findValidatedForPublicPaginated($driver, $sortDir, $page, $limit);

        $reviewDTOs = [];
        foreach ($result['data'] as $review) {
            $reviewDTOs[] = DriverReviewReadDTO::fromEntity($review);
        }

        $averageRate = $this->reviewRepository->getValidatedStatsForDriver($driver);

        return [
            'data' => $reviewDTOs,
            'averageRate' => $averageRate['average'],
            'total' => $result['total'],
            'totalPages' => $result['totalPages'],
            'currentPage' => $result['currentPage'],
            'perPage' => $result['perPage'],
            'sortDir' => $sortDir,
        ];
    }

    public function listForUser(string $role, ?string $status = null, string $sortDir, int $page, int $limit): array
    {
        if ($status !== null) {
            if (!DriverReviewEnum::isValid($status)) {
                throw new BadRequestHttpException("Status is not correct.");
            }
        }

        $sortDir = strtolower($sortDir) === 'desc' ? 'DESC' : 'ASC';

        $user = $this->accessControl->getUser();

        $result = $this->reviewRepository->findForUserPaginated($user, $role, $status, $sortDir, $page, $limit);

        $reviewDTOs = [];
        foreach ($result['data'] as $review) {
            $reviewDTOs[] = DriverReviewReadDTO::fromEntity($review);
        }

        $resultData = [
            'data' => $reviewDTOs,
            'total' => $result['total'],
            'totalPages' => $result['totalPages'],
            'currentPage' => $result['currentPage'],
            'perPage' => $result['perPage'],
            'sortDir' => $sortDir,
        ];

        if ($role === 'driver') {
            $averageRate = $this->reviewRepository->getValidatedStatsForDriver($user);
            $resultData['averageRate'] = $averageRate['average'];
        }

        return $resultData;
    }

    public function listForEmployee(string $sortDir, int $page, int $limit): array
    {
        $sortDir = strtolower($sortDir) === 'desc' ? 'DESC' : 'ASC';

        $result = $this->reviewRepository->findForEmployeePaginated($sortDir, $page, $limit);

        $reviewDTOs = [];
        foreach ($result['data'] as $review) {
            $reviewDTOs[] = DriverReviewReadDTO::fromEntity($review);
        }

        return [
            'data' => $reviewDTOs,
            'total' => $result['total'],
            'totalPages' => $result['totalPages'],
            'currentPage' => $result['currentPage'],
            'perPage' => $result['perPage'],
            'sortDir' => $sortDir,
        ];
    }

}

?>
