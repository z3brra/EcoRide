<?php

namespace App\DTO\Drive;

use Symfony\Component\Validator\Constraints as Assert;

use DateTimeImmutable;

class DriveSearchDTO
{

    #[Assert\NotBlank(message: "Depart is required.", groups: ['drive:search'])]
    #[Assert\Length(
        min: 2,
        minMessage: "Depart must have at least 2 chars.",
        max: 255,
        maxMessage: "Depart may not exceed 255 characters.",
        groups: ['drive:search']
    )]
    public ?string $depart = null;

    #[Assert\NotBlank(message: "Arrived is required.", groups: ['drive:search'])]
    #[Assert\Length(
        min: 2,
        minMessage: "Arrived must have at least 2 chars.",
        max: 255,
        maxMessage: "Arrived may not exceed 255 characters.",
        groups: ['drive:search']
    )]
    public ?string $arrived = null;

    #[Assert\NotNull(message: "Depart at is required.", groups: ['drive:search'])]
    #[Assert\Type(DateTimeImmutable::class, message: "Depart at must be a valid date.", groups: ['drive:search'])]
    public ?DateTimeImmutable $departAt = null;

    /** Optionnal filter */

    public ?bool $isElectric = null;

    #[Assert\PositiveOrZero(message: "Max price must be a positive number or free : 0.", groups: ['drive:search'])]
    public ?int $maxPrice = null;

    #[Assert\Positive(message: "Max duration must be a positive number.", groups: ['drive:search'])]
    public ?int $maxDuration = null;

    public ?bool $animals = null;

    public ?bool $smoke = null;

    /** Sort */
    #[Assert\Choice(choices: ['price', 'departAt'], message: "sortBy invalid.", groups: ['sdrive:earch'])]
    public string $sortBy = 'price';

    #[Assert\Choice(choices: ['asc', 'desc'], message: "sortDir invalid.", groups: ['drive:search'])]
    public string $sortDir = 'asc';

    public function isEmpty(): bool
    {
        return $this->depart === null &&
               $this->arrived === null &&
               $this->departAt === null;
    }

}

?>