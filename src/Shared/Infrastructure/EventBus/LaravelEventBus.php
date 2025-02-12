<?php

declare(strict_types=1);

namespace Shared\Infrastructure\EventBus;

use Illuminate\Contracts\Events\Dispatcher;
use Shared\Domain\Contracts\EventBus;
use Shared\Domain\Events\AbstractDomainEvent;

class LaravelEventBus implements EventBus
{
    public function __construct(private readonly Dispatcher $dispatcher) {}

    public function publish(AbstractDomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->dispatcher->dispatch($event);
        }
    }
}
