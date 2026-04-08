<?php

namespace App\Repository;

use App\Entity\MailAccount;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MailAccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MailAccount::class);
    }

    public function findOneByUuid(string $uuid): ?MailAccount
    {
        return $this->findOneBy(['uuid' => $uuid]);
    }

    public function findOneByMcsUuid(string $mcsUuid): ?MailAccount
    {
        return $this->findOneBy(['mcsUuid' => $mcsUuid]);
    }

    public function findOneByEmail(string $email): ?MailAccount
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function findOneByUser(User $user): ?MailAccount
    {
        return $this->findOneBy(['user' => $user]);
    }
}

?>