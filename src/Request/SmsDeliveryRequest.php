<?php

namespace MobilniPlatbyCz\Request;

use DateTimeImmutable;
use InvalidArgumentException;
use MobilniPlatbyCz\Response\SmsDeliveryResponse;

class SmsDeliveryRequest implements SmsRequestInterface
{
    private const AVAILABLE_STATUSES = [
        'DELIVERED',
        'UNDELIVERED',
        'PENDING',
        'WAITING',
        'UNKNOWN',
    ];
    private const AVAILABLE_MESSAGES = [
        'NOT_ENOUGH_CREDIT',
        'INVALID_OPERATOR',
        'SERVICE_NOT_ALLOWED',
        'SERVICE_BLOCKED',
        'USAGE_RATE_EXCEEDED',
        'MT_SERVICE_NOT_ALLOWED',
        'CUSTOMER_BLOCKED',
        'DAILY_LIMIT_EXCEEDED',
        'INTERNAL_ERROR',
        'INFO_NOT_AVAILABLE',
    ];

    private DateTimeImmutable $dateTime;
    private int $requestId;
    private string $status;
    private ?string $statusMessage = null;
    private ?int $partNumber = null;
    private ?int $partCount = null;
    private int $attempt;
    private int $id;

    public function getDateTime(): DateTimeImmutable
    {
        return $this->dateTime;
    }

    public function setDateTime(DateTimeImmutable $dateTime): void
    {
        $this->dateTime = $dateTime;
    }

    public function getRequestId(): int
    {
        return $this->requestId;
    }

    public function setRequestId(int $requestId): void
    {
        $this->requestId = $requestId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        if (!in_array($status, self::AVAILABLE_STATUSES, true)) {
            throw new InvalidArgumentException('Invalid status');
        }

        $this->status = $status;
    }

    public function getStatusMessage(): ?string
    {
        return $this->statusMessage;
    }

    public function setStatusMessage(?string $statusMessage = null): void
    {
        if (null !== $statusMessage && $this->getStatus() !== 'UNDELIVERED') {
            throw new InvalidArgumentException('Only available for UNDELIVERED status');
        }
        if (null !== $statusMessage && !in_array($statusMessage, self::AVAILABLE_MESSAGES, true)) {
            throw new InvalidArgumentException('Invalid message');
        }

        $this->statusMessage = $statusMessage;
    }

    public function getPartNumber(): ?int
    {
        return $this->partNumber;
    }

    public function setPartNumber(?int $partNumber): void
    {
        $this->partNumber = $partNumber;
    }

    public function getPartCount(): ?int
    {
        return $this->partCount;
    }

    public function setPartCount(?int $partCount): void
    {
        $this->partCount = $partCount;
    }

    public function getAttempt(): int
    {
        return $this->attempt;
    }

    public function setAttempt(int $attempt): void
    {
        $this->attempt = $attempt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function execute(): SmsDeliveryResponse
    {
        return new SmsDeliveryResponse();
    }
}
