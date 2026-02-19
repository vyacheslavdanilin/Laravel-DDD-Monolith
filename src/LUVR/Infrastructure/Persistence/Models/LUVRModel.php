<?php

declare(strict_types=1);

namespace LUVR\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class LUVRModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'luvrs';

    protected $fillable = [
        'shift_id',
        'performer_id',
        'position_id',
        'store_id',
        'status_id',
        'start_date_time',
        'end_date_time',
    ];

    protected $casts = [
        'start_date_time' => 'datetime',
        'end_date_time' => 'datetime',
        'status_id' => 'integer',
    ];
}
