<?php

namespace App\DTO\Preference;

use Symfony\Component\Validator\Constraints as Assert;

class CustomDriverPreferenceDTO
{
    #[Assert\NotBlank(message: "Label is required", groups: ['create'])]
    #[Assert\Length(
        min: 2,
        minMessage: "Label must have at least 2 caracters.",
        max: 100,
        maxMessage: "Label may not exceed 100 caracters.",
        groups: ['create', 'update']
    )]
    public ?string $label = null;

    public function isEmpty(): bool
    {
        return $this->label === null;
    }
}

?>