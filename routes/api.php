<?php

use App\Http\Controllers\Api\VacancyApiController;
use App\Http\Controllers\Api\ApplicationApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/vacancies', [VacancyApiController::class, 'index']);
    Route::get('/vacancies/{slug}', [VacancyApiController::class, 'show']);
    Route::get('/track-application', [ApplicationApiController::class, 'track']);
});
