<?php

namespace App\Service\Mcs;

use App\DTO\Mcs\{
    CreateMcsUserDTO,
    McsUserReadDTO
};

class McsUserService
{
    public function __construct(
        private McsClient $mcsClient,
        private string $mcsDomainUuid,
    ) {}

    public function create(CreateMcsUserDTO $createMcsUserDTO): McsUserReadDTO
    {
        $payload = [
            'domainUuid' => $this->mcsDomainUuid,
            'email' => $createMcsUserDTO->email,
            'plainPassword' => $createMcsUserDTO->plainPassword,
            'active' => $createMcsUserDTO->active,
        ];

        $response = $this->mcsClient->post('/token/users', $payload);

        return McsUserReadDTO::fromArray($response['data']);
    }

    public function enable(string $mcsUserUuid): void
    {
        $this->mcsClient->patch('/token/users' . $mcsUserUuid . '/status', [
            'action' => 'enable',
        ]);
    }

    public function disable(string $mcsUserUuid): void
    {
        $this->mcsClient->patch('/token/users' . $mcsUserUuid . '/status', [
            'action' => 'enable',
        ]);
    }

    public function changePassword(string $mcsUserUuid, string $oldPassword, string $newPassword): void
    {
        $this->mcsClient->patch('/token/users' . $mcsUserUuid . '/password', [
            'oldPassword' => $oldPassword,
            'newPassword' => $newPassword,
        ]);
    }
}

?>