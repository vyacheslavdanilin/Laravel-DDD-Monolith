<?php

declare(strict_types=1);

namespace ShiftPlanning\Domain\ValueObjects;

use DateTimeImmutable;
use InvalidArgumentException;

final class ShiftTimeRange
{
    private DateTimeImmutable $start;

    private DateTimeImmutable $end;

    public function __construct(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        if ($start >= $end) {
            throw new InvalidArgumentException('Время начала смены должно быть раньше времени окончания.');
        }
        $this->start = $start;
        $this->end = $end;
    }

    public function getStart(): DateTimeImmutable
    {
        return $this->start;
    }

    public function getEnd(): DateTimeImmutable
    {
        return $this->end;
    }

    public function calculateDuration(): float
    {
        return ($this->end->getTimestamp() - $this->start->getTimestamp()) / 3600;
    }
}
