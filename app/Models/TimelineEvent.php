<?php

namespace App\Models;

use Database\Factories\TimelineEventFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimelineEvent extends Model
{
    use HasFactory;

    /** @use HasFactory<TimelineEventFactory> */
    protected $fillable = [
        'slug',
        'title',
        'client_or_company',
        'role',
        'era',
        'tagline',
        'description',
        'tech_stack',
        'metadata',
        'skills',
        'start_date',
        'end_date',
        'featured',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tech_stack' => 'array',
            'metadata' => 'array',
            'skills' => 'array',
            'featured' => 'boolean',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }
}
