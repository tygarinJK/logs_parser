<?php

namespace App\Utils;

use DateTimeImmutable;
use Doctrine\DBAL\Connection;

class LogsService implements LogsServiceInterface
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function countLogRecords(array $parameters): int
    {
        $qb = $this->connection->createQueryBuilder()
            ->select('COUNT(*) AS counter')
            ->from('log_messages');

        $where = [];

        // Filer by: serviceNames[]
        if ($serviceNames = $parameters['serviceNames'] ?? null) {
            $placeholders = implode(',', array_map(function ($element) {
                return "'" . $element . "'";
            }, array_values($serviceNames)));
            $where[] = "service_name IN ($placeholders)";
        }

        // Filer by: statusCode
        if ($statusCode = $parameters['statusCode'] ?? null) {
            $where[] = "status_code = $statusCode";
        }

        // Filer by: flexible date (startDate and endDate)
        if ($startDate = $parameters['startDate'] ?? null) {
            try {
                $date = new DateTimeImmutable($startDate);
                $formatted = $date->format('Y-m-d H:i:s');
                $where[] = "date >= '$formatted'";
            } catch (\Exception $e) {
            }
        }

        if ($endDate = $parameters['endDate'] ?? null) {
            try {
                $date = new DateTimeImmutable($endDate);
                $formatted = $date->format('Y-m-d H:i:s');
                $where[] = "date <= '$formatted'";
            } catch (\Exception $e) {
            }
        }

        if ($where) {
            $qb->where(implode(' AND ', $where));
        }

        return (int) $qb->executeQuery()->fetchOne();
    }
}