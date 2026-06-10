<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PlanSeeder::class,
            PassportSeeder::class
        ]);

        // If self-hosted, seed the user seeder
        if(config('trypost.self_hosted')) {
            $this->call(UserSeeder::class);
        }
    }
}
