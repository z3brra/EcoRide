<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class DriveTransitionException extends HttpException
{
    public function __construct(array $errors, int $status = 409)
    {
        parent::__construct($status, json_encode(['errors' => $errors]));
    }
}

?>