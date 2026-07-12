<?php

namespace Database\Seeders;

use App\Models\TimelineEvent;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $path = storage_path('timeline/events.json');
        $events = json_decode(File::get($path), true, 512, JSON_THROW_ON_ERROR);

        foreach ($events as $event) {
            TimelineEvent::updateOrCreate(
                ['slug' => $event['slug']],
                [
                    'title' => $event['title'],
                    'client_or_company' => $event['client_or_company'],
                    'role' => $event['role'],
                    'era' => $event['era'],
                    'tagline' => $event['tagline'] ?? null,
                    'description' => $event['description'] ?? null,
                    'tech_stack' => $event['tech_stack'] ?? [],
                    'metadata' => $event['metadata'] ?? [],
                    'skills' => $event['skills'] ?? [],
                    'start_date' => $event['start_date'],
                    'end_date' => $event['end_date'] ?? null,
                    'featured' => (bool) ($event['featured'] ?? false),
                ]
            );
        }
    }
}
