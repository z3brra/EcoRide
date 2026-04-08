<?php

namespace App\Service\User;

use App\Entity\{
    User,
    MailAccount
};
use App\DTO\User\{UserEditDTO, UserReadDTO};

use App\Repository\MailAccountRepository;

use App\Service\ValidationService;
use App\Service\Mcs\McsUserService;

use App\Exception\McsException;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Symfony\Component\HttpKernel\Exception\{BadRequestHttpException, ConflictHttpException};
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class EditUserProfileService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private ValidationService $validationService,
        private MailAccountRepository $mailAccountRepository,
        private McsUserService $mcsUserService,
    ) {}

    public function editUser(User $user, UserEditDTO $userEditDTO): UserReadDTO
    {
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

        $this->entityManager->beginTransaction();

        try {
            if ($newPassword !== null) {
                if ($oldPassword === null) {
                    throw new BadRequestHttpException('Old password is required.');
                }

                if (!$this->passwordHasher->isPasswordValid($user, $oldPassword)) {
                    throw new BadCredentialsException('Invalid credentials.');
                }

                if ($this->passwordHasher->isPasswordValid($user, $newPassword)) {
                    throw new BadRequestHttpException('The new password cannot be the same as old');
                }

                $mailAccount = $this->mailAccountRepository->findOneByUser($user);

                if ($mailAccount instanceof MailAccount) {
                    $this->mcsUserService->changePassword(
                        $mailAccount->getMcsUuid(),
                        $oldPassword,
                        $newPassword
                    );
                }

                $user->setPassword($this->passwordHasher->hashPassword($user, $newPassword));
            }

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (McsException $e) {
            $this->entityManager->rollback();

            throw new ConflictHttpException(
                sprintf('MCS password change failed: %s', $e->getMessage())
            );
        } catch (\Throwable $e) {
            $this->entityManager->rollback();
            throw $e;
        }

        return UserReadDTO::fromEntity($user);
    }
}


?>
