<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\LogsQueryData;

interface LogEntryRepositoryInterface
{
    public function getCount(LogsQueryData $data): int;
}
