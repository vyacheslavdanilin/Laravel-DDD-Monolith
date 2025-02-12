<?php

declare(strict_types=1);

namespace ShiftPlanning\Domain\Events;

use Shared\Domain\Events\AbstractDomainEvent;
use ShiftPlanning\Domain\ValueObjects\ShiftId;
use ShiftPlanning\Domain\ValueObjects\ShiftStatus;
use ShiftPlanning\Domain\ValueObjects\ShiftTimeRange;

final class ShiftCreated extends AbstractDomainEvent
{
    public function __construct(
        private readonly ShiftId $shiftId,
        private readonly ShiftTimeRange $timeRange,
        private readonly ShiftStatus $status
    ) {
        parent::__construct([
            'shiftId' => $shiftId->toInt(),
            'start' => $timeRange->getStart()->format(DATE_ATOM),
            'end' => $timeRange->getEnd()->format(DATE_ATOM),
            'status' => $status->value,
        ]);
    }

    public static function eventName(): string
    {
        return 'shift.created';
    }
}
