<?php

declare(strict_types=1);

use App\Http\Controllers\Api\MeetupController;
use Illuminate\Support\Facades\Route;

Route::get('/meetups', [MeetupController::class, 'index']);
Route::get('/meetups/{event}', [MeetupController::class, 'show']);
