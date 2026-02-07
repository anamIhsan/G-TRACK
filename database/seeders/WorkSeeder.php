<?php

namespace Database\Seeders;

use App\Models\Work;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WorkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Work::truncate();

        // Ambil semua zone
        $zones = DB::table('zones')->get(['id', 'nama']);

        foreach ($zones as $zone) {
            DB::table('works')->insert([
                [
                    'id' => Str::uuid(),
                    'nama' => 'Pekerjaan Utama ' . $zone->nama,
                    'zone_id' => $zone->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => Str::uuid(),
                    'nama' => 'Pekerjaan Sampingan ' . $zone->nama,
                    'zone_id' => $zone->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
