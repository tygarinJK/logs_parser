<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\LogsParser;

use App\Services\LogsParser\FileGenerator\FileGeneratorInterface;
use App\Services\LogsParser\LogsParserService;
use App\Services\LogsParser\LogsParserServiceInterface;
use App\Services\LogsParser\Parser\LineParserInterface;
use App\Services\LogsParser\Repository\LogEntryRepositoryInterface;
use App\Services\LogsParser\ValueObject\ParsedLine;
use ArrayIterator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @internal
 */
#[CoversClass(LogsParserService::class)]
final class LogsParserServiceTest extends TestCase implements LogEntryRepositoryInterface
{
    private LogsParserServiceInterface $logsParserService;

    private array $savedLogEntries = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->logsParserService = new LogsParserService(
            $this->createMock(LoggerInterface::class),
            $this->createMock(LogEntryRepositoryInterface::class),
            $this->createMock(LineParserInterface::class),
        );
    }

    public function testParseLogs(): void
    {
        $logLines = [
            'USER-SERVICE - - [17/Aug/2018:09:21:53 +0000] "POST /users HTTP/1.1" 201',
            'INVOICE-SERVICE - - [17/Aug/2018:09:21:55 +0000] "POST /invoices HTTP/1.1" 201'
        ];

        $fileGenerator = $this->createMock(FileGeneratorInterface::class);
        $fileGenerator
            ->expects(self::once())
            ->method('getLines')
            ->willReturn(new ArrayIterator($logLines))
        ;

        $logger = $this->createMock(LoggerInterface::class);

        $parsedLines = [];

        $lineParser = $this->createMock(LineParserInterface::class);
        $lineParser
            ->expects(self::exactly(count($logLines)))
            ->method('parseLine')
            ->willReturnCallback(
                function (string $line) use (&$parsedLines) {
                    $parsedLine = new ParsedLine(
                        uniqid(),
                        new \DateTimeImmutable('17/Aug/2018:09:21:53 +0000'),
                        '',
                        201
                    );

                    $parsedLines[] = $parsedLine;

                    return $parsedLine;
                }
            )
        ;

        $parser = new LogsParserService($logger, $this, $lineParser);

        $parser->parseLogs($fileGenerator, 2);

        $this->assertEquals($parsedLines, $this->savedLogEntries);
    }

    public static function provideIterationSize(): iterable
    {
        yield ['path/to/logs1.log', 100];

        yield ['path/to/logs1.log', 0];

        yield ['path/to/logs1.log', -100];
    }

    #[DataProvider('provideIterationSize')]
    public function testIterationSize(string $path, int $iteration_size): void
    {
        $this->assertIsInt($iteration_size);

        if ($iteration_size <= 0) {
            $this->expectException(\UnexpectedValueException::class);
        }

        $fileGenerator = $this->createMock(FileGeneratorInterface::class);

        $this->logsParserService->parseLogs($fileGenerator, $iteration_size);
    }

    public function save(ParsedLine ...$parsedLines): void
    {
        $this->savedLogEntries = array_merge($this->savedLogEntries, $parsedLines);
    }
}
