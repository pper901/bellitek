<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // This array lists all the individual seeders you want to run.
        $this->call([
            ApiProviderSeeder::class, // <-- This calls the new provider seeder
            // Add other seeders here, e.g., UserSeeder::class,
        ]);
    }
}