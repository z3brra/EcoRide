<?php

namespace App\DTO\Vehicle;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

class VehicleDTO
{
    #[Assert\NotBlank(message: "License plate is required.", groups: ['create'])]
    #[Assert\Length(
        min: 2,
        minMessage: "License plate must have at least 2 chars.",
        max: 20,
        maxMessage: "License plate may not exceed 20 characters.",
        groups: ['create']
    )]
    public ?string $licensePlate = null;

    #[Assert\NotNull(message: "First license date is required.", groups: ['create'])]
    #[Assert\Type(DateTimeImmutable::class, message: "First license date must be a valid date.", groups: ['create', 'update'])]
    public ?DateTimeImmutable $firstLicenseDate = null;

    #[Assert\NotNull(message: "Is electric flag is required.", groups: ['create'])]
    public ?bool $isElectric = null;

    #[Assert\NotBlank(message: "Color is required.", groups: ['create'])]
    #[Assert\Choice(
        choices: ["BLACK", "GREY", "WHITE", "BROWN", "RED", "ORANGE", "YELLOW", "GREEN", "BLUE", "PURPLE", "PINK"],
        message: "Color is incorrect.",
        groups: ['create', 'update']
    )]
    public ?string $color = null;

    #[Assert\NotNull(message: "Number of seats is required.", groups: ['create'])]
    #[Assert\Positive(message: "Number of seats must be a positive number.", groups: ['create', 'update'])]
    public ?int $seats = null;

    public function isEmpty(): bool
    {
        return $this->licensePlate     === null &&
               $this->firstLicenseDate === null &&
               $this->isElectric       === null &&
               $this->color            === null &&
               $this->seats            === null;
    }

}

?>
