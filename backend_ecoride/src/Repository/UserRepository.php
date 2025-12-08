<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findOneByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function findOneByUuid(string $uuid): ?User
    {
        return $this->findOneBy(['uuid' => $uuid]);
    }

    public function isExistingUser(string $email): bool
    {
        return $this->findOneByEmail($email) !== null;
    }

    // public function findEmployeePaginated(int $page = 1, int $limit = 10): array {
    //     // $query = $this->createQueryBuilder('user')
    //     //     // ->andWhere('user.roles = :employee')
    //     //     ->andWhere(':role MEMBER OF user.roles')
    //     //     // ->setParameter('employee', 'ROLE_EMPLOYEE')
    //     //     ->setParameter('role', 'ROLE_EMPLOYEE')
    //     //     ->orderBy('user.createdAt', 'DESC')
    //     //     ->setFirstResult(($page - 1) * $limit)
    //     //     ->setMaxResults($limit)
    //     //     ->getQuery();

    //     // $paginator = new Paginator($query);

    //     $builder = $this->createQueryBuilder('user');
    //         // ->andWhere(':role MEMBER OF user.roles')
    //     $builder->andWhere(
    //         $builder->expr()->orX(
    //             'user.roles LIKE :middle',
    //             'user.roles LIKE :start',
    //             'user.roles LIKE :end',
    //             'user.roles = :exact'
    //         )
    //     )
    //         ->setParameter('middle', '%,ROLE_EMPLOYEE,%')
    //         ->setParameter('start', 'ROLE_EMPLOYEE,%')
    //         ->setParameter('end', '%,ROLE_EMPLOYEE')
    //         ->setParameter('exact', 'ROLE_EMPLOYEE')

    //         // ->setParameter('role', 'ROLE_EMPLOYEE')
    //         ->orderBy('user.createdAt', 'DESC')
    //         ->setFirstResult(($page - 1) * $limit)
    //         ->setMaxResults($limit);

    //     $query = $builder->getQuery();

    //     $paginator = new Paginator($query);
    //     $total = count($paginator);
    //     $totalPages = (int) ceil($total / $limit);

    //     return [
    //         'data' => iterator_to_array($paginator->getIterator()),
    //         'total' => $total,
    //         'totalPages' => $totalPages,
    //         'currentPage' => $page,
    //         'perPage' => $limit
    //     ];
    // }


    public function findEmployeePaginated(int $page = 1, int $limit = 10): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $total = (int) $conn->executeQuery(
            'SELECT COUNT(*) 
            FROM user 
            WHERE JSON_CONTAINS(roles, :needle) = 1',
            ['needle' => json_encode('ROLE_EMPLOYEE')]
        )->fetchOne();

        $offset = max(0, ($page - 1) * $limit);
        $ids = $conn->executeQuery(
            'SELECT id
            FROM user
            WHERE JSON_CONTAINS(roles, :needle) = 1
            ORDER BY created_at DESC
            LIMIT :offset, :limit',
            [
                'needle' => json_encode('ROLE_EMPLOYEE'),
                'offset' => $offset,
                'limit'  => $limit,
            ],
            [
                'needle' => \PDO::PARAM_STR,
                'offset' => \PDO::PARAM_INT,
                'limit'  => \PDO::PARAM_INT,
            ]
        )->fetchFirstColumn();

        $users = [];
        if ($ids) {
            $users = $this->createQueryBuilder('user')
                ->where('user.id IN (:ids)')
                ->setParameter('ids', $ids)
                ->orderBy('user.createdAt', 'DESC')
                ->getQuery()
                ->getResult();
        }

        return [
            'data'        => $users,
            'total'       => $total,
            'totalPages'  => (int) ceil($total / $limit),
            'currentPage' => $page,
            'perPage'     => $limit,
        ];
    }



    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
