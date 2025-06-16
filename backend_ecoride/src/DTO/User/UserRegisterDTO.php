<?php

namespace App\DTO\User;

use Symfony\Component\Validator\Constraints as Assert;

class UserRegisterDTO
{
    #[Assert\NotBlank(message: "Pseudo is required.", groups: ['create'])]
    #[Assert\Length(
        min: 1,
        minMessage: "Pseudo must have at least 2 caracters.",
        max: 64,
        maxMessage: "Pseudo may not exceed 64 caracters.",
        groups: ['create']
    )]
    public ?string $pseudo = null;

    #[Assert\Length(
        min: 1,
        minMessage: "Email must have at least 2 caracters.",
        max: 180,
        maxMessage: "Email may not exceed 64 caracters.",
        groups: ['create']
    )]
    #[Assert\NotBlank(message: "Email is required.", groups: ['create'])]
    public ?string $email = null;

    #[Assert\Length(
        min: 12,
        minMessage: "Password must have at least 12 caracters.",
        groups: ['create']
    )]
    #[Assert\NotBlank(message: "Password is required.", groups: ['create'])]
    public ?string $password = null;

    public function isEmpty(): bool
    {
        return $this->pseudo   === null &&
               $this->email    === null &&
               $this->password === null;
    }
}

?>
