<?php

namespace Database\Seeders;

use App\Models\Archive; // Don't forget to import your model
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Generate 10 fake thesis entries
        Archive::factory(10)->create();
    }
}