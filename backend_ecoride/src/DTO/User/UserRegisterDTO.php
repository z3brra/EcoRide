<?php

namespace App\DTO\User;

use Symfony\Component\Validator\Constraints as Assert;

class UserRegisterDTO
{
    #[Assert\NotBlank(message: "Pseudo is required.", groups: ['create'])]
    #[Assert\Length(
        min: 1,
        minMessage: "Pseudo must have at least 1 caracters.",
        max: 64,
        maxMessage: "Pseudo may not exceed 64 caracters.",
        groups: ['create']
    )]
    public ?string $pseudo = null;

    #[Assert\NotBlank(message: "Email is required.", groups: ['create'])]
    #[Assert\Email(message: "Email must be valid.", groups: ['create'])]
    #[Assert\Length(
        min: 1,
        minMessage: "Email must have at least 1 caracters.",
        max: 180,
        maxMessage: "Email may not exceed 180 caracters.",
        groups: ['create']
    )]
    public ?string $email = null;

    #[Assert\NotBlank(message: "Password is required.", groups: ['create'])]
    #[Assert\Length(
        min: 12,
        minMessage: "Password must have at least 12 caracters.",
        groups: ['create']
    )]

    # Used this website for test regex : https://regex101.com/
    #[Assert\Regex(
        pattern: "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).+$/",
        message: "Password must contain at least one uppercase, one lowercase and one number.",
        groups: ['create']
    )]
    public ?string $password = null;

    public function isEmpty(): bool
    {
        return $this->pseudo   === null &&
               $this->email    === null &&
               $this->password === null;
    }
}

?>
