<?php

namespace App\DTO\DriverReview;

use Symfony\Component\Validator\Constraints as Assert;

class DriverReviewDTO
{
    #[Assert\NotBlank(message: "Drive uuid is required.", groups: ['create'])]
    public ?string $driveUuid = null;

    #[Assert\NotBlank(message: "Rate is required", groups: ['create'])]
    #[Assert\Range(
        min: 1,
        max: 5,
        notInRangeMessage: "The rate must be between 1 and 5"
    )]
    public ?int $rate = null;

    #[Assert\Length(
        min: 10,
        minMessage: "The comment must contain at least 10 characters.",
        groups: ['create']
    )]
    public ?string $comment = null;

    public function isEmpty(): bool
    {
        return $this->driveUuid === null &&
               $this->rate      === null &&
               $this->comment   === null;
    }
}


?>
