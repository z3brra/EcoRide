<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

use App\Enum\CreditStatusEnum;
use DateTimeImmutable;

#[ODM\Document(collection: "credits")]
#[ODM\Index(keys: ["driveUuid" => 1, "participantUuid" => 1], options: ["unique" => true])]
#[ODM\Index(keys: ["driveUuid" => 1, "occurredAt" => -1])]
#[ODM\Index(keys: ["ownerUuid" => 1, "occurredAt" => -1])]
#[ODM\Index(keys: ["participantUuid" => 1, "occurredAt" => -1])]

class Credit
{
    #[ODM\Id(strategy: "AUTO")]
    private ?string $id = null;

    #[ODM\Field(type: "string")]
    private string $driveUuid;

    #[ODM\Field(type: "string")]
    private string $ownerUuid;

    #[ODM\Field(type: "string")]
    private string $participantUuid;

    #[ODM\Field(type: "int")]
    private int $amount;

    #[ODM\Field(type: "int")]
    private int $fee = 0;

    #[ODM\Field(type: "date_immutable")]
    private DateTimeImmutable $occurredAt;

    #[ODM\Field(type: "string", nullable: true)]
    private ?string $comment = null;

    #[ODM\Field(type: "string", enumType: CreditStatusEnum::class)]
    private CreditStatusEnum $status;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getDriveUuid(): ?string
    {
        return $this->driveUuid;
    }

    public function setDriveUuid(string $driveUuid): static
    {
        $this->driveUuid = $driveUuid;
        return $this;
    }

    public function getOwnerUuid(): ?string
    {
        return $this->ownerUuid;
    }

    public function setOwnerUuid(string $ownerUuid): static
    {
        $this->ownerUuid = $ownerUuid;
        return $this;
    }

    public function getParticipantUuid(): ?string
    {
        return $this->participantUuid;
    }

    public function setParticipantUuid(string $participantUuid): static
    {
        $this->participantUuid = $participantUuid;
        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;
        return $this;
    }

    public function getFee(): ?int
    {
        return $this->fee;
    }

    public function setFee(int $fee): static
    {
        $this->fee = $fee;
        return $this;
    }

    public function getOccurredAt(): DateTimeImmutable
    {
        return $this->occurredAt;
    }

    public function setOccurredAt(DateTimeImmutable $occurredAt): static
    {
        $this->occurredAt = $occurredAt;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status->value;
    }

    public function setStatus(CreditStatusEnum $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;
        return $this;
    }

}
?>