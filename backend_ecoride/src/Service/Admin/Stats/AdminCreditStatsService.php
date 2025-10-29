<?php

namespace App\Service\Admin\Stats;

use App\Repository\CreditRepository;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;

class AdminCreditStatsService
{
    private const APP_TIMEZONE = 'Europe/Paris';

    public function __construct(
        private CreditRepository $creditRepository,
    ) {}

    public function getStats(string $range, ?int $year = null): array
    {
        [$fromLocal, $toLocal, $seriesSpec] = $this->computeSegmentandSeries($range, $year);

        $fromUTC = $fromLocal->setTimezone(new DateTimeZone('UTC'));
        $toUTC = $toLocal->setTimezone(new DateTimeZone('UTC'));

        $series = [];
        foreach ($seriesSpec as $spec) {
            $points = $this->creditRepository->aggregatePlatformFeesByGranularity(
                from: $fromUTC,
                to: $toUTC,
                granularity: $spec['granularity'],
                timezone: self::APP_TIMEZONE,
            );

            $series[] = [
                'key' => $spec['key'],
                'granularity' => $spec['granularity'],
                'points' => $points,
            ];
        }

        $total = $this->creditRepository->sumPlatformFees($fromUTC, $toUTC);

        return [
            'range' => $range,
            'from' => $fromLocal->format(DateTimeInterface::ATOM),
            'to' => $toLocal->format(DateTimeInterface::ATOM),
            'timezone' => self::APP_TIMEZONE,
            'series' => $series,
            'total' => $total,
        ];
    }

    public function computeSegmentandSeries(string $range, ?int $year): array
    {
        $timezone = new DateTimeZone(self::APP_TIMEZONE);
        $now = new DateTimeImmutable('now', $timezone);

        switch ($range) {
            case 'today':
                $from = $now->setTime(0, 0, 0);
                $to = $now->setTime(23, 59, 59);
                $series = [
                    ['key' => 'half_hour', 'granularity' => 'half_hour'],
                ];
                break;

            case 'last_7_days':
                $from = $now->modify('-6 days')->setTime(0, 0, 0);
                $to = $now->setTime(23, 59, 59);
                $series = [
                    ['key' => 'hour', 'granularity' => 'hour'],
                    ['key' => 'day', 'granularity' => 'day'],
                ];
                break;

            case 'this_month':
                $from = $now->modify('first day of this month')->setTime(0, 0, 0);
                $to = $now->modify('last day of this month')->setTime(23, 59, 59);
                $series = [
                    ['key' => 'half_day', 'granularity' => 'half_day'],
                    ['key' => 'day', 'granularity' => 'day'],
                ];
                break;

            case 'last_3_month':
                $from = $now->modify('first day of this month')->setTime(0, 0, 0);
                $to = $now->modify('last day of this month')->setTime(23, 59, 59);
                $series = [
                    ['key' => 'half_day', 'granularity' => 'half_day'],
                    ['key' => 'day', 'granularity' => 'day'],
                ];
                break;

            case 'this_year':
                $yearNum = (int) $now->format('Y');
                $from = $now->setDate($yearNum, 1, 1)->setTime(0, 0, 0);
                $to = $now->setDate($yearNum, 12, 31)->setTime(23, 59, 59);
                $series = [
                    ['key' => 'month', 'granularity' => 'month'],
                ];
                break;

            case 'year':
                if ($year === null) {
                    throw new InvalidArgumentException('Parameter "year" is required when range=year');
                }
                $from = new DateTimeImmutable(sprintf('%d-01-01 00:00:00', $year), $timezone);
                $to = new DateTimeImmutable(sprintf('%d-12-31 23:59:59', $year), $timezone);
                $series = [
                    ['key' => 'month', 'granularity' => 'month'],
                ];
                break;

            default:
                throw new InvalidArgumentException('Unsupported range');
        }

        return [$from, $to, $series];
    }
}


?>