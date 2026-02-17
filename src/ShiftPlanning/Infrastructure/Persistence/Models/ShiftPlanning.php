<?php

declare(strict_types=1);

namespace ShiftPlanning\Infrastructure\Persistence\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use ShiftPlanning\Domain\ValueObjects\ShiftStatus;

/**
 * @property int $id
 * @property int|null $performer_id
 * @property int|null $position_id
 * @property int|null $store_id
 * @property CarbonInterface $start_date_time
 * @property CarbonInterface $end_date_time
 * @property float|null $working_hours
 * @property string|null $comment
 * @property int $status_id
 */
final class ShiftPlanning extends EloquentModel
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'shifts';

    protected $fillable = [
        'performer_id',
        'position_id',
        'start_date_time',
        'end_date_time',
        'working_hours',
        'comment',
        'status_id',
        'store_id',
    ];

    protected $casts = [
        'start_date_time' => 'datetime',
        'end_date_time' => 'datetime',
        'working_hours' => 'float',
        'status_id' => 'integer',
    ];

    public function getStatusAttribute(): string
    {
        return ShiftStatus::fromValue($this->status_id)->getText();
    }
}
