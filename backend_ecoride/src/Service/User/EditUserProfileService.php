<?php

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\DTO\User\{UserEditDTO, UserReadDTO};

use App\Service\ValidationService;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use LogicException;
use Symfony\Component\HttpKernel\Exception\{AccessDeniedHttpException, BadRequestHttpException};
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class EditUserProfileService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private ValidationService $validationService
    ) {}

    public function editUser(UserInterface $user, UserEditDTO $userEditDTO): UserReadDTO
    {
        if (!$user instanceof User) {
            throw new LogicException("Invalid user type");
        }

        if ($user->isBanned()) {
            throw new AccessDeniedHttpException("This account is banned");
        }

        if ($userEditDTO->isEmpty()) {
            throw new BadRequestHttpException("No data to update");
        }

        $this->validationService->validate($userEditDTO, ['update']);

        $pseudo = $userEditDTO->pseudo;

        $oldPassword = $userEditDTO->oldPassword;
        $newPassword = $userEditDTO->newPassword;

        if ($pseudo !== null) {
            $user->setPseudo($pseudo);
        }

        if ($newPassword !== null) {
            if ($oldPassword === null) {
                throw new BadRequestHttpException("Old password is required");
            }
            if (!$this->passwordHasher->isPasswordValid($user, $oldPassword)) {
                throw new BadCredentialsException("Invalid credentials");
            }

            /*
            Turn the use of this function to my need (old password !== new password)
            To be improved if a better method exist.
            */
            if ($this->passwordHasher->isPasswordValid($user, $newPassword)) {
                throw new BadRequestHttpException("The new password cannot be the same as old");
            }

            $user->setPassword($this->passwordHasher->hashPassword($user, $newPassword));
        }

        $this->entityManager->flush();

        return UserReadDTO::fromEntity($user);
    }
}



?>
