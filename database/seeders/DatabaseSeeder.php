<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Only for local development; DemoSeeder explicitly blocks production.
        $this->call(DemoSeeder::class);
    }
}
