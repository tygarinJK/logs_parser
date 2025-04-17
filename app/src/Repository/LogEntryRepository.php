<?php

namespace App\Repository;

use App\Dto\LogsQueryData;
use App\Entity\LogEntry;
use App\Services\LogsParser\Repository\LogEntryRepositoryInterface;
use App\Services\LogsParser\ValueObject\ParsedLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LogEntry>
 */
class LogEntryRepository extends ServiceEntityRepository implements LogEntryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LogEntry::class);
    }

    public function getCount(LogsQueryData $data): int
    {
        $qb = $this->createQueryBuilder('log_entry')
            ->select('COUNT(log_entry.id)');

        // Filer by: serviceNames[]
        if ($serviceNames = $data->getServiceNames()) {
            $qb->andWhere('log_entry.serviceName IN (:serviceNames)')
                ->setParameter('serviceNames', $serviceNames);
        }

        // Filer by: statusCode
        if ($statusCode = $data->getStatusCode()) {
            $qb->andWhere('log_entry.statusCode = :statusCode')
                ->setParameter('statusCode', $statusCode);
        }

        // Filer by: flexible date (startDate and endDate)
        if ($startDate = $data->getStartDate()) {
            $qb->andWhere('log_entry.date >= :startDate')
                ->setParameter('startDate', $startDate);
        }
        if ($endDate = $data->getEndDate()) {
            $qb->andWhere('log_entry.date <= :endDate')
                ->setParameter('endDate', $endDate);
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function save(ParsedLine ...$parsedLines): void
    {
        $em = $this->getEntityManager();

        foreach ($parsedLines as $parsedLine) {
            $logEntry = new LogEntry();
            $logEntry->setServiceName($parsedLine->getServiceName());
            $logEntry->setDate($parsedLine->getDateTime());
            $logEntry->setMessage($parsedLine->getMessage());
            $logEntry->setStatusCode($parsedLine->getStatusCode());

            $em->persist($logEntry);
        }

        $em->flush();
    }
}
