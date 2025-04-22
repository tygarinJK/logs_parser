<?php

declare(strict_types=1);

namespace App\Services\LogsParser;

use App\Services\LogsParser\FileGenerator\FileGeneratorInterface;

interface LogsParserServiceInterface
{
    public function parseLogs(FileGeneratorInterface $generator, int $iteration_size): void;
}
