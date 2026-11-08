<?php

use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [SessionController::class, 'login'])->middleware('throttle:6,1');
Route::post('/logout', [SessionController::class, 'logout'])->middleware('auth');

Route::view('/', 'app');
Route::view('/login', 'app');
Route::view('/app/{path?}', 'app')->where('path', '.*');
