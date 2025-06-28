<?php

namespace App\EventListener;

use App\Entity\Drive;
use App\Repository\DriveRepository;

use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Ramsey\Uuid\Uuid;

class DriveReferenceListener
{
    public function __construct(
        private DriveRepository $driveRepository
    ) {}

    // public function prePersist(PrePersistEventArgs $args): void
    // {
    //     $drive = $args->getObject();
    //     if (!$drive instanceof Drive || $drive->getReference()) {
    //         return;
    //     }

    //     $now = new DateTimeImmutable('now', new DateTimeZone('UTC'));
    //     $datePart = $now->format('Ymd');
    //     $hourPart = $now->format('H');

    //     $dailyPrefix = sprintf('DRV-%s-', $datePart);

    //     $queryBuilder = $this->driveRepository->createQueryBuilder('drive');
    //     $dailyDriveCount = (int) $queryBuilder
    //         ->select('COUNT(drive.id)')
    //         ->where($queryBuilder->expr()->like('drive.reference', ':like'))
    //         ->setParameter('like', $dailyPrefix . '%')
    //         ->getQuery()
    //         ->getSingleScalarResult();

    //     $drive->setReference(sprintf('DRV-%s-%s-%04d', $datePart, $hourPart, $dailyDriveCount + 1));
    // }
    public function prePersist(PrePersistEventArgs $args): void
    {
        $drive = $args->getObject();
        if (!$drive instanceof Drive || $drive->getReference()) {
            return;
        }

        $now = new DateTimeImmutable('now', new DateTimeZone('UTC'));
        $datePart = $now->format('Ymd');
        $hourPart = $now->format('H');

        $uuid = Uuid::uuid7()->toString();
        $randomPart = substr($uuid, -4);

        $drive->setReference(sprintf('DRV-%s-%s-%s', $datePart, $hourPart, strtoupper($randomPart)));
    }
}

?>