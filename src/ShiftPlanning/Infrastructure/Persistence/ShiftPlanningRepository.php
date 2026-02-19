<?php

declare(strict_types=1);

namespace ShiftPlanning\Infrastructure\Persistence;

use DateTimeImmutable;
use ShiftPlanning\Domain\Entities\Shift;
use ShiftPlanning\Domain\Repositories\ShiftPlanningRepositoryInterface;
use ShiftPlanning\Domain\ValueObjects\ShiftId;
use ShiftPlanning\Domain\ValueObjects\ShiftStatus;
use ShiftPlanning\Domain\ValueObjects\ShiftTimeRange;
use ShiftPlanning\Infrastructure\Persistence\Models\ShiftPlanning;

final class ShiftPlanningRepository implements ShiftPlanningRepositoryInterface
{
    public function find(ShiftId $id): ?Shift
    {
        $model = ShiftPlanning::find($id->toInt());
        if (! $model) {
            return null;
        }

        $start = $model->start_date_time instanceof \DateTimeImmutable
            ? $model->start_date_time
            : new \DateTimeImmutable($model->start_date_time->format(\DATE_ATOM));
        $end = $model->end_date_time instanceof \DateTimeImmutable
            ? $model->end_date_time
            : new \DateTimeImmutable($model->end_date_time->format(\DATE_ATOM));

        return Shift::reconstitute(
            new ShiftId($model->id),
            new ShiftTimeRange($start, $end),
            ShiftStatus::fromValue($model->status_id)
        );
    }

    public function save(Shift $shift): Shift
    {
        $model = ShiftPlanning::updateOrCreate(
            ['id' => $shift->getId()->toInt() ?: null],
            [
                'start_date_time' => $shift->getTimeRange()->getStart(),
                'end_date_time' => $shift->getTimeRange()->getEnd(),
                'status_id' => $shift->getStatus()->value,
            ]
        );

        return Shift::reconstitute(
            new ShiftId($model->id),
            new ShiftTimeRange(
                new DateTimeImmutable($model->start_date_time->toDateTimeString()),
                new DateTimeImmutable($model->end_date_time->toDateTimeString())
            ),
            ShiftStatus::fromValue($model->status_id)
        );
    }

    public function delete(Shift $shift): void
    {
        ShiftPlanning::destroy($shift->getId()->toInt());
    }
}
