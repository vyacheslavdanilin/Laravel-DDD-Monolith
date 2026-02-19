<?php

declare(strict_types=1);

namespace ShiftPlanning\Application\Services;

use ShiftPlanning\Application\DTOs\CreateShiftPlanningDto;
use ShiftPlanning\Domain\Entities\Shift;
use ShiftPlanning\Domain\Repositories\ShiftPlanningRepositoryInterface;
use ShiftPlanning\Domain\ValueObjects\ShiftStatus;
use ShiftPlanning\Domain\ValueObjects\ShiftTimeRange;

final class ShiftPlanningService
{
    public function __construct(
        private readonly ShiftPlanningRepositoryInterface $repository
    ) {
    }

    public function create(CreateShiftPlanningDto $dto): Shift
    {
        $timeRange = new ShiftTimeRange($dto->getStartDateTime(), $dto->getEndDateTime());
        $status = ShiftStatus::getDefaultStatus();

        $shift = Shift::create($timeRange, $status);

        return $this->repository->save($shift);
    }
}
