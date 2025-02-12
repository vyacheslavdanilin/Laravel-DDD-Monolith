<?php

declare(strict_types=1);

namespace LUVR\Domain\Entities;

use DateTimeInterface;
use LUVR\Domain\ValueObjects\LUVRStatus;
use Shared\Domain\Aggregate\AggregateRoot;
use ShiftPlanning\Domain\ValueObjects\ShiftId;

final class LUVR extends AggregateRoot
{
    public function __construct(
        private readonly int $id,
        private readonly ShiftId $shiftId,
        private LUVRStatus $status,
        private DateTimeInterface $startDateTime,
        private DateTimeInterface $endDateTime,
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

    public function setStatus(LUVRStatus $status): void
    {
        $this->status = $status;
    }

    public function getStartDateTime(): DateTimeInterface
    {
        return $this->startDateTime;
    }

    public function getEndDateTime(): DateTimeInterface
    {
        return $this->endDateTime;
    }

    public function updateDates(DateTimeInterface $start, DateTimeInterface $end): void
    {
        if ($start >= $end) {
            throw new \DomainException('Начало смены должно быть раньше окончания.');
        }
        $this->startDateTime = $start;
        $this->endDateTime = $end;
    }

    public function updateStatus(LUVRStatus $status): void
    {
        if ($this->status === LUVRStatus::Paid) {
            throw new \DomainException('Невозможно изменить оплаченный LUVR.');
        }
        $this->status = $status;
    }
}
