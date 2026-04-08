<?php

namespace App\DTO\Mcs;

class McsAliasReadDTO
{
    public function __construct(
        public readonly string $uuid,
        public readonly string $sourceEmail,
        public readonly string $destinationEmail,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            uuid: $data['uuid'],
            sourceEmail: $data['sourceEmail'],
            destinationEmail: $data['destinationEmail']
        );
    }
}

?>