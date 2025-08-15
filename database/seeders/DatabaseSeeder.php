<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SuperAdminSeed::class,
            SettingsSeeder::class,
            TagSeeder::class,
            CategorySeeder::class,
            BlogSeeder::class,
        ]);
    }
}
