<?php

declare(strict_types=1);

namespace ShiftPlanning\Presentation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use ShiftPlanning\Domain\Entities\Shift;

final class ShiftPlanningResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Shift $this */
        return [
            'id' => $this->getId()->toInt(),
            'start_date_time' => $this->getTimeRange()->getStart()->format(DATE_ATOM),
            'end_date_time' => $this->getTimeRange()->getEnd()->format(DATE_ATOM),
            'status' => $this->getStatus()->getText(),
        ];
    }
}
