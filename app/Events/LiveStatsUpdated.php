<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class LiveStatsUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The channel name where the event broadcasts.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('terminal-metrics'),
        ];
    }

    /**
     * Payload containing the fresh analytical insights
     */
    public function broadcastWith(): array
    {
        // Calculate aggregations directly from the SQLite footprint
        $totalVisits = DB::table('visits')->count();
        $uniqueVisitors = DB::table('visits')->distinct('ip_hash')->count('ip_hash');
        
        // Active in the last 5 minutes
        $activeNow = DB::table('visits')
            ->where('created_at', '>=', now()->subMinutes(5))
            ->distinct('ip_hash')
            ->count('ip_hash');

        return [
            'total_hits' => $totalVisits,
            'unique_nodes' => $uniqueVisitors ?: 1,
            'active_connections' => $activeNow ?: 1,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}