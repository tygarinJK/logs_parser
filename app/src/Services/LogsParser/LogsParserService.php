<?php

declare(strict_types=1);

namespace App\Services\LogsParser;

use App\Services\LogsParser\FileGenerator\FileGeneratorInterface;
use App\Services\LogsParser\Parser\LineParserException;
use App\Services\LogsParser\Parser\LineParserInterface;
use App\Services\LogsParser\Repository\LogEntryRepositoryInterface;
use Psr\Log\LoggerInterface;

readonly class LogsParserService implements LogsParserServiceInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private LogEntryRepositoryInterface $logEntryRepository,
        private LineParserInterface $lineParser,
    ) {}

    public function parseLogs(FileGeneratorInterface $generator, int $iteration_size): void
    {
        $chunk = [];

        if ($iteration_size < 1) {
            throw new \UnexpectedValueException('Iteration size must be a positive integer.');
        }

        foreach ($generator->getLines() as $key => $line) {
            try {
                $chunk[] = $this->lineParser->parseLine($line);

                if (count($chunk) >= $iteration_size) {
                    $this->logEntryRepository->save(...$chunk);

                    $chunk = [];
                }
            } catch (LineParserException $e) {
                $this->logger->error("Error parsing line {$key}: ".$e->getMessage());

                continue;
            }
        }

        if (!empty($chunk)) {
            $this->logEntryRepository->save(...$chunk);
        }
    }
}
