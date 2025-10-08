<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class PendingSettlementException extends HttpException
{
    public function __construct(string $message = 'Pending settlement: confirm or open a dispute on the last drive')
    {
        parent::__construct(423, $message);
    }
}

?>
