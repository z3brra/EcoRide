<?php

namespace App\Repository;

use App\Document\Credit;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

/**
 * @extends ServiceDocumentRepository<Credit>
 */
class CreditRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Credit::class);
    }

    public function findOneByDriveAndParticipant(string $driveUuid, string $participantUuid): ?Credit
    {
        return $this->findOneBy([
            'driveUuid' => $driveUuid,
            'participantUuid' => $participantUuid
        ]);
    }

    public function existsForDriveAndParticipant(string $driveUuid, string $participantUuid): bool
    {
        return null !== $this->findOneByDriveAndParticipant($driveUuid, $participantUuid);
    }
}

?>
