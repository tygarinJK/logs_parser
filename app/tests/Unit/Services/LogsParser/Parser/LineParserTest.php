<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\LogsParser\Parser;

use App\Services\LogsParser\Parser\LineParser;
use App\Services\LogsParser\Parser\LineParserException;
use App\Services\LogsParser\Parser\Line;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(LineParser::class)]
final class LineParserTest extends TestCase
{
    private LineParser $parser;

    private const LOG_LINE = 'USER-SERVICE - - [17/Aug/2018:09:21:53 +0000] "POST /users HTTP/1.1" 201';
    private const LOG_LINE_INVALID = 'Invalid log line without expected format';

    protected function setUp(): void
    {
        $this->parser = new LineParser();
    }

    public function testParseLine(): void
    {
        $parsed = $this->parser->parseLine(self::LOG_LINE);

        $this->assertInstanceOf(Line::class, $parsed);
        $this->assertSame('USER-SERVICE', $parsed->getServiceName());
        $this->assertEquals(new \DateTimeImmutable('2018-08-17 09:21:53 +0000'), $parsed->getDateTime());
        $this->assertSame('POST /users HTTP/1.1', $parsed->getMessage());
        $this->assertSame(201, $parsed->getStatusCode());
    }

    public function testParseInvalidLine(): void
    {
        $this->expectException(LineParserException::class);

        $this->parser->parseLine(self::LOG_LINE_INVALID);
    }

    public function testMatchLine()
    {
        $matches = $this->parser->matchLine(self::LOG_LINE);

        $this->assertSame('USER-SERVICE', $matches['1']);
        $this->assertSame('17/Aug/2018:09:21:53 +0000', $matches['2']);
        $this->assertSame('POST /users HTTP/1.1', $matches['3']);
        $this->assertSame('201', $matches['4']);
    }

    public function testMatchInvalidLine()
    {
        $this->expectException(LineParserException::class);

        $this->parser->matchLine(self::LOG_LINE_INVALID);
    }

    public function testParseDate(): void
    {
        $dateStr = '01/Jan/2020:00:00:00 +0000';

        $parsed = $this->parser->parseDate($dateStr);

        $this->assertInstanceOf(\DateTimeImmutable::class, $parsed);
        $this->assertEquals(new \DateTimeImmutable('2020-01-01 00:00:00 +0000'), $parsed);
    }

    public function testParseInvalidDate(): void
    {
        $this->expectException(LineParserException::class);

        $this->parser->parseDate('invalid-date');
    }
}
