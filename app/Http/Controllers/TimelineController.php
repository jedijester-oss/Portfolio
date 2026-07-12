<?php

namespace App\Http\Controllers;

use App\Models\TimelineEvent;
use Illuminate\Http\JsonResponse;

class TimelineController extends Controller
{
    public function index(): JsonResponse
    {
        $events = TimelineEvent::query()
            ->orderBy('start_date')
            ->get();

        return response()->json($events);
    }
}
