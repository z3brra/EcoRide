<?php

namespace App\Service\User;

use App\Entity\User;
use App\DTO\User\UserReadDTO;
use Symfony\Component\Security\Core\User\UserInterface;

use LogicException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ReadUserProfileService
{
    public function getProfile(UserInterface $user): UserReadDTO
    {
        if (!$user instanceof User) {
            throw new LogicException("Invalid user type");
        }

        if ($user->isBanned()) {
            throw new AccessDeniedHttpException("This account is banned");
        }

        return UserReadDTO::fromEntity($user);
    }

}


?>