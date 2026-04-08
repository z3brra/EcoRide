<?php

namespace App\DTO\Mcs;

class McsUserReadDTO
{
    /**
     * @param McsAliasReadDTO[] $aliases
     */
    public function __construct(
        public readonly string $uuid,
        public readonly string $email,
        public readonly string $domainUuid,
        public readonly bool $active,
        public readonly array $aliases = [],
    ) {}

    public static function fromArray(array $data): self
    {
        $aliases = [];

        foreach ($data['aliases'] ?? [] as $alias) {
            $aliases[] = McsAliasReadDTO::fromArray($alias);
        }

        return new self(
            uuid: $data['uuid'],
            email: $data['email'],
            domainUuid: $data['domainUuid'],
            active: $data['active'],
            aliases: $aliases
        );
    }
}


?>