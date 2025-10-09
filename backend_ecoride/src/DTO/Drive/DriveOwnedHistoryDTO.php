<?php

namespace App\DTO\Drive;

use App\Enum\DriveStatusEnum;
use Symfony\Component\Validator\Constraints as Assert;

class DriveOwnedHistoryDTO
{
    #[Assert\Choice(
        callback: [DriveStatusEnum::class, 'getValues'],
        message: "You have to choose correct status (open, in_progress, finished, cancelled)",
        groups: ['history']
    )]
    public ?string $status = null;

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

    public static function fromQuery(array $query): self
    {
        $driveOwnedDTO = new self();

        $driveOwnedDTO->status = self::strOrNull($query['status'] ?? null);
        $driveOwnedDTO->depart = self::strOrNull($query['depart'] ?? null);
        $driveOwnedDTO->arrived = self::strOrNull($query['arrived'] ?? null);

        if (array_key_exists('includeCancelled', $query)) {
            $driveOwnedDTO->includeCancelled = filter_var(
                $query['includeCancelled'],
                FILTER_VALIDATE_BOOLEAN,
                FILTER_NULL_ON_FAILURE
            );
        }

        $sort = strtolower((string) ($query['sortDir'] ?? 'asc'));
        $driveOwnedDTO->sortDir = in_array($sort, ['asc', 'desc'], true) ? $sort : 'asc';

        return $driveOwnedDTO;
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