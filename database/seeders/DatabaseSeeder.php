<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            CreatorSeeder::class,
            ProductSeeder::class,
            TrainingClassSeeder::class,
            BuyerSeeder::class,
        ]);
    }
}
