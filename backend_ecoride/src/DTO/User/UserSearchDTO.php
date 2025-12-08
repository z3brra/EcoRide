<?php

namespace App\DTO\User;

use Symfony\Component\Validator\Constraints as Assert;

class UserSearchDTO
{
    #[Assert\NotNull(message: "User email is required", groups: ['search'])]
    public ?string $email = null;

    public function isEmpty(): bool
    {
        return $this->email === null;
    }
}


?>