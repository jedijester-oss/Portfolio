<?php

namespace Database\Factories;

use App\Models\TimelineEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TimelineEvent>
 */
class TimelineEventFactory extends Factory
{
    protected $model = TimelineEvent::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(3),
            'title' => fake()->sentence(4),
            'client_or_company' => fake()->company(),
            'role' => fake()->jobTitle(),
            'era' => fake()->randomElement(['Foundations', 'Education', 'Enterprise Architecture', 'Bespoke Engineering']),
            'tagline' => fake()->sentence(6),
            'description' => fake()->paragraph(),
            'tech_stack' => fake()->randomElements(['Laravel', 'PHP', 'Vue', 'JavaScript', 'MySQL', 'Docker'], 3),
            'metadata' => ['image' => fake()->imageUrl()],
            'skills' => fake()->randomElements(['Architecture', 'Leadership', 'Backend', 'Frontend'], 2),
            'start_date' => fake()->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
            'end_date' => fake()->optional()->dateTimeBetween('now', '+5 years')->format('Y-m-d'),
            'featured' => fake()->boolean(20),
        ];
    }
}
