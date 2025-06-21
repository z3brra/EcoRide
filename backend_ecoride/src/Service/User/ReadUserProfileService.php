<?php

namespace App\Service\User;

use App\Entity\User;
use App\DTO\User\UserReadDTO;

class ReadUserProfileService
{
    public function getProfile(User $user): UserReadDTO
    {
        return UserReadDTO::fromEntity($user);
    }

}


?>