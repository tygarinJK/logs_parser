<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class LogsQueryData
{
    /**
     * @var null|array<string> $serviceNames
     */
    #[Assert\Type(type: 'array')]
    #[Assert\All(constraints: [new Assert\Type(type: 'string')])]
    private ?array $serviceNames;

    #[Assert\AtLeastOneOf(constraints: [new Assert\Blank(), new Assert\DateTime()])]
    private ?\DateTimeInterface $startDate;

    #[Assert\AtLeastOneOf(constraints: [new Assert\Blank(), new Assert\DateTime()])]
    private ?\DateTimeInterface $endDate;

    #[Assert\AtLeastOneOf(constraints: [new Assert\Blank(), new Assert\Type(type: 'int')])]
    private ?int $statusCode;

    /**
     * @param null|array<string> $serviceNames
     */
    public function __construct(
        ?array $serviceNames = null,
        ?int $statusCode = null,
        ?\DateTimeInterface $startDate = null,
        ?\DateTimeInterface $endDate = null,
    ) {
        $this->serviceNames = $serviceNames;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->statusCode = $statusCode;
    }

    /**
     * @return null|array<string>
     */
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
     * @param array{
     *      serviceNames?: array<string>|null,
     *      statusCode?: int|string|null,
     *      startDate?: string|null,
     *      endDate?: string|null
     *  } $data
     *
     * @throws \DateMalformedStringException
     */
    public static function fromArray(array $data): self
    {
        return new self(
            serviceNames: $data['serviceNames'] ?? null,
            statusCode: isset($data['statusCode']) ? (int) $data['statusCode'] : null,
            startDate: isset($data['startDate']) ? new \DateTimeImmutable($data['startDate']) : null,
            endDate: isset($data['endDate']) ? new \DateTimeImmutable($data['endDate']) : null,
        );
    }
}
