<?php

namespace App\DTO\Mail;

class MailHeaderDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $value,
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
        ];
    }
}

?>