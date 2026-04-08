<?php

namespace App\Exception;

use Exception;

class McsException extends Exception
{
    public function __construct(
        string $message,
        private readonly ?string $codeValue = null,
        private readonly ?array $details = null,
        private readonly ?string $requestId = null,
        private readonly int $statusCode = 500
    ) {
        parent::__construct($message, $statusCode);
    }

    public function getCodeValue(): ?string
    {
        return $this->codeValue;
    }

    public function getDetails(): ?array
    {
        return $this->details;
    }

    public function getRequestId(): ?string
    {
        return $this->requestId;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}

?>