<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;

class TimelineController extends Controller
{
    /**
     * Return the portfolio content from the JSON files in storage/timeline.
     */
    public function index(): JsonResponse
    {
        $timelineDirectory = storage_path('timeline');

        $events = $this->loadJson($timelineDirectory . '/events.json');
        $aboutMe = $this->loadJson($timelineDirectory . '/aboutme.json');
        $aboutThisSite = $this->loadJson($timelineDirectory . '/aboutthissite.json');

        usort($events, static function (array $a, array $b): int {
            return strcmp($a['start_date'] ?? '', $b['start_date'] ?? '');
        });

        return response()->json([
            'events' => $events,
            'about_me' => $aboutMe,
            'about_this_site' => $aboutThisSite,
        ]);
    }

    /**
     * Load a JSON file and coerce it into an array for the frontend.
     */
    protected function loadJson(string $path): array
    {
        $contents = File::get($path);

        return json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
    }
}
