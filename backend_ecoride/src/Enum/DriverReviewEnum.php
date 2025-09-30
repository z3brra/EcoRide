<?php

namespace App\Enum;

enum DriverReviewEnum: string
{
    case PENDING = 'pending';
    case VALIDATED = 'validated';
    case REFUSED = 'refused';
}

?>