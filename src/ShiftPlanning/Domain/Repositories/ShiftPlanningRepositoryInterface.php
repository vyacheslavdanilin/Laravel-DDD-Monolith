<?php

declare(strict_types=1);

namespace ShiftPlanning\Domain\Repositories;

use ShiftPlanning\Domain\Entities\Shift;
use ShiftPlanning\Domain\ValueObjects\ShiftId;

interface ShiftPlanningRepositoryInterface
{
    public function find(ShiftId $id): ?Shift;

    public function save(Shift $shift): Shift;

    public function delete(Shift $shift): void;
}
