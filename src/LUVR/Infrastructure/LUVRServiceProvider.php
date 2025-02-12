<?php

declare(strict_types=1);

namespace LUVR\Infrastructure;

use Illuminate\Support\ServiceProvider;
use LUVR\Domain\Repositories\LUVRRepositoryInterface;
use LUVR\Infrastructure\Persistence\LUVRRepository;

final class LUVRServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LUVRRepositoryInterface::class, LUVRRepository::class);
    }
}
