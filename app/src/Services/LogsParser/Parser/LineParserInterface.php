<?php

declare(strict_types=1);

namespace App\Services\LogsParser\Parser;

interface LineParserInterface
{
    public function parseLine(string $line): Line;
}
