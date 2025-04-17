<?php

namespace App\Services\LogsParser\FileGenerator;

interface FileGeneratorInterface
{
    /**
     * @return iterable<string>
     */
    public function getLines(): iterable;
}