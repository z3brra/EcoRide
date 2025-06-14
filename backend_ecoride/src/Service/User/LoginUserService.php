<?php

namespace App\Service\User;

use App\DTO\User\{UserLoginDTO, UserReadDTO};
use App\Repository\UserRepository;
use App\Service\ValidationService;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\RateLimiter\RateLimiterFactory;

use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class LoginUserService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private RateLimiterFactory $loginLimiter,
        private ValidationService $validationService
    ) {}

    public function handleLogin(UserLoginDTO $userLoginDTO): UserReadDTO
    {
        $this->validationService->validate($userLoginDTO, ['login']);

        $user = $this->userRepository->findOneByEmail($userLoginDTO->username);
        if (!$user || $this->passwordHasher->isPasswordValid($user, $userLoginDTO->password)) {
            throw new BadCredentialsException("Invalid credentials.");
        }

        return UserReadDTO::fromEntity($user);
    }
}

?>