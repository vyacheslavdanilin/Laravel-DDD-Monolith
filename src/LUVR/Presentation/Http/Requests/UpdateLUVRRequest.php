<?php

declare(strict_types=1);

namespace LUVR\Presentation\Http\Requests;

use DateTimeImmutable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LUVR\Application\DTOs\UpdateLUVRDto;
use LUVR\Domain\ValueObjects\LUVRStatus;

final class UpdateLUVRRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'start_date_time' => ['nullable', 'date'],
            'end_date_time' => ['nullable', 'date', 'after:start_date_time'],
            'status' => ['nullable', Rule::enum(LUVRStatus::class)],
        ];
    }

    public function toDto(): UpdateLUVRDto
    {
        return new UpdateLUVRDto(
            startDateTime: $this->input('start_date_time') ? new DateTimeImmutable($this->input('start_date_time')) : null,
            endDateTime: $this->input('end_date_time') ? new DateTimeImmutable($this->input('end_date_time')) : null,
            status: $this->input('status') !== null ? LUVRStatus::fromValue((int) $this->input('status')) : null
        );
    }
}
