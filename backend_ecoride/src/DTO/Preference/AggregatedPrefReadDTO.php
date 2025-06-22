<?php

namespace App\DTO\Preference;

use App\Entity\User;
use App\DTO\Preference\CustomDriverPreferenceReadDTO;

use Symfony\Component\Serializer\Annotation\Groups;

class AggregatedPrefReadDTO
{
    #[Groups(['pref:read', 'prefs:list'])]
    public bool $animals;

    #[Groups(['pref:read', 'pref:list'])]
    public bool $smoke;

    #[Groups(['pref:read', 'pref:list'])]
    public ?array $customPreferences = [];

    public function __construct(
        bool $animals,
        bool $smoke,
        ?array $customPreferences = []
    )
    {
        $this->animals = $animals;
        $this->smoke = $smoke;
        $this->customPreferences = $customPreferences;
    }

    public static function fromEntity(User $user): self
    {
        $fixedPref = $user->getFixedDriverPreference();

        $customPrefDTOs = [];
        foreach ($user->getCustomDriverPreferences() as $pref) {
            $customPrefDTOs[] = CustomDriverPreferenceReadDTO::fromEntity($pref);
        }

        return new self(
            animals: $fixedPref->isAnimals() ?? false,
            smoke: $fixedPref->isSmoke() ?? false,
            customPreferences: $customPrefDTOs
        );
    }

}

?>