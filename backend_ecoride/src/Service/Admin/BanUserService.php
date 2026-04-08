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

use App\Service\Mcs\McsUserService;
use App\Exception\McsException;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpKernel\Exception\{
    NotFoundHttpException,
    ConflictHttpException
};

use DateTimeImmutable;

class BanUserService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private MailAccountRepository $mailAccountRepository,
        private McsUserService $mcsUserService
    ) {}

    public function banUser(string $uuid): void
    {
        $user = $this->userRepository->findOneByUuid($uuid);
        if (!$user) {
            throw new NotFoundHttpException("User not found or does not exist");
        }

        $mailAccount = $this->mailAccountRepository->findOneByUser($user);

        $this->entityManager->beginTransaction();

        try {
            $user->setIsBanned(true);

            if ($mailAccount instanceof MailAccount) {
                $this->mcsUserService->disable($mailAccount->getMcsUuid());
                $mailAccount->setActive(false);
            }

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (McsException $e) {
            $this->entityManager->rollback();

            throw new ConflictHttpException(
                sprintf('MCS mailbox disable failed : %s', $e->getMessage())
            );
        } catch (\Throwable $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }

    public function unbanUser(string $uuid): void
    {
        $user = $this->userRepository->findOneByUuid($uuid);
        if (!$user) {
            throw new NotFoundHttpException("User not found or does not exist");
        }

        $mailAccount = $this->mailAccountRepository->findOneByUser($user);

        $this->entityManager->beginTransaction();

        try {
            $user->setIsBanned(false);

            if ($mailAccount instanceof MailAccount) {
                $this->mcsUserService->enable($mailAccount->getMcsUuid());
                $mailAccount->setActive(true);
            }

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (McsException $e) {
            $this->entityManager->rollback();

            throw new ConflictHttpException(
                sprintf('MCS mailbox enable failed : %s', $e->getMessage())
            );
        } catch (\Throwable $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }
}


?>
