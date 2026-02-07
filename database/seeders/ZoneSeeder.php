<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Zone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Urutan truncate penting
        Zone::truncate();
        User::where('role', 'ADMIN_DAERAH')->delete();

        $zones = [
            'Zona Barat',
            'Zona Tengah',
            'Zona Timur',
        ];

        foreach ($zones as $namaZone) {
            // generate email dari nama zone
            $email = strtolower(str_replace(' ', '_', $namaZone)) . '@gmail.com';

            // buat user admin zone
            $user = User::create([
                'nama' => 'Admin ' . $namaZone,
                'username' => strtolower(str_replace(' ', '_', $namaZone)),
                'email' => $email,
                'password' => Hash::make('123456789'),
                'hint_password' => '123456789',
                'role' => 'ADMIN_DAERAH',
            ]);

            // buat zone dan relasikan ke user admin
            Zone::create([
                'id' => Str::uuid(),
                'nama' => $namaZone,
                'user_id' => $user->id,
            ]);
        }
    }
}
