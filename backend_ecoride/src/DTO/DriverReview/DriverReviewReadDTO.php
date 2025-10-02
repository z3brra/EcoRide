<?php

namespace App\DTO\DriverReview;

use App\Entity\DriverReview;

use App\DTO\Drive\DriveReadDTO;
use App\DTO\User\UserReadDTO;

use Symfony\Component\Serializer\Annotation\Groups;

use DateTimeImmutable;

class DriverReviewReadDTO
{
    #[Groups(['review:public', 'review:driver', 'review:author', 'review:employee'])]
    public string $uuid;

    #[Groups(['review:author', 'review:employee'])]
    public UserReadDTO $driver;

    #[Groups(['review:public', 'review:driver', 'review:employee'])]
    public UserReadDTO $author;

    #[Groups(['review:author', 'review:employee'])]
    public DriveReadDTO $drive;

    #[Groups(['review:public', 'review:driver', 'review:author', 'review:employee'])]
    public int $rate;

    #[Groups(['review:public', 'review:driver', 'review:author', 'review:employee'])]
    public ?string $comment;

    #[Groups(['review:driver', 'review:author', 'review:employee'])]
    public string $status;

    #[Groups(['review:public', 'review:driver', 'review:author', 'review:employee'])]
    public DateTimeImmutable $createdAt;

    public function __construct(
        string $uuid,
        UserReadDTO $driver,
        UserReadDTO $author,
        DriveReadDTO $drive,
        int $rate,
        ?string $comment = null,
        string $status,
        DateTimeImmutable $createdAt,
    )
    {
        $this->uuid = $uuid;
        $this->driver = $driver;
        $this->author = $author;
        $this->drive = $drive;
        $this->rate = $rate;
        $this->comment = $comment;
        $this->status = $status;
        $this->createdAt = $createdAt;
    }

    public static function fromEntity(DriverReview $driverReview): self
    {
        $driverReviewDTO = new self(
            uuid: $driverReview->getUuid(),
            driver: UserReadDTO::fromEntity($driverReview->getDriver()),
            author: UserReadDTO::fromEntity($driverReview->getAuthor()),
            drive: DriveReadDTO::fromEntity($driverReview->getDrive()),
            rate: $driverReview->getRate(),
            comment: $driverReview->getComment(),
            status: $driverReview->getStatus(),
            createdAt: $driverReview->getCreatedAt()
        );

        return $driverReviewDTO;
    }
}


?>