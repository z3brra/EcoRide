<?php

namespace App\Service\User;

use App\Entity\User;

use App\DTO\User\UserReadDTO;
use App\Entity\FixedDriverPreference;
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
        $roles = $user->getRoles();
        if (!in_array('ROLE_DRIVER', $roles, true)) {
            $roles[] = 'ROLE_DRIVER';
        }
        $roles = array_values(array_unique($roles));
        $user->setRoles($roles);
            //  ->setUpdatedAt(new DateTimeImmutable());

        if ($user->getFixedDriverPreference() === null) {
            $fixedPref = new FixedDriverPreference();
            $fixedPref->setOwner($user)
                      ->setCreatedAt(new DateTimeImmutable());

            $this->entityManager->persist($fixedPref);
        }

        $this->entityManager->flush();

        return UserReadDTO::fromEntity($user);
    }
}

?>