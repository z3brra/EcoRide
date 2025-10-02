<?php

namespace App\Repository;

use App\Entity\{
    DriverReview,
    Drive,
    User
};

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DriverReview>
 */
class DriverReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DriverReview::class);
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
