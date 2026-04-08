<?php

namespace App\Service\Admin;

use App\Entity\{
    User,
    MailAccount
};
use App\Repository\{
    UserRepository,
    MailAccountRepository
};

use App\Enum\MailAccountTypeEnum;

use App\DTO\User\{UserDTO, UserReadDTO};
use App\DTO\Mcs\CreateMcsUserDTO;

use App\Service\{ValidationService, Utils, StringHelper};
use App\Service\Mcs\McsUserService;

use App\Exception\McsException;

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
        private McsUserService $mcsUserService
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

        $this->entityManager->beginTransaction();

        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $mcsUser = $this->mcsUserService->create(
                new CreateMcsUserDTO(
                    email: $email,
                    plainPassword: $plainPassword,
                    active: true,
                )
            );

            $mailAccount = new MailAccount();
            $mailAccount->setUser($user)
                ->setMcsUuid($mcsUser->uuid)
                ->setDomainUuid($mcsUser->domainUuid)
                ->setEmail($mcsUser->email)
                ->setType(MailAccountTypeEnum::MAILBOX)
                ->setActive($mcsUser->active);
            
            $this->entityManager->persist($mailAccount);
            $this->entityManager->flush();

            $this->entityManager->commit();
        } catch (McsException $e) {
            $this->entityManager->rollback();

            throw new ConflictHttpException(
                sprintf('MCS mailbox creation failed : %s', $e->getMessage())
            );
        } catch (\Throwable $e) {
            $this->entityManager->rollback();
            throw $e;
        }

        return UserReadDTO::fromEntity($user, $plainPassword);
    }
}

?>
