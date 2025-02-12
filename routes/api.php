<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use ShiftPlanning\Presentation\Http\Controllers\ShiftPlanningController;

Route::prefix('v1')->group(function () {
    Route::prefix('shifts')->group(function () {
        Route::post('/', [ShiftPlanningController::class, 'create']);
    });
});
