<?php

declare(strict_types=1);

namespace Shared\Domain\Events;

use DateTimeImmutable;
use JsonSerializable;
use Ramsey\Uuid\Uuid;

abstract class AbstractDomainEvent implements JsonSerializable
{
    private string $eventId;

    private DateTimeImmutable $occurredOn;

    private array $payload;

    public function __construct(array $payload, ?string $eventId = null, ?DateTimeImmutable $occurredOn = null)
    {
        $this->eventId = $eventId ?? Uuid::uuid4()->toString();
        $this->occurredOn = $occurredOn ?? new DateTimeImmutable;
        $this->payload = $payload;
    }

    public function getEventId(): string
    {
        return $this->eventId;
    }

    public function getOccurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    abstract public static function eventName(): string;

    public function jsonSerialize(): array
    {
        return [
            'eventId' => $this->getEventId(),
            'occurredOn' => $this->getOccurredOn()->format(DATE_ATOM),
            'eventName' => static::eventName(),
            'payload' => $this->getPayload(),
        ];
    }
}
