<?php

namespace App\Service\Admin;

use App\Entity\User;
use App\Repository\UserRepository;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use DateTimeImmutable;

class BanUserService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository
    ) {}

    public function banUser(string $uuid): void
    {
        $user = $this->userRepository->findOneByUuid($uuid);
        if (!$user) {
            throw new NotFoundHttpException("User not found or does not exist");
        }

        $user->setIsBanned(true);
        // $user->setUpdatedAt(new DateTimeImmutable());

        $this->entityManager->flush();
    }

    public function unbanUser(string $uuid): void
    {
        $user = $this->userRepository->findOneByUuid($uuid);
        if (!$user) {
            throw new NotFoundHttpException("User not found or does not exist");
        }
        $user->setIsBanned(false);
        // $user->setUpdatedAt(new DateTimeImmutable());

        $this->entityManager->flush();
    }
}


?>
