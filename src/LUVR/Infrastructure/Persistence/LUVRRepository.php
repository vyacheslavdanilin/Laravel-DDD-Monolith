<?php

declare(strict_types=1);

namespace LUVR\Infrastructure\Persistence;

use LUVR\Domain\Entities\LUVR;
use LUVR\Domain\Repositories\LUVRRepositoryInterface;
use LUVR\Domain\ValueObjects\LUVRStatus;
use LUVR\Infrastructure\Persistence\Models\LUVRModel;
use ShiftPlanning\Domain\ValueObjects\ShiftId;

final class LUVRRepository implements LUVRRepositoryInterface
{
    public function findById(int $id): ?LUVR
    {
        $model = LUVRModel::find($id);
        if (! $model) {
            return null;
        }

        return new LUVR(
            id: $model->id,
            shiftId: new ShiftId((int) $model->shift_id),
            status: LUVRStatus::fromValue((int) $model->status_id),
            startDateTime: $model->start_date_time,
            endDateTime: $model->end_date_time
        );
    }

    public function save(LUVR $luvr): LUVR
    {
        $model = LUVRModel::updateOrCreate(
            ['id' => $luvr->getId() ?: null],
            [
                'shift_id' => $luvr->getShiftId()->toInt(),
                'status_id' => $luvr->getStatus()->value,
                'start_date_time' => $luvr->getStartDateTime(),
                'end_date_time' => $luvr->getEndDateTime(),
            ]
        );

        return new LUVR(
            id: $model->id,
            shiftId: new ShiftId((int) $model->shift_id),
            status: LUVRStatus::fromValue((int) $model->status_id),
            startDateTime: $model->start_date_time,
            endDateTime: $model->end_date_time
        );
    }

    public function delete(LUVR $luvr): void
    {
        LUVRModel::destroy($luvr->getId());
    }
}
