<?php

namespace Tests\Feature;

use Tests\TestCase;

class TimelineApiTest extends TestCase
{
    public function test_timeline_api_returns_json_data_from_storage_files(): void
    {
        $response = $this->getJson('/api/timeline');

        $response->assertOk()
            ->assertJsonStructure([
                'events' => [['slug', 'title', 'start_date']],
                'about_me' => ['name', 'title', 'summary'],
                'about_this_site' => ['title', 'summary'],
            ])
            ->assertJsonPath('events.0.slug', 'first-computer-program')
            ->assertJsonPath('about_me.name', 'Bruce Flett')
            ->assertJsonPath('about_this_site.title', 'About This Site');
    }
}
