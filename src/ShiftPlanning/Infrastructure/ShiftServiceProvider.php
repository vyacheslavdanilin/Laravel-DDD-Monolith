<?php

declare(strict_types=1);

namespace ShiftPlanning\Infrastructure;

use Illuminate\Support\ServiceProvider;
use ShiftPlanning\Domain\Repositories\ShiftPlanningRepositoryInterface;
use ShiftPlanning\Infrastructure\Persistence\ShiftPlanningRepository;

final class ShiftServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ShiftPlanningRepositoryInterface::class, ShiftPlanningRepository::class);
    }
}
