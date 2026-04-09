<?php

namespace App\DTO\Mail;

class MailSendResultDTO
{
    public function __construct(
        public readonly string $status,
        public readonly ?string $messageId = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            status: $data['status'],
            messageId: $data['messageId'] ?? null,
        );
    }
}

?>