<?php

namespace App\Service\Admin;

use App\Entity\User;
use App\Repository\UserRepository;

use App\Service\{ValidationService, Utils, StringHelper};

use App\DTO\User\{UserDTO, UserReadDTO};

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

use DateTimeImmutable;

class CreateUserService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private ValidationService $validationService,

        private StringHelper $stringHelper,
    ) {}

    public function createUser(UserDTO $userCreateDTO): UserReadDTO
    {
        $this->validationService->validate($userCreateDTO, ['create']);

        $email = $this->stringHelper->generateEmail($userCreateDTO->pseudo);

        if ($this->userRepository->isExistingUser($email)) {
            throw new ConflictHttpException("User already exist");
        }

        $plainPassword = Utils::randomPassword();

        $user = new User();
        $user->setPseudo($userCreateDTO->pseudo)
             ->setEmail($email)
             ->setRoles(['ROLE_EMPLOYEE'])
             ->setIsBanned(false)
            //  ->setCreatedAt(new DateTimeImmutable())
             ->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return UserReadDTO::fromEntity($user, $plainPassword);
    }
}

?>
