<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class PendingSettlementException extends HttpException
{
    private string $driveUuid;

    public function __construct(string $driveUuid)
    {
        $this->driveUuid = $driveUuid;

        parent::__construct(
            423,
            "Pending settlement: confirm or open a dispute on the last drive"
        );
    }

    public function getDriveUuid(): string
    {
        return $this->driveUuid;
    }
}

?>
