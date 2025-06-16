<?php

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\DTO\User\{UserRegisterDTO, UserReadDTO};
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use DateTimeImmutable;

class RegisterUserService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private ValidationService $validationService
    ) {}

    public function handleRegister(UserRegisterDTO $userRegisterDTO): UserReadDTO
    {
        $this->validationService->validate($userRegisterDTO, ['create']);

        if ($this->userRepository->isExistingUser($userRegisterDTO->email)) {
            throw new ConflictHttpException("User already exist");
        }

        $user = new User();
        $user->setPseudo($userRegisterDTO->pseudo)
             ->setEmail($userRegisterDTO->email)
             ->setIsBanned(false)
             ->setCredits(20)
             ->setCreatedAt(new DateTimeImmutable())
             ->setPassword($this->passwordHasher->hashPassword($user, $userRegisterDTO->password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return UserReadDTO::fromEntity($user);
    }
}

?>
