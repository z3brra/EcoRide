<?php

namespace App\DTO\Preference;

use Symfony\Component\Validator\Constraints as Assert;

class AggregatedPrefDTO
{
    #[Assert\Valid(groups: ['create', 'update'])]
    public ?FixedDriverPreferenceDTO $fixedPref = null;

    /** @var CustomDriverPreferenceDTO[]|null */
    #[Assert\Valid(groups: ['create', 'update'])]
    public ?array $customPref = [];

    public function isEmpty(): bool
    {
        return $this->fixedPref  === null &&
               (empty($this->customPref) || $this->customPref === null);
    }
}


?>
