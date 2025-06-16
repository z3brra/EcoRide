<?php

namespace App\DTO\User;

use Symfony\Component\Validator\Constraints as Assert;

class UserDTO
{
    #[Assert\NotBlank(message: "Pseudo is required.", groups: ['create'])]
    #[Assert\Length(
        min: 2,
        minMessage: "Pseudo must have at least 2 caracters.",
        max: 64,
        maxMessage: "Pseudo may not exceed 64 caracters.",
        groups: ['create']
    )]
    public ?string $pseudo = null;

    public function isEmpty(): bool
    {
        return $this->pseudo === null;
    }
}

?>
