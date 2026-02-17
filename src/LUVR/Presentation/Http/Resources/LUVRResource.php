<?php

declare(strict_types=1);

namespace LUVR\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use LUVR\Domain\Entities\LUVR;

final class LUVRResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var LUVR $luvr */
        $luvr = $this->resource;

        return [
            'id' => $luvr->getId(),
            'shift_id' => $luvr->getShiftId()->toInt(),
            'status' => $luvr->getStatus()->getText(),
            'start_date_time' => $luvr->getStartDateTime()->format(DATE_ATOM),
            'end_date_time' => $luvr->getEndDateTime()->format(DATE_ATOM),
        ];
    }
}
