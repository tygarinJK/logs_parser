<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\LogsParser\Parser;

use App\Services\LogsParser\Parser\LineParser;
use App\Services\LogsParser\Parser\LineParserException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(LineParser::class)]
final class LineParserTest extends TestCase
{
    private LineParser $parser;

    private const LOG_LINE_VALID = 'SOME-VERY-USEFUL-SERVICE - - [18/Apr/2025:09:21:53 +0000] "POST /users HTTP/1.1" 418';
    private const LOG_LINE_INVALID = 'This line will definitely not be parsed';

    protected function setUp(): void
    {
        $this->parser = new LineParser();
    }

    public function testParseLine(): void
    {
        $parsed = $this->parser->parseLine(self::LOG_LINE_VALID);

        $this->assertSame('SOME-VERY-USEFUL-SERVICE', $parsed->getServiceName());
        $this->assertEquals(new \DateTimeImmutable('18/Apr/2025:09:21:53 +0000'), $parsed->getDateTime());
        $this->assertSame('POST /users HTTP/1.1', $parsed->getMessage());
        $this->assertSame(418, $parsed->getStatusCode());
    }

    public function testParseInvalidLine(): void
    {
        $this->expectException(LineParserException::class);

        $this->parser->parseLine(self::LOG_LINE_INVALID);
    }
}
