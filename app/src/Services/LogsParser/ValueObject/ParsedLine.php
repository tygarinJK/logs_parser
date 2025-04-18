<?php

declare(strict_types=1);

namespace App\Services\LogsParser\ValueObject;

readonly class ParsedLine
{
    public function __construct(
        private string $serviceName,
        private \DateTimeInterface $dateTime,
        private string $message,
        private int $statusCode,
    ) {}

    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    public function getDateTime(): \DateTimeInterface
    {
        return $this->dateTime;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
