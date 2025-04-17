<?php

namespace App\Services\LogsParser\Repository;

use App\Services\LogsParser\ValueObject\ParsedLine;

interface LogEntryRepositoryInterface
{
    public function save(ParsedLine ...$parsedLines): void;
}