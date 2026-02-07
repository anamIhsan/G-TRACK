<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Urutan aman
        Group::truncate();
        User::where('role', 'ADMIN_KELOMPOK')->delete();

        // Ambil relasi
        $zones = DB::table('zones')->limit(3)->get(['id', 'nama']);
        $villages = DB::table('villages')->limit(3)->get(['id', 'nama']);

        $groups = [
            [
                'nama' => 'Kelompok Tani Sejahtera',
                'zone_index' => 0,
                'village_index' => 0,
            ],
            [
                'nama' => 'Kelompok Maju Bersama',
                'zone_index' => 1,
                'village_index' => 1,
            ],
            [
                'nama' => 'Kelompok Makmur Jaya',
                'zone_index' => 2,
                'village_index' => 2,
            ],
        ];

        foreach ($groups as $data) {
            $zone = $zones[$data['zone_index']];
            $village = $villages[$data['village_index']];

            // slug untuk email & username
            $slug = strtolower(str_replace(' ', '_', $data['nama']));

            // buat admin group
            $user = User::create([
                'nama' => 'Admin ' . $data['nama'],
                'username' => $slug,
                'email' => $slug . '@gmail.com',
                'password' => Hash::make('123456789'),
                'hint_password' => '123456789',
                'role' => 'ADMIN_KELOMPOK',
            ]);

            // buat group
            Group::create([
                'id' => Str::uuid(),
                'nama' => $data['nama'],
                'zone_id' => $zone->id,
                'village_id' => $village->id,
                'user_id' => $user->id,
            ]);
        }
    }
}
