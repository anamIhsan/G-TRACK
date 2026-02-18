<?php

namespace Database\Seeders;

use App\Models\AgeCategory;
use App\Models\Group;
use App\Models\Interest;
use App\Models\SubInterest;
use App\Models\User;
use App\Models\Village;
use App\Models\Work;
use App\Models\Zone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::truncate();
        AgeCategory::truncate();
        Zone::truncate();
        // Work::truncate();
        Interest::truncate();
        SubInterest::truncate();

        $this->call([
            AgeCategorySeeder::class,
            ZoneSeeder::class,
            // InterestSeeder::class,
            // SubInterestSeeder::class,
            // WorkSeeder::class,
            UserSeeder::class,
        ]);
    }
}
