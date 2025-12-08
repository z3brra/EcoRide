<?php

namespace App\Service\Admin;

use App\DTO\User\{UserSearchDTO, UserReadDTO};
use App\Repository\UserRepository;
use App\Service\ValidationService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SearchUserService
{
    public function __construct(
        private UserRepository $userRepository,
        private ValidationService $validationService,
    ) {}

    public function searchUser(UserSearchDTO $userSearchDTO): UserReadDTO
    {
        $this->validationService->validate($userSearchDTO, ['search']);

        $user = $this->userRepository->findOneByEmail($userSearchDTO->email);
        if (!$user) {
            throw new NotFoundHttpException("User not found or does not exist.");
        }

        return UserReadDTO::fromEntity($user);
    }
}

?>