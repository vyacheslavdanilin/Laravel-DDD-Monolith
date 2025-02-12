<?php

declare(strict_types=1);

namespace Shared\Domain\Aggregate;

use Shared\Domain\Contracts\EventBus;
use Shared\Domain\Events\AbstractDomainEvent;

abstract class AggregateRoot
{
    private array $domainEvents = [];

    final public function pullDomainEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];

        return $events;
    }

    final protected function record(AbstractDomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }

    final public function releaseEvents(EventBus $eventBus): void
    {
        $eventBus->publish(...$this->pullDomainEvents());
    }
}
