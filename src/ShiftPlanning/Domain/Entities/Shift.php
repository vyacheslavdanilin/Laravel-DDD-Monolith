<?php

declare(strict_types=1);

namespace ShiftPlanning\Domain\Entities;

use Shared\Domain\Aggregate\AggregateRoot;
use ShiftPlanning\Domain\Events\ShiftCreated;
use ShiftPlanning\Domain\ValueObjects\ShiftId;
use ShiftPlanning\Domain\ValueObjects\ShiftStatus;
use ShiftPlanning\Domain\ValueObjects\ShiftTimeRange;

final class Shift extends AggregateRoot
{
    private function __construct(
        private ShiftId $id,
        private ShiftTimeRange $timeRange,
        private ShiftStatus $status,
    ) {}

    public static function create(ShiftTimeRange $timeRange, ShiftStatus $status): self
    {
        $shift = new self(new ShiftId(0), $timeRange, $status);
        $shift->record(new ShiftCreated($shift->id, $shift->timeRange, $shift->status));

        return $shift;
    }

    public static function reconstitute(ShiftId $id, ShiftTimeRange $timeRange, ShiftStatus $status): self
    {
        return new self($id, $timeRange, $status);
    }

    public function getId(): ShiftId
    {
        return $this->id;
    }

    public function getTimeRange(): ShiftTimeRange
    {
        return $this->timeRange;
    }

    public function getStatus(): ShiftStatus
    {
        return $this->status;
    }

    public function isFinalStatus(): bool
    {
        return $this->status->isFinal();
    }
}
