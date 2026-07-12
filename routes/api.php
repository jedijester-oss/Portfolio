<?php

use App\Http\Controllers\TimelineController;
use Illuminate\Support\Facades\Route;

Route::get('/timeline', [TimelineController::class, 'index']);
