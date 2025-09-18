<?php

namespace App\DTO\Drive;

use Symfony\Component\Validator\Constraints as Assert;

use DateTimeImmutable;

class DriveSearchDTO
{

    #[Assert\NotBlank(message: "Depart is required.", groups: ['search'])]
    #[Assert\Length(
        min: 2,
        minMessage: "Depart must have at least 2 chars.",
        max: 255,
        maxMessage: "Depart may not exceed 255 characters.",
        groups: ['search']
    )]
    public ?string $depart = null;

    #[Assert\NotBlank(message: "Arrived is required.", groups: ['search'])]
    #[Assert\Length(
        min: 2,
        minMessage: "Arrived must have at least 2 chars.",
        max: 255,
        maxMessage: "Arrived may not exceed 255 characters.",
        groups: ['search']
    )]
    public ?string $arrived = null;

    #[Assert\NotNull(message: "Depart at is required.", groups: ['search'])]
    #[Assert\Type(DateTimeImmutable::class, message: "Depart at must be a valid date.", groups: ['search'])]
    public ?DateTimeImmutable $departAt = null;

    /** Optionnal filter */

    public ?bool $isElectric = null;

    #[Assert\PositiveOrZero(message: "Max price must be a positive number or free : 0.", groups: ['search'])]
    public ?int $maxPrice = null;

    #[Assert\Positive(message: "Max duration must be a positive number.", groups: ['search'])]
    public ?int $maxDuration = null;

    public ?bool $animals = null;

    public ?bool $smoke = null;

    public function isEmpty(): bool
    {
        return $this->depart === null &&
               $this->arrived === null &&
               $this->departAt === null;
    }

}

?>