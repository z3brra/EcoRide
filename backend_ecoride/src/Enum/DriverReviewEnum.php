<?php

namespace App\Enum;

enum DriverReviewEnum: string
{
    case PENDING = 'pending';
    case VALIDATED = 'validated';
    case REFUSED = 'refused';

    public static function isValid(string $status): bool
    {
        return in_array($status, array_column(self::cases(), 'value'), true);
    }
}

?>