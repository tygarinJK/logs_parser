<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\LogsParser;

use App\Services\LogsParser\FileGenerator\FileGeneratorInterface;
use App\Services\LogsParser\LogsParserService;
use App\Services\LogsParser\Parser\LineParser;
use App\Services\LogsParser\Parser\LineParserInterface;
use App\Services\LogsParser\Parser\Line;
use App\Services\LogsParser\Repository\LogsParserRepositoryInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Random\RandomException;

/**
 * @internal
 */
#[CoversClass(LogsParserService::class)]
final class LogsParserServiceTest extends TestCase
{
    /**
     * @return array{array<string>, array<Line>}
     *
     * @throws RandomException
     */
    private function generateLines(): array
    {
        $lineParser  = new LineParser();

        $lines = [];
        $parsed = [];

        for ($i = 0; $i < 100; ++$i) {
            $serviceName = uniqid();
            $date = new \DateTimeImmutable();
            $message = uniqid();
            $statusCode = \random_int(100, 500);

            $line = "{$serviceName} - - [{$date->format('d/M/Y:H:i:s O')}] \"{$message}\" {$statusCode}";

            $lines[] = $line;
            $parsed[] = $lineParser->parseLine($line);
        }

        return [$lines, $parsed];
    }

    public function testParseLogs(): void
    {
        [$logLines, $parsedLines] = $this->generateLines();

        $fileGenerator = $this->createMock(FileGeneratorInterface::class);
        $fileGenerator
            ->expects(self::once())
            ->method('getLines')
            ->willReturn((static fn (): iterable => yield from $logLines)())
        ;

        $logger = $this->createMock(LoggerInterface::class);

        $lineRepository = new class () implements LogsParserRepositoryInterface {
            /**
             * @var array<Line>
             */
            public array $savedLogEntries = [];

            public function save(Line ...$parsedLines): void
            {
                $this->savedLogEntries = array_merge($this->savedLogEntries, $parsedLines);
            }
        };

        $parser = new LogsParserService($logger, $lineRepository, new LineParser());

        $parser->parseLogs($fileGenerator, 2);

        $this->assertEquals($parsedLines, $lineRepository->savedLogEntries);
    }

    /**
     * @return iterable<array{int}>
     */
    public static function provideIterationSize(): iterable
    {
        yield [0];

        yield [-100];
    }

    #[DataProvider('provideIterationSize')]
    public function testIterationSize(int $iteration_size): void
    {
        $logsParserService = new LogsParserService(
            $this->createMock(LoggerInterface::class),
            $this->createMock(LogsParserRepositoryInterface::class),
            $this->createMock(LineParserInterface::class),
        );

        $this->expectException(\UnexpectedValueException::class);

        $fileGenerator = $this->createMock(FileGeneratorInterface::class);

        $logsParserService->parseLogs($fileGenerator, $iteration_size);
    }
}
