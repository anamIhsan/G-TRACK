<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Village;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VillageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Urutan aman
        Village::truncate();
        User::where('role', 'ADMIN_DESA')->delete();

        // Ambil zone
        $zones = DB::table('zones')->limit(3)->get(['id', 'nama']);

        $villages = [
            ['nama' => 'Desa Mekar Jaya', 'zone' => 0],
            ['nama' => 'Desa Suka Makmur', 'zone' => 1],
            ['nama' => 'Desa Maju Bersama', 'zone' => 2],
        ];

        foreach ($villages as $data) {
            $zone = $zones[$data['zone']];

            // email & username dari nama desa
            $slug = strtolower(str_replace(' ', '_', $data['nama']));

            // buat admin desa
            $user = User::create([
                'nama' => 'Admin ' . $data['nama'],
                'username' => $slug,
                'email' => $slug . '@gmail.com',
                'password' => Hash::make('123456789'),
                'hint_password' => '123456789',
                'role' => 'ADMIN_DESA',
            ]);

            // buat village
            Village::create([
                'id' => Str::uuid(),
                'nama' => $data['nama'],
                'user_id' => $user->id,
                'zone_id' => $zone->id,
            ]);
        }
    }
}
