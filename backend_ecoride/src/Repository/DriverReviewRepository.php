<?php

namespace App\Repository;

use App\Entity\{
    DriverReview,
    Drive,
    User
};
use App\Enum\DriverReviewEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use function Symfony\Component\DependencyInjection\Loader\Configurator\iterator;

/**
 * @extends ServiceEntityRepository<DriverReview>
 */
class DriverReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DriverReview::class);
    }

    public function findOneByUuid(string $uuid): ?DriverReview
    {
        return $this->findOneBy([
            'uuid' => $uuid
        ]);
    }

    public function findOneByDriveAndAuthor(Drive $drive, User $author): ?DriverReview
    {
        $query = $this->createQueryBuilder('review')
            ->andWhere('review.drive = :drive')
            ->andWhere('review.author = :author')
            ->setParameter('drive', $drive)
            ->setParameter('author', $author)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return $query;
    }

    public function findValidatedForPublicPaginated(User $driver, string $sortDir, int $page = 1, int $limit = 10): array
    {
        $sortDir = strtolower($sortDir) === 'desc' ? 'DESC' : 'ASC';

        $query = $this->createQueryBuilder('review')
            ->andWhere('review.driver = :driver')
            ->andWhere('review.status = :validated')
            ->setParameter('driver', $driver)
            ->setParameter('validated', DriverReviewEnum::VALIDATED)
            ->orderBy('review.createdAt', $sortDir)
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        $paginator = new Paginator($query);
        $total = count($paginator);
        $totalPages = (int) ceil($total / $limit);

        return [
            'data' => iterator_to_array($paginator->getIterator()),
            'total' => $total,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'perPage' => $limit,
        ];
    }

    public function findForUserPaginated(User $user, string $role, ?string $status, string $sortDir, int $page, int $limit): array
    {
        $sortDir = strtolower($sortDir) === 'desc' ? 'DESC' : 'ASC';

        $queryBuilder = $this->createQueryBuilder('review');

        switch ($role) {
            case 'author':
                $queryBuilder->andWhere('review.author = :user')
                    ->setParameter('user', $user);
                break;
            case 'driver':
                $queryBuilder->andWhere('review.driver = :user')
                    ->setParameter('user', $user);
                break;
            default:
                throw new BadRequestHttpException("Role is incorrect");
                break;
        }

        if ($status !== null) {
            $queryBuilder->andWhere('review.status = :status')
                ->setParameter('status', $status);
        }

        $query = $queryBuilder->addOrderBy('review.createdAt', $sortDir)
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        $paginator = new Paginator($query);
        $total = count($paginator);
        $totalPages = (int) ceil($total / $limit);

        return [
            'data' => iterator_to_array($paginator->getIterator()),
            'total' => $total,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'perPage' => $limit,
        ];
    }

    public function findForEmployeePaginated(string $sortDir, int $page, int $limit): array
    {
        $sortDir = strtolower($sortDir) === 'desc' ? 'DESC' : 'ASC';

        $query = $this->createQueryBuilder('review')
            ->andWhere('review.status = :pending')
            ->setParameter('pending', DriverReviewEnum::PENDING)
            ->orderBy('review.createdAt', $sortDir)
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        $paginator = new Paginator($query);
        $total = count($paginator);
        $totalPages = (int) ceil($total / $limit);

        return [
            'data' => iterator_to_array($paginator->getIterator()),
            'total' => $total,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'perPage' => $limit
        ];
    }

    public function getValidatedStatsForDriver(User $driver): array
    {
        $query = $this->createQueryBuilder('review')
            ->select('AVG(review.rate) as averageRate, COUNT(review.id) as reviewCount')
            ->andWhere('review.driver = :driver')
            ->andWhere('review.status = :validated')
            ->setParameter('driver', $driver)
            ->setParameter('validated', DriverReviewEnum::VALIDATED);

        $result = $query->getQuery()->getSingleResult();

        return [
            'average' => $result['averageRate'] !== null ? (float) $result['averageRate'] : null,
            'count' => (int) $result['reviewCount'],
        ];
    }

    public function existsForDriveAndAuthor(Drive $drive, User $author): bool
    {
        return null !== $this->findOneByDriveAndAuthor($drive, $author);
    }




    //    /**
    //     * @return DriverReview[] Returns an array of DriverReview objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?DriverReview
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
