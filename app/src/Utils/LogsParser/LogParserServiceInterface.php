<?php

namespace App\Utils\LogsParser;

interface LogParserServiceInterface
{
    public function processLogRecord(string $record): bool;

    public function parseLogLine(string $line): ?array;
}