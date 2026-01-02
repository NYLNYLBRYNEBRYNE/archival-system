<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Archive>
 */
class ArchiveFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(6), // e.g., "Study of Network Security in Banking"
            'author' => fake()->name(),     // e.g., "Dr. John Doe"
            'year' => fake()->numberBetween(2018, 2025),
            // Important: This points to a dummy file so your "View PDF" button doesn't crash
            'file_path' => 'http://localhost:8000/storage/thesis-files/sample.pdf', 
        ];
    }
}