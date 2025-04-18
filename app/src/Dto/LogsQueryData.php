<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class LogsQueryData
{
    public function __construct(
        #[Assert\All([
            new Assert\Type('string'),
        ])]
        #[Assert\Optional]
        private ?array $serviceNames = null,
        #[Assert\Type('integer')]
        #[Assert\Optional]
        private ?int $statusCode = null,
        #[Assert\DateTime]
        #[Assert\Optional]
        private ?\DateTimeInterface $startDate = null,
        #[Assert\DateTime]
        #[Assert\Optional]
        private ?\DateTimeInterface $endDate = null,
    ) {}

    public function getServiceNames(): ?array
    {
        return $this->serviceNames;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    /**
     * @throws \DateMalformedStringException
     */
    public static function fromArray(array $data): self
    {
        return new self(
            serviceNames: isset($data['serviceNames']) && is_array($data['serviceNames']) ? $data['serviceNames'] : null,
            statusCode: isset($data['statusCode']) ? (int) $data['statusCode'] : null,
            startDate: isset($data['startDate']) ? new \DateTimeImmutable($data['startDate']) : null,
            endDate: isset($data['endDate']) ? new \DateTimeImmutable($data['endDate']) : null,
        );
    }
}
