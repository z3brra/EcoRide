<?php

namespace App\DTO\Mail;

class MailAddressDTO
{
    public function __construct(
        public readonly string $email,
        public readonly ?string $name = null,
    ) {}

    public function toArray(): array
    {
        $data = [
            'email' => $this->email,
        ];

        if ($this->name !== null && $this->name !== '') {
            $data['name'] = $this->name;
        }

        return $data;
    }
}

?>