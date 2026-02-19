<?php

namespace Tests\Unit;

use DateTimeImmutable;
use LUVR\Domain\Entities\LUVR;
use LUVR\Domain\Exceptions\LUVRException;
use LUVR\Domain\ValueObjects\LUVRStatus;
use PHPUnit\Framework\TestCase;
use ShiftPlanning\Domain\ValueObjects\ShiftId;

class LUVRTest extends TestCase
{
    private function createLuvr(LUVRStatus $status = LUVRStatus::WaitingForApproval): LUVR
    {
        return new LUVR(
            id: 1,
            shiftId: new ShiftId(1),
            status: $status,
            startDateTime: new DateTimeImmutable('2025-01-01 08:00'),
            endDateTime: new DateTimeImmutable('2025-01-01 16:00'),
        );
    }

    public function test_with_updated_dates_validates_range(): void
    {
        $luvr = $this->createLuvr();

        $this->expectException(LUVRException::class);
        $luvr->withUpdatedDates(new DateTimeImmutable('2025-01-01 17:00'), new DateTimeImmutable('2025-01-01 16:00'));
    }

    public function test_with_updated_status_throws_when_paid(): void
    {
        $luvr = $this->createLuvr(LUVRStatus::Paid);

        $this->expectException(LUVRException::class);
        $luvr->withUpdatedStatus(LUVRStatus::Approved);
    }
}
