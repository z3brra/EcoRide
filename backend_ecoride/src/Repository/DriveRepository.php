<?php

namespace App\Repository;

use App\Entity\Drive;
use App\Enum\DriveStatusEnum;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Drive>
 */
class DriveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Drive::class);
    }

    public function findOneByUuid(string $uuid): ?Drive
    {
        return $this->findOneBy(['uuid' => $uuid]);
    }

    public function findOneByReference(string $reference): ?Drive
    {
        return $this->findOneBy(['reference' => $reference]);
    }

    public function findPaginated(
        string $depart,
        string $arrived,
        DateTimeImmutable $departAt,
        ?bool $isElectric = null,
        ?int $maxPrice = null,
        ?int $maxDuration = null,
        ?bool $animals = null,
        ?bool $smoke = null,
        int $page = 1,
        int $limit = 10,
        string $sortBy = 'price',
        string $sortDir = 'asc'
    ): array {
        $departNormalized = mb_strtolower(trim($depart));
        $arrivedNormalized = mb_strtolower(trim($arrived));

        $dayStart = $departAt->setTime(0, 0, 0);
        $dayEnd = $departAt->setTime(23, 59, 59);

        $minDepartAt = $departAt->setTimezone(new DateTimeZone('UTC'));

        $queryBuilder = $this->createQueryBuilder('drive')
            ->innerJoin('drive.vehicle', 'vehicle')
            ->innerJoin('drive.owner', 'owner')
            ->leftJoin('owner.fixedDriverPreference', 'fixedPref')
            ->andWhere('drive.status = :open')
            ->andWhere('drive.availableSeats > 0')
            ->andWhere('LOWER(drive.depart) = :depart')
            ->andWhere('LOWER(drive.arrived) = :arrived')
            // ->andWhere('drive.departAt BETWEEN :start AND :end')
            ->andWhere('drive.departAt >= :minDepartAt')
            ->setParameter('open', DriveStatusEnum::OPEN)
            ->setParameter('depart', $departNormalized)
            ->setParameter('arrived', $arrivedNormalized)
            // ->setParameter('start', $dayStart)
            // ->setParameter('end', $dayEnd);
            ->setParameter('minDepartAt', $minDepartAt);

        if ($isElectric !== null) {
            $queryBuilder->andWhere('vehicle.isElectric = :isElectric')
                  ->setParameter('isElectric', $isElectric);
        }

        if ($maxPrice !== null) {
            $queryBuilder->andWhere('drive.price <= :maxPrice')
                  ->setParameter('maxPrice', $maxPrice);
        }

        if ($animals !== null) {
            $queryBuilder->andWhere('fixedPref.animals = :animals')
                  ->setParameter('animals', $animals);
        }

        if ($smoke !== null) {
            $queryBuilder->andWhere('fixedPref.smoke = :smoke')
                  ->setParameter('smoke', $smoke);
        }

        if ($maxDuration !== null) {
            $queryBuilder->andWhere("drive.arrivedAt <= DATE_ADD(drive.departAt, :maxDuration, 'minute')")
                  ->setParameter('maxDuration', $maxDuration);
        }

        $allowedSort = ['price', 'departAt'];
        $sortBy = in_array($sortBy, $allowedSort, true) ? $sortBy : 'price';
        $sortDir = strtolower($sortDir) === 'desc' ? 'DESC' : 'ASC';

        $queryBuilder->addOrderBy('drive.' . $sortBy, $sortDir);

        $query = $queryBuilder->setFirstResult(($page - 1) * $limit)
                              ->setMaxResults($limit)
                              ->getQuery();

        // $paginator = new Paginator($query, fetchJoinCollection: true);
        $paginator = new Paginator($query);
        $total = count($paginator);
        $totalPages = (int) ceil($total / $limit);

        return [
            'data' => iterator_to_array($paginator->getIterator()),
            'total' => $total,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'perPage' => $limit,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir
        ];
    }

    //    /**
    //     * @return Drive[] Returns an array of Drive objects
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

    //    public function findOneBySomeField($value): ?Drive
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
