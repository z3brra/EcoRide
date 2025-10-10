<?php

namespace App\Enum;

enum DriveStatusEnum: string
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case CANCELLED = 'cancelled';
    case FINISHED = 'finished';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}

?>