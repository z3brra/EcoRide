<?php

namespace App\DTO\Preference;

use App\Entity\CustomDriverPreference;
use Symfony\Component\Serializer\Annotation\Groups;

use DateTimeImmutable;

class CustomDriverPreferenceReadDTO
{
    #[Groups(['pref:read', 'pref:list'])]
    public string $uuid;

    #[Groups(['pref:read', 'pref:list'])]
    public string $label;

    #[Groups(['pref:read', 'pref:list'])]
    public DateTimeImmutable $createdAt;

    #[Groups(['pref:read', 'pref:list'])]
    public ?DateTimeImmutable $updatedAt;

    public function __construct(
        string $uuid,
        string $label,
        DateTimeImmutable $createdAt,
        ?DateTimeImmutable $updatedAt = null,
    ) {
        $this->uuid = $uuid;
        $this->label = $label;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function fromEntity(CustomDriverPreference $customPref): self
    {
        return new self(
            uuid: $customPref->getUuid(),
            label: $customPref->getLabel(),
            createdAt: $customPref->getCreatedAt(),
            updatedAt: $customPref->getUpdatedAt(),
        );
    }


}


?>