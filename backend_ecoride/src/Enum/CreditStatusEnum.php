<?php

namespace App\Enum;

enum CreditStatusEnum: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case REFUNDED = 'refunded';
}

?>