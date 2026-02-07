<?php

namespace Database\Seeders;

use App\Models\SubInterest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubInterestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SubInterest::truncate();

        // Ambil semua interest beserta zone_id
        $interests = DB::table('interests')->get(['id', 'nama', 'zone_id']);

        foreach ($interests as $interest) {
            DB::table('sub_interests')->insert([
                [
                    'id' => Str::uuid(),
                    'nama' => 'Sub ' . $interest->nama . ' A',
                    'interest_id' => $interest->id,
                    'zone_id' => $interest->zone_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => Str::uuid(),
                    'nama' => 'Sub ' . $interest->nama . ' B',
                    'interest_id' => $interest->id,
                    'zone_id' => $interest->zone_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
