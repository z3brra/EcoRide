<?php

namespace App\Service\Admin;

use App\Entity\User;
use App\Repository\UserRepository;

use App\DTO\User\UserReadDTO;

class ListEmployeeService {
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function listEmployeePaginated(int $page, int $limit): array
    {
        $result = $this->userRepository->findEmployeePaginated($page, $limit);

        $employeeDTOs = [];
        foreach ($result['data'] as $employee) {
            $employeeDTOs[] = UserReadDTO::fromEntity($employee);
        }

        return [
            'data' => $employeeDTOs,
            'total' => $result['total'],
            'totalPages' => $result['totalPages'],
            'currentPage' => $result['currentPage'],
            'perPage' => $result['perPage'],
        ];
    }
}

?>