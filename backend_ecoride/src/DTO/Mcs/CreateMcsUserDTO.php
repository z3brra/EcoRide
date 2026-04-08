<?php

namespace App\DTO\Mcs;

class CreateMcsUserDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $plainPassword,
        public readonly bool $active = true,
    ) {}
}

?>