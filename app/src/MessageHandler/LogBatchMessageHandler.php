<?php

namespace App\MessageHandler;

use App\Message\LogBatchMessage;
use App\Utils\LogsParser\LogParserServiceInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class LogBatchMessageHandler
{
    public function __construct(
        private LogParserServiceInterface $parser
    ) {}

    public function __invoke(LogBatchMessage $message): void
    {
        foreach ($message->batch as $line) {
            $this->parser->processLogRecord($line);
        }
    }
}