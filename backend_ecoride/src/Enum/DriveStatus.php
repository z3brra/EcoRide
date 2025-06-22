<?php

namespace App\Enum;

enum DriveStatus: string
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case CANCELLED = 'cancelled';
    case COMPLETED = 'completed';
}

?>