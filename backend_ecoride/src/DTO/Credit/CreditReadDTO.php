<?php

namespace App\DTO\Credit;

use App\Document\Credit;
use App\Entity\{Drive, User};

use App\DTO\Drive\DriveReadDTO;
use App\DTO\User\UserReadDTO;

use Symfony\Component\Serializer\Annotation\Groups;

use DateTimeImmutable;

class CreditReadDTO
{
    #[Groups(['credit:read', 'credit:list'])]
    public DriveReadDTO $drive;

    #[Groups(['credit:read', 'credit:list'])]
    public UserReadDTO $participant;

    #[Groups(['credit:read', 'credit:list'])]
    public int $amount;

    #[Groups(['credit:read', 'credit:list'])]
    public int $fee;

    #[Groups(['credit:read', 'credit:list'])]
    public DateTimeImmutable $occurredAt;

    #[Groups(['credit:read'])]
    public ?string $comment;

    #[Groups(['credit:read', 'credit:list'])]
    public $status;

    public function __construct(
        DriveReadDTO $drive,
        UserReadDTO $participant,
        int $amount,
        int $fee,
        DateTimeImmutable $occurredAt,
        ?string $comment = null,
        string $status
    ) {
        $this->drive = $drive;
        $this->participant = $participant;
        $this->amount = $amount;
        $this->fee = $fee;
        $this->occurredAt = $occurredAt;
        $this->comment = $comment;
        $this->status = $status;
    }

    public static function fromDocument(Credit $credit, Drive $drive, User $participant): self
    {
        return new self(
            drive: DriveReadDTO::fromEntity($drive),
            participant: UserReadDTO::fromEntity($participant),
            amount: $credit->getAmount(),
            fee: $credit->getFee(),
            occurredAt: $credit->getOccurredAt(),
            comment: $credit->getComment(),
            status: $credit->getStatus(),
        );
    }
}

?>