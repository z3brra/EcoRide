<?php

namespace App\DTO\Drive;

use App\Entity\Drive;

use App\DTO\User\UserReadDTO;
use App\DTO\Vehicle\VehicleReadDTO;

use Symfony\Component\Serializer\Annotation\Groups;

use DateTimeImmutable;

class DriveReadDTO
{

    #[Groups(['drive:read', 'drive:list', 'review:author', 'review:employee'])]
    public string $uuid;

    #[Groups(['drive:read', 'drive:list', 'review:author', 'review:employee'])]
    public string $reference;

    #[Groups(['drive:read', 'drive:list'])]
    public string $status;

    #[Groups(['drive:read', 'drive:list'])]
    public UserReadDTO $owner;

    #[Groups(['drive:read', 'drive:list'])]
    public VehicleReadDTO $vehicle;

    #[Groups(['drive:read'])]
    public int $participantsCount;

    #[Groups(['drive:read', 'drive:list'])]
    public int $availableSeats;

    #[Groups(['drive:read', 'drive:list'])]
    public int $price;

    #[Groups(['drive:read'])]
    public float $distance;

    #[Groups(['drive:read', 'drive:list', 'review:author'])]
    public string $depart;

    #[Groups(['drive:read', 'drive:list', 'review:author'])]
    public DateTimeImmutable $departAt;

    #[Groups(['drive:read', 'drive:list', 'review:author'])]
    public string $arrived;

    #[Groups(['drive:read', 'drive:list'])]
    public DateTimeImmutable $arrivedAt;

    #[Groups(['drive:read'])]
    public DateTimeImmutable $createdAt;

    #[Groups(['drive:read'])]
    public ?DateTimeImmutable $updatedAt;

    public function __construct(
        string $uuid,
        string $reference,
        string $status,
        UserReadDTO $owner,
        VehicleReadDTO $vehicle,
        int $participantsCount,
        int $availableSeats,
        int $price,
        float $distance,
        string $depart,
        DateTimeImmutable $departAt,
        string $arrived,
        DateTimeImmutable $arrivedAt,
        DateTimeImmutable $createdAt,
        ?DateTimeImmutable $updatedAt = null,
    )
    {
        $this->uuid = $uuid;
        $this->reference = $reference;
        $this->status = $status;
        $this->owner = $owner;
        $this->vehicle = $vehicle;
        $this->participantsCount = $participantsCount;
        $this->availableSeats = $availableSeats;
        $this->price = $price;
        $this->distance = $distance;
        $this->depart = $depart;
        $this->departAt = $departAt;
        $this->arrived = $arrived;
        $this->arrivedAt = $arrivedAt;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function fromEntity(Drive $drive): self
    {
        return new self(
            uuid: $drive->getUuid(),
            reference: $drive->getReference(),
            status: $drive->getStatus(),
            owner: UserReadDTO::fromEntity($drive->getOwner()),
            vehicle: VehicleReadDTO::fromEntity($drive->getVehicle()),
            participantsCount: $drive->getParticipants()->count(),
            availableSeats: $drive->getAvailableSeats(),
            price: $drive->getPrice(),
            distance: $drive->getDistance(),
            depart: $drive->getDepart(),
            departAt: $drive->getDepartAt(),
            arrived: $drive->getArrived(),
            arrivedAt: $drive->getArrivedAt(),
            createdAt: $drive->getCreatedAt(),
            updatedAt: $drive->getUpdatedAt(),
        );
    }
}

?>