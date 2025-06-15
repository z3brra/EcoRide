<?php

namespace App\DTO\User;

use App\Entity\User;

use DateTimeImmutable;
use Symfony\Component\Serializer\Annotation\Groups;

class UserReadDTO
{
    #[Groups(['user:read', 'user:login', 'user:list'])]
    public string $uuid;

    #[Groups(['user:read', 'user:list'])]
    public string $pseudo;

    #[Groups(['user:read', 'user:login', 'user:list'])]
    public string $email;

    #[Groups(['user:read', 'user:login', 'user:list'])]
    public array $roles;

    #[Groups(['user:read', 'user:list'])]
    public ?int $credits;

    #[Groups(['user:read', 'user:list'])]
    public bool $isBanned;

    #[Groups(['user:read'])]
    public DateTimeImmutable $createdAt;

    #[Groups(['user:read'])]
    public ?DateTimeImmutable $updatedAt;

    #[Groups(['user:read'])]
    public string $apiToken;

    public function __construct(
        string $uuid,
        string $pseudo,
        string $email,
        array $roles,
        ?int $credits = null,
        bool $isBanned,
        DateTimeImmutable $createdAt,
        ?DateTimeImmutable $updatedAt = null,
        string $apiToken,
    )
    {
        $this->uuid = $uuid;
        $this->pseudo = $pseudo;
        $this->email = $email;
        $this->roles = $roles;
        $this->credits = $credits;
        $this->isBanned = $isBanned;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->apiToken = $apiToken;
    }

    public static function fromEntity(User $user): self
    {
        $userDTO = new self(
            uuid: $user->getUuid(),
            pseudo: $user->getPseudo(),
            email: $user->getEmail(),
            roles: $user->getRoles(),
            credits: $user->getCredits(),
            isBanned: $user->isBanned(),
            createdAt: $user->getCreatedAt(),
            updatedAt: $user->getUpdatedAt(),
            apiToken: $user->getApiToken()
        );

        return $userDTO;
    }
}

?>