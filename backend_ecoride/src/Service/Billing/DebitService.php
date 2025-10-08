<?php

namespace App\Service\Billing;

use App\Entity\User;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;

class DebitService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function debit(User $user, int $amount): void
    {
        if ($amount <= 0) {
            return;
        }

        $this->entityManager->lock($user, LockMode::PESSIMISTIC_WRITE);

        $currentCredits = $user->getCredits();
        $user->setCredits($currentCredits - $amount);
    }
}

?>
