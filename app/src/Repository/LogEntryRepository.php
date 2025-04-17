<?php

namespace App\Repository;

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

    //    /**
    //     * @return LogEntry[] Returns an array of LogEntry objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?LogEntry
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

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
