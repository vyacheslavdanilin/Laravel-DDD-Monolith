<?php

declare(strict_types=1);

namespace ShiftPlanning\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use ShiftPlanning\Application\DTOs\CreateShiftPlanningDto;

final class CreateShiftPlanningRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'start_date_time' => ['required', 'date'],
            'end_date_time' => ['required', 'date', 'after:start_date_time'],
        ];
    }

    public function toDto(): CreateShiftPlanningDto
    {
        return new CreateShiftPlanningDto(
            startDateTime: $this->input('start_date_time'),
            endDateTime: $this->input('end_date_time'),
        );
    }
}
