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
    public function __construct(
        private ShiftId $id,
        private ShiftTimeRange $timeRange,
        private ShiftStatus $status,
    ) {
        $this->record(new ShiftCreated($this->id, $this->timeRange, $this->status));
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
