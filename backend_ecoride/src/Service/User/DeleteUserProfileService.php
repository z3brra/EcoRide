<?php

namespace App\Service\User;
use App\Entity\User;

use Doctrine\ORM\EntityManagerInterface;

class DeleteUserProfileService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function deleteUser(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}



?>