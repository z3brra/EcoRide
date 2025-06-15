<?php

namespace App\DTO\User;

use Symfony\Component\Validator\Constraints as Assert;

class UserLoginDTO
{
    #[Assert\NotBlank(message: "Username is required", groups: ['login'])]
    public ?string $username = null;

    #[Assert\NotBlank(message: "Password is required", groups: ['login'])]
    public ?string $password = null;

    public function isEmpty(): bool
    {
        return $this->username === null &&
               $this->password === null;
    }
}

?>