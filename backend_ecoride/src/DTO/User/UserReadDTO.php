<?php

namespace App\DTO\User;

use App\Entity\User;

use DateTimeImmutable;
use Symfony\Component\Serializer\Annotation\Groups;

class UserReadDTO
{
    #[Groups([
        'user:read',
        'user:login',
        'user:list',
        'drive:read',
        'drive:list',
        'review:public',
        'review:author',
        'review:driver',
        'review:employee',
        'public:read'
    ])]
    public string $uuid;

    #[Groups([
        'user:read',
        'user:list',
        'drive:read',
        'drive:list',
        'review:public',
        'review:author',
        'review:driver',
        'review:employee',
        'public:read'
    ])]
    public string $pseudo;

    #[Groups(['user:read', 'user:login', 'user:list', 'review:employee'])]
    public string $email;

    #[Groups(['user:read', 'user:login', 'user:list'])]
    public array $roles;

    #[Groups(['user:read', 'user:list'])]
    public ?int $credits;

    #[Groups(['user:read', 'user:list'])]
    public bool $isBanned;

    #[Groups(['user:read', 'user:list'])]
    public DateTimeImmutable $createdAt;

    #[Groups(['user:read', 'user:list'])]
    public ?DateTimeImmutable $updatedAt;

    // #[Groups(['user:internal'])]
    public string $apiToken;

    #[Groups(['user:create'])]
    public ?string $plainPassword;

    public function __construct(
        string $uuid,
        string $pseudo,
        string $email,
        array $roles,
        bool $isBanned,
        DateTimeImmutable $createdAt,
        string $apiToken,
        ?string $plainPassword,
        ?int $credits = null,
        ?DateTimeImmutable $updatedAt = null,
    )
    {
        $this->uuid = $uuid;
        $this->pseudo = $pseudo;
        $this->email = $email;
        $this->roles = $roles;
        $this->isBanned = $isBanned;
        $this->createdAt = $createdAt;
        $this->apiToken = $apiToken;
        $this->plainPassword = $plainPassword;
        $this->credits = $credits;
        $this->updatedAt = $updatedAt;
    }

    public static function fromEntity(User $user, ?string $plainPassword = null): self
    {
        $userDTO = new self(
            uuid: $user->getUuid(),
            pseudo: $user->getPseudo(),
            email: $user->getEmail(),
            roles: $user->getRoles(),
            isBanned: $user->isBanned(),
            createdAt: $user->getCreatedAt(),
            apiToken: $user->getApiToken(),
            plainPassword: $plainPassword,
            credits: $user->getCredits(),
            updatedAt: $user->getUpdatedAt(),
        );

        return $userDTO;
    }
}

?>