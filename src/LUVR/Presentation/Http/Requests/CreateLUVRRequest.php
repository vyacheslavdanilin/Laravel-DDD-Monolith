<?php

declare(strict_types=1);

namespace LUVR\Presentation\Http\Requests;

use DateTimeImmutable;
use Illuminate\Foundation\Http\FormRequest;
use LUVR\Application\DTOs\CreateLUVRDto;

final class CreateLUVRRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'shift_id' => ['required', 'integer'],
            'start_date_time' => ['required', 'date'],
            'end_date_time' => ['required', 'date'],
        ];
    }

    public function toDto(): CreateLUVRDto
    {
        return new CreateLUVRDto(
            shiftId: (int) $this->shift_id,
            startDateTime: new DateTimeImmutable($this->start_date_time),
            endDateTime: new DateTimeImmutable($this->end_date_time)
        );
    }
}
