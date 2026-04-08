<?php

namespace App\Entity;

use App\Enum\MailAccountTypeEnum;
use App\Repository\MailAccountRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

#[ORM\Entity(repositoryClass: MailAccountRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'uniq_mail_account_uuid', fields: ['uuid'])]
#[ORM\UniqueConstraint(name: 'uniq_mail_account_mcs_uuid', fields: ['mcsUuid'])]
#[ORM\UniqueConstraint(name: 'uniq_mail_account_email', fields: ['email'])]
class MailAccount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 36, unique: true)]
    private ?string $uuid = null;

    #[ORM\ManyToOne(inversedBy: 'mailAccounts')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $user = null;

    #[ORM\Column(length: 36, unique: true)]
    private ?string $mcsUuid = null;

    #[ORM\Column(length: 36)]
    private ?string $domainUuid = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 20)]
    private string $type = MailAccountTypeEnum::MAILBOX->value;

    #[ORM\Column]
    private bool $active = true;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->uuid = Uuid::uuid7()->toString();
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getMcsUuid(): ?string
    {
        return $this->mcsUuid;
    }

    public function setMcsUuid(string $mcsUuid): static
    {
        $this->mcsUuid = $mcsUuid;
        return $this;
    }

    public function getDomainUuid(): ?string
    {
        return $this->domainUuid;
    }

    public function setDomainUuid(string $domainUuid): static
    {
        $this->domainUuid = $domainUuid;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getTypeEnum(): MailAccountTypeEnum
    {
        return MailAccountTypeEnum::from($this->type);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(MailAccountTypeEnum|string $type): static
    {
        $this->type = $type instanceof MailAccountTypeEnum ? $type->value : $type;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;
        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }
}


?>