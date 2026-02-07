<?php

namespace Database\Seeders;

use App\Models\Interest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InterestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Interest::truncate();

        $zones = DB::table('zones')->get(['id', 'nama']);

        foreach ($zones as $zone) {
            DB::table('interests')->insert([
                [
                    'id' => Str::uuid(),
                    'nama' => 'Pertanian ' . $zone->nama,
                    'zone_id' => $zone->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => Str::uuid(),
                    'nama' => 'Peternakan ' . $zone->nama,
                    'zone_id' => $zone->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
