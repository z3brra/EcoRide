<?php

namespace App\Enum;

enum DriveWhenChoices: string
{
    case UPCOMING = 'upcoming';
    case PAST = 'past';
    case ALL = 'all';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}

?>