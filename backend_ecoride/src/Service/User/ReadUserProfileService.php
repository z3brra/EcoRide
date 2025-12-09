<?php

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\DTO\User\UserReadDTO;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReadUserProfileService
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function getProfile(User $user): UserReadDTO
    {
        return UserReadDTO::fromEntity($user);
    }

    public function getProfileByUuid(string $uuid): UserReadDTO
    {
        $user = $this->userRepository->findOneByUuid($uuid);
        if (!$user) {
            throw new NotFoundHttpException("User not found or does not exist.");
        }

        return UserReadDTO::fromEntity($user);
    }

}


?>