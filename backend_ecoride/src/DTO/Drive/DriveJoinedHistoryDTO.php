<?php

namespace App\DTO\Drive;

use App\Enum\{DriveStatusEnum, DriveWhenChoices};
use Symfony\Component\Validator\Constraints as Assert;



class DriveJoinedHistoryDTO
{
    #[Assert\Choice(
        callback: [DriveStatusEnum::class, 'getValues'],
        message: "You have to choose correct status (open, in_progress, finished, cancelled)",
        groups: ['history']
    )]
    public ?string $status = null;

    #[Assert\Choice(
        callback: [DriveWhenChoices::class, 'getValues'],
        message: "You have to choose correct when (upcoming, past, all)",
        groups: ['history']
    )]
    public ?string $when = null;

    #[Assert\Length(
        min: 2,
        minMessage: "Depart must have at least 2 chars.",
        max: 255,
        maxMessage: "Depart may not exceed 255 characters.",
        groups: ['history']
    )]
    public ?string $depart = null;

    #[Assert\Length(
        min: 2,
        minMessage: "Arrived must have at least 2 chars.",
        max: 255,
        maxMessage: "Arrived may not exceed 255 characters.",
        groups: ['history']
    )]
    public ?string $arrived = null;

    public ?bool $includeCancelled = null;

    #[Assert\Choice(choices: ['asc', 'desc'], message: "sortDir invalid.", groups: ['history'])]
    public string $sortDir = 'asc';

    public static function fromQuery(array $querry): self
    {
        $driveJoinedDTO = new self();

        $driveJoinedDTO->status = self::strOrNull($querry['status'] ?? null);
        $driveJoinedDTO->when = self::strOrNull($querry['when'] ?? null);
        $driveJoinedDTO->depart = self::strOrNull($querry['depart'] ?? null);
        $driveJoinedDTO->arrived = self::strOrNull($querry['arrived'] ?? null);

        if (array_key_exists('includeCancelled', $querry)) {
            $driveJoinedDTO->includeCancelled = filter_var(
                $querry['includeCancelled'],
                FILTER_VALIDATE_BOOLEAN,
                FILTER_NULL_ON_FAILURE
            );
        }

        $sort = strtolower((string) ($querry['sortDir'] ?? 'asc'));
        $driveJoinedDTO->sortDir = in_array($sort, ['asc', 'desc'], true) ? $sort : 'asc';

        return $driveJoinedDTO;
    }

    private static function strOrNull(mixed $value): ?string
    {
        if (!is_string($value) && !is_numeric($value)) {
            return null;
        }
        $string = trim((string) $value);
        return $string === '' ? null : $string;
    }
}

?>
