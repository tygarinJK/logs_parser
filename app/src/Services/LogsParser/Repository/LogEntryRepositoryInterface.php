<?php

declare(strict_types=1);

namespace App\Services\LogsParser\Repository;

use App\Services\LogsParser\Parser\Line;

interface LogEntryRepositoryInterface
{
    public function save(Line ...$parsedLines): void;
}
