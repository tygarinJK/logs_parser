<?php

declare(strict_types=1);

namespace App\Services\LogsParser\Parser;

use App\Services\LogsParser\ValueObject\ParsedLine;

interface LineParserInterface
{
    public function parseLine(string $line): ParsedLine;

    /**
     * @throws LineParserException
     */
    public function matchLine(string $logLine): array;

    /**
     * @throws LineParserException
     */
    public function parseDate(string $raw): \DateTimeImmutable;
}
