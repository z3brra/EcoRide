<?php

namespace App\DTO\Vehicle;

use App\Entity\Vehicle;

use Symfony\Component\Serializer\Annotation\Groups;

use DateTimeImmutable;

class VehicleReadDTO
{
    #[Groups(['vehicle:read', 'vehicle:list'])]
    public string $uuid;

    #[Groups(['vehicle:read', 'vehicle:list'])]
    public string $licensePlate;

    #[Groups(['vehicle:read', 'vehicle:list'])]
    public DateTimeImmutable $firstLicenseDate;

    #[Groups(['vehicle:read', 'vehicle:list'])]
    public bool $isElectric;

    #[Groups(['vehicle:read'])]
    public string $color;

    #[Groups(['vehicle:read', 'vehicle:list'])]
    public int $seats;

    #[Groups(['vehicle:read'])]
    public DateTimeImmutable $createdAt;

    #[Groups(['vehicle:read'])]
    public ?DateTimeImmutable $updatedAt;

    #[Groups(['vehicle:read', 'vehicle:list'])]
    public string $ownerUuid;

    #[Groups(['vehicle:read', 'vehicle:list'])]
    public string $ownerPseudo;

    public function __construct(
        string $uuid,
        string $licensePlate,
        DateTimeImmutable $firstLicenseDate,
        bool $isElectric,
        string $color,
        int $seats,
        DateTimeImmutable $createdAt,
        ?DateTimeImmutable $updatedAt = null,
        string $ownerUuid,
        string $ownerPseudo,
    )
    {
        $this->uuid = $uuid;
        $this->licensePlate = $licensePlate;
        $this->firstLicenseDate = $firstLicenseDate;
        $this->isElectric = $isElectric;
        $this->color = $color;
        $this->seats = $seats;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->ownerUuid = $ownerUuid;
        $this->ownerPseudo = $ownerPseudo;
    }

    public static function fromEntity(Vehicle $vehicle): self
    {
        return new self(
            uuid: $vehicle->getUuid(),
            licensePlate: $vehicle->getLicensePlate(),
            firstLicenseDate: $vehicle->getFirstLicenseDate(),
            isElectric: $vehicle->isElectric(),
            color: $vehicle->getColor(),
            seats: $vehicle->getSeats(),
            createdAt: $vehicle->getCreatedAt(),
            updatedAt: $vehicle->getUpdatedAt(),
            ownerUuid: $vehicle->getOwner()->getUuid(),
            ownerPseudo: $vehicle->getOwner()->getPseudo(),
        );
    }
}


?>
