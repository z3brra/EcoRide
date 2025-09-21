<?php

namespace App\Enum;

enum CreditActionEnum: string
{
    case CONFIRM = 'confirm';
    case REFUND = 'refund';
}

?>