<?php

declare(strict_types=1);

namespace LUVR\Domain\Entities;

use DateTimeInterface;
use LUVR\Domain\ValueObjects\LUVRStatus;
use LUVR\Domain\Exceptions\LUVRException;
use Shared\Domain\Aggregate\AggregateRoot;
use ShiftPlanning\Domain\ValueObjects\ShiftId;

final class LUVR extends AggregateRoot
{
    public function __construct(
        private readonly int $id,
        private readonly ShiftId $shiftId,
        private readonly LUVRStatus $status,
        private readonly DateTimeInterface $startDateTime,
        private readonly DateTimeInterface $endDateTime,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getShiftId(): ShiftId
    {
        return $this->shiftId;
    }

    public function getStatus(): LUVRStatus
    {
        return $this->status;
    }

    public function getStartDateTime(): DateTimeInterface
    {
        return $this->startDateTime;
    }

    public function getEndDateTime(): DateTimeInterface
    {
        return $this->endDateTime;
    }

    public function withUpdatedDates(DateTimeInterface $start, DateTimeInterface $end): self
    {
        if ($start >= $end) {
            throw LUVRException::invalidDateRange();
        }

        return new self(
            $this->id,
            $this->shiftId,
            $this->status,
            $start,
            $end
        );
    }

    public function withUpdatedStatus(LUVRStatus $status): self
    {
        if ($this->status === LUVRStatus::Paid) {
            throw LUVRException::cannotModifyPaid();
        }

        return new self(
            $this->id,
            $this->shiftId,
            $status,
            $this->startDateTime,
            $this->endDateTime
        );
    }
}
