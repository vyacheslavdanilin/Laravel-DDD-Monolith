<?php

declare(strict_types=1);

namespace Shared\Infrastructure;

use Illuminate\Support\ServiceProvider;
use Shared\Domain\Contracts\EventBus;
use Shared\Infrastructure\EventBus\LaravelEventBus;

final class SharedServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EventBus::class, LaravelEventBus::class);
    }
}
