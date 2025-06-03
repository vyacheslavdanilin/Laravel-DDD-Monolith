<?php

namespace Tests\Unit;

use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ShiftPlanning\Domain\ValueObjects\ShiftTimeRange;

class ShiftTimeRangeTest extends TestCase
{
    public function test_constructor_throws_exception_when_start_is_after_end(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ShiftTimeRange(new DateTimeImmutable('2025-01-02 10:00'), new DateTimeImmutable('2025-01-02 09:00'));
    }

    public function test_calculate_duration_returns_hours(): void
    {
        $range = new ShiftTimeRange(
            new DateTimeImmutable('2025-01-02 08:00'),
            new DateTimeImmutable('2025-01-02 16:30')
        );

        $this->assertSame(8.5, $range->calculateDuration());
    }
}
