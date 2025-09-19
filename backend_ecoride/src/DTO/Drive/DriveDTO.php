<?php

namespace App\DTO\Drive;

use App\Enum\DriveStatusEnum;

use Symfony\Component\Validator\Constraints as Assert;

use DateTimeImmutable;

class DriveDTO
{
    #[Assert\NotBlank(message: "Vehicle uuid is required.", groups: ['create'])]
    public ?string $vehicleUuid = null;

    #[Assert\NotNull(message: "Price is required.", groups: ['create'])]
    #[Assert\PositiveOrZero(message: "Price must be a positive number or free : 0.", groups: ['create'])]
    public ?int $price = null;

    #[Assert\Positive(message: "Available must be a positive number.", groups: ['create', 'update'])]
    public ?int $availableSeats = null;

    #[Assert\NotNull(message: "Distance is required.", groups: ['create'])]
    #[Assert\Positive(message: "Distance must be a positive number.", groups: ['create'])]
    public ?float $distance = null;

    #[Assert\NotBlank(message: "Depart is required.", groups: ['create'])]
    #[Assert\Length(
        min: 2,
        minMessage: "Depart must have at least 2 chars.",
        max: 255,
        maxMessage: "Depart may not exceed 255 characters.",
        groups: ['create']
    )]
    public ?string $depart = null;

    #[Assert\NotNull(message: "Depart at is required.", groups: ['create'])]
    #[Assert\Type(DateTimeImmutable::class, message: "Depart at must be a valid date.", groups: ['create', 'update'])]
    public ?DateTimeImmutable $departAt = null;

    #[Assert\NotBlank(message: "Arrived is required.", groups: ['create'])]
    #[Assert\Length(
        min: 2,
        minMessage: "Arrived must have at least 2 chars.",
        max: 255,
        maxMessage: "Arrived may not exceed 255 characters.",
        groups: ['create']
    )]
    public ?string $arrived = null;

    #[Assert\NotNull(message: "Arrived at is required.", groups: ['create'])]
    #[Assert\Type(DateTimeImmutable::class, message: "Arrived at must be a valid date.", groups: ['create', 'update'])]
    public ?DateTimeImmutable $arrivedAt = null;

    #[Assert\Choice(
        callback: [DriveStatusEnum::class, 'cases']
    )]
    public ?DriveStatusEnum $status = null;

    public function isEmpty(): bool
    {
        return $this->vehicleUuid    === null &&
               $this->price          === null &&
               $this->availableSeats === null &&
               $this->distance       === null &&
               $this->depart         === null &&
               $this->departAt       === null &&
               $this->arrived        === null &&
               $this->arrivedAt      === null &&
               $this->status         === null;
    }


}

?>
