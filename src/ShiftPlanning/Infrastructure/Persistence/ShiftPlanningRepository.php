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

        return new Shift(
            id: new ShiftId($model->id),
            timeRange: new ShiftTimeRange($model->start_date_time, $model->end_date_time),
            status: ShiftStatus::fromValue($model->status_id)
        );
    }

    public function create(Shift $shift): Shift
    {
        $model = ShiftPlanning::create([
            'start_date_time' => $shift->getTimeRange()->getStart(),
            'end_date_time' => $shift->getTimeRange()->getEnd(),
            'status_id' => $shift->getStatus()->value,
        ]);

        return new Shift(
            id: new ShiftId($model->id),
            timeRange: new ShiftTimeRange(
                new DateTimeImmutable($model->start_date_time->toDateTimeString()),
                new DateTimeImmutable($model->end_date_time->toDateTimeString())
            ),
            status: ShiftStatus::fromValue($model->status_id)
        );

    }

    public function save(Shift $shift): void
    {
        ShiftPlanning::updateOrCreate(
            ['id' => $shift->getId()->toInt()],
            [
                'start_date_time' => $shift->getTimeRange()->getStart(),
                'end_date_time' => $shift->getTimeRange()->getEnd(),
                'status_id' => $shift->getStatus()->value,
            ]
        );
    }

    public function delete(Shift $shift): void
    {
        ShiftPlanning::destroy($shift->getId()->toInt());
    }
}
