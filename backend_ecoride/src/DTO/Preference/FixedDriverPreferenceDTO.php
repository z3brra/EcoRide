<?php

namespace App\DTO\Preference;

use Symfony\Component\Validator\Constraints as Assert;

class FixedDriverPreferenceDTO
{
    #[Assert\NotNull(message: "animals must be accepted or no",  groups: ['update'])]
    public ?bool $animals = null;

    #[Assert\NotNull(message: "smoke must be accepted or no", groups: ['update'])]
    public ?bool $smoke = null;

    public function isEmpty(): bool
    {
        return $this->animals === null &&
               $this->smoke   === null;
    }
}


?>