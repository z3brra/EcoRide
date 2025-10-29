<?php

namespace App\Repository;

use App\Document\Credit;
use App\Enum\CreditStatusEnum;
use DateTimeImmutable;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

use Doctrine\ODM\MongoDB\Aggregation\Builder;
use InvalidArgumentException;

/** #######################################################################
 * AVIS AUX LECTEURS / EVALUATEURS : Ceci est en grande partie fait avec ChatGPT. Mea Culpa
 * Après 1 semaine d'essai seul avec la documentation, j'ai finis par abandonner l'idée de faire seul.
 */ #######################################################################

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

    public function aggregatePlatformFeesByGranularity(
        DateTimeImmutable $from,
        DateTimeImmutable $to,
        string $granularity,
        string $timezone = 'Europe/Paris'
    ): array {
        $aggregateBuilder = $this->createAggregationBuilder(Credit::class);

        $aggregateBuilder->match()
            ->field('status')->equals(CreditStatusEnum::CONFIRMED->value)
            ->field('occurredAt')->range($from, $to);

        $this->addSegmentField($aggregateBuilder, $granularity, $timezone);

        $aggregateBuilder->group()
            ->field('_id')->expression('$segment')
            ->field('sum')->sum(
                $aggregateBuilder->expr()->ifNull('$fee', 0)
            );
        
        $aggregateBuilder->sort('_id', 'asc');

        $iterator = $aggregateBuilder->getAggregation()->getIterator();
        $rows = iterator_to_array($iterator, false);

        $out = [];
        foreach ($rows as $row) {
            $out[] = [
                'timestamp' => (string) ($row['_id'] ?? ''),
                'sum' => (int) ($row['sum'] ?? 0),
            ];
        }
        return $out;
    }

    private function addSegmentField(
        Builder $aggregateBuilder,
        string $granularity,
        string $timezone
    ): void {
        $add = $aggregateBuilder->addFields();
        $expr = $aggregateBuilder->expr();

        switch ($granularity) {
            case 'half_hour':
                $hourStr = $aggregateBuilder->expr()->dateToString('%Y-%m-%dT%H', '$occurredAt', $timezone);
                $minute  = $aggregateBuilder->expr()->minute('$occurredAt', $timezone);
                $lt30    = $aggregateBuilder->expr()->lt($minute, 30);
                $suffix  = $aggregateBuilder->expr()->cond($lt30, ':00:00', ':30:00');
                $segment = $aggregateBuilder->expr()->concat($hourStr, $suffix);
                $add->field('segment')->expression($segment);
                break;

            case 'hour':
                $segment = $aggregateBuilder->expr()->dateToString('%Y-%m-%dT%H:00:00', '$occurredAt', $timezone);
                $add->field('segment')->expression($segment);
                break;

            case 'day':
                $segment = $aggregateBuilder->expr()->dateToString('%Y-%m-%dT00:00:00', '$occurredAt', $timezone);
                $add->field('segment')->expression($segment);
                break;

            case 'half_day':
                $dateStr   = $aggregateBuilder->expr()->dateToString('%Y-%m-%dT', '$occurredAt', $timezone);
                $hour    = $aggregateBuilder->expr()->hour('$occurredAt', $timezone);
                $lt12    = $aggregateBuilder->expr()->lt($hour, 12);
                $suffix  = $aggregateBuilder->expr()->cond($lt12, '00:00:00', '12:00:00');
                $segment = $aggregateBuilder->expr()->concat($dateStr, $suffix);
                $add->field('segment')->expression($segment);
                break;

            case 'month':
                $segment = $aggregateBuilder->expr()->dateToString('%Y-%m-01T00:00:00', '$occurredAt', $timezone);
                $add->field('segment')->expression($segment);
                break;

            default:
                throw new InvalidArgumentException('Unsupported granularity');
        }
    }

    public function sumPlatformFees(DateTimeImmutable $from, DateTimeImmutable $to): int
    {
        $aggregateBuilder = $this->createAggregationBuilder(Credit::class);

        $aggregateBuilder->match()
            ->field('status')->equals(CreditStatusEnum::CONFIRMED->value)
            ->field('occurredAt')->gte($from)->lt($to);
        
        $aggregateBuilder->group()
            ->field('_id')->expression(null)
            ->field('total')->sum(
                $aggregateBuilder->expr()->ifNull('$fee', 0)
            );
        
        $iterator = $aggregateBuilder->getAggregation()->getIterator();
        $result = iterator_to_array($iterator, false);

        return isset($result[0]['total']) ? (int) $result[0]['total'] : 0;
    }
}

?>
