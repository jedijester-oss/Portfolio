<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\LiveStatsUpdated;

class TrackTerminalVisits
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Skip background utility routes, debug pages, or static asset calls
        if ($request->is('_tailwind*') || $request->is('build/*') || $request->ajax()) {
            return $response;
        }

        try {
            // Log a lightweight footprint into SQLite
            // Using a simple upsert/insert statement for active session tracking
            DB::table('visits')->insert([
                'ip_hash' => hash('sha256', $request->ip()),
                'url' => $request->fullUrl(),
                'user_agent' => substr($request->userAgent(), 0, 255),
                'created_at' => now(),
            ]);

            // Dispatch the WebSocket broadcast event
            event(new LiveStatsUpdated());
        } catch (\Exception $e) {
            // Silently fail if database is locked to keep user experience frictionless
        }

        return $response;
    }
}