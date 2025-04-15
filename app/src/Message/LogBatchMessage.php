<?php

namespace App\Message;

readonly class LogBatchMessage
{
    public function __construct(
        public array $batch,
    ) {
    }
}