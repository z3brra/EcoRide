<?php

namespace App\DTO\User;

use Symfony\Component\Validator\Constraints as Assert;

class UserEditDTO
{
    #[Assert\NotBlank(message: "Pseudo is required.", groups: ['create'])]
    #[Assert\Length(
        min: 2,
        minMessage: "Pseudo must have at least 2 caracters.",
        max: 64,
        maxMessage: "Pseudo may not exceed 64 caracters.",
        groups: ['update']
    )]
    public ?string $pseudo = null;


    public ?string $oldPassword = null;

    # Used this website for test regex : https://regex101.com/
    #[Assert\Regex(
        pattern: "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).+$/",
        message: "Password must contain at least one uppercase, one lowercase and one number.",
        groups: ['update']
    )]
    public ?string $newPassword = null;

    public function isEmpty(): bool
    {
        return $this->pseudo === null &&
               $this->oldPassword === null &&
               $this->newPassword === null;
    }
}

?>
