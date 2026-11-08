<?php

use App\Http\Controllers\Api\DirectoryController;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\SawController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(['auth:sanctum', 'active'])->group(function () {
    Route::get('/me', [SessionController::class, 'me']);
    Route::get('/overview', [DirectoryController::class, 'overview']);
    Route::get('/directory', [DirectoryController::class, 'index']);
    Route::post('/users', [DirectoryController::class, 'createUser']);
    Route::post('/periods', [DirectoryController::class, 'createPeriod']);
    Route::post('/periods/{period}/activate', [DirectoryController::class, 'activatePeriod']);
    Route::post('/departments', [DirectoryController::class, 'createDepartment']);
    Route::post('/memberships', [DirectoryController::class, 'createMembership']);
    Route::patch('/users/{user}/active', [DirectoryController::class, 'toggleUser']);
    Route::put('/memberships/{membership}/permissions', [DirectoryController::class, 'setOverride']);
    Route::get('/programs', [ProgramController::class, 'index']);
    Route::post('/programs', [ProgramController::class, 'store']);
    Route::patch('/programs/{program}', [ProgramController::class, 'update']);
    Route::post('/programs/{program}/submit', [ProgramController::class, 'submit']);
    Route::post('/programs/{program}/notes', [ProgramController::class, 'note']);
    Route::post('/programs/{program}/decisions', [ProgramController::class, 'decision']);
    Route::post('/programs/{program}/complete', [ProgramController::class, 'complete']);
    Route::get('/saw', [SawController::class, 'index']);
    Route::put('/saw/weights', [SawController::class, 'weights']);
    Route::post('/saw/assessments', [SawController::class, 'assessment']);
    Route::post('/saw/runs', [SawController::class, 'calculate']);
});
