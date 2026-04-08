<?php

namespace App\Service\User;
use App\Entity\{
    User,
    MailAccount
};

use App\Repository\MailAccountRepository;

use App\Service\Mcs\McsUserService;

use App\Exception\McsException;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class DeleteUserProfileService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MailAccountRepository $mailAccountRepository,
        private McsUserService $mcsUserService,
    ) {}

    public function deleteUser(User $user): void
    {
        $mailAccount = $this->mailAccountRepository->findOneByUser($user);

        $this->entityManager->beginTransaction();

        try {
            if ($mailAccount instanceof MailAccount) {
                $this->mcsUserService->disable($mailAccount->getMcsUuid());
                $mailAccount->setActive(false);
            }

            $this->entityManager->remove($user);
            $this->entityManager->flush();

            $this->entityManager->commit();
        } catch (McsException $e) {
            $this->entityManager->rollback();

            throw new ConflictHttpException(
                sprintf('MCS mailbox disable failed: %s', $e->getMessage())
            );
        } catch (\Throwable $e) {
            $this->entityManager->rollback();
            throw $e;
        }

    }
}



?>