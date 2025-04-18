<?php

declare(strict_types=1);

namespace App\Services\LogsParser\Parser;

class LineParser implements LineParserInterface
{
    private const REGEX = '/^(\S+)\s+-\s+-\s+\[(.*?)\]\s+"(.*?)"\s+(\d{3})$/';

    public function parseLine(string $line): Line
    {
        $matches = $this->matchLine($line);

        $dateTime = $this->parseDate($matches['2']);

        return new Line(
            $matches['1'],
            $dateTime,
            $matches['3'],
            (int) $matches['4']
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
