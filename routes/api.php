<?php

use App\Http\Controllers\TimelineController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/timeline', [TimelineController::class, 'index']);
Route::get('/stats', function () {
    return response()->json([
        'total_hits' => DB::table('visits')->count(),
        'unique_nodes' => DB::table('visits')->distinct('ip_hash')->count('ip_hash') ?: 1,
        'active_connections' => DB::table('visits')->where('created_at', '>=', now()->subMinutes(5))->distinct('ip_hash')->count('ip_hash') ?: 1,
    ]);
});
