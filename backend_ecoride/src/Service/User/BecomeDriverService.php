<?php

namespace App\Service\User;

use App\Entity\User;

use App\DTO\User\UserReadDTO;

use App\Service\Access\AccessControlService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class BecomeDriverService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AccessControlService $accessControl,
    ) {}

    public function becomeDriver(User $user): UserReadDTO
    {
        $user->setRoles(['ROLE_DRIVER'])
             ->setUpdatedAt(new DateTimeImmutable());

        $this->entityManager->flush();

        return UserReadDTO::fromEntity($user);
    }
}

?>