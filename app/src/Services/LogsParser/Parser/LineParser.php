<?php

declare(strict_types=1);

namespace App\Services\LogsParser\Parser;

use App\Services\LogsParser\ValueObject\ParsedLine;

class LineParser implements LineParserInterface
{
    private const REGEX = '/^(?<serviceName>[^\s]+)\s+-\s+-\s+\[(?<date>[^\]]+)\]\s+"(?<message>[^"]+)"\s+(?<statusCode>\d+)/';

    public function parseLine(string $line): ParsedLine
    {
        $matches = $this->matchLine($line);

        $dateTime = $this->parseDate($matches['date']);

        return new ParsedLine(
            $matches['serviceName'],
            $dateTime,
            $matches['message'],
            (int) $matches['statusCode']
        );
    }

    public function matchLine(string $logLine): array
    {
        if (!preg_match(self::REGEX, $logLine, $matches)) {
            throw new LineParserException(sprintf('Invalid format: Unable to parse line "%s"', $logLine));
        }

        return $matches;
    }

    public function parseDate(string $raw): \DateTimeImmutable
    {
        $date = \DateTimeImmutable::createFromFormat('d/M/Y:H:i:s O', $raw);

        if (false === $date) {
            throw new LineParserException(sprintf('Invalid format: Unable to parse date "%s"', $raw));
        }

        return $date;
    }
}
