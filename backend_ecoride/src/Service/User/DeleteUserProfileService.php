<?php

namespace App\Service\User;
use App\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use LogicException;
use Symfony\Component\HttpKernel\Exception\{AccessDeniedHttpException};

class DeleteUserProfileService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function deleteUser(UserInterface $user): void
    {
        if (!$user instanceof User) {
            throw new LogicException("Invalid user type");
        }

        if ($user->isBanned()) {
            throw new AccessDeniedHttpException("This account is banned");
        }

        $this->entityManager->remove($user);
    }
}



?>