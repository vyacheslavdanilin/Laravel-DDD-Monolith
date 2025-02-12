<?php

declare(strict_types=1);

namespace Shared\Domain\Contracts;

use Shared\Domain\Events\AbstractDomainEvent;

interface EventBus
{
    public function publish(AbstractDomainEvent ...$events): void;
}
