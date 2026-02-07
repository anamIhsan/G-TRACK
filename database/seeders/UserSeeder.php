<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus hanya MASTER & USER
        User::whereIn('role', ['MASTER', 'USER'])->delete();

        /**
         * =====================
         * MASTER ADMIN
         * =====================
         */
        User::create([
            'id' => Str::uuid(),
            'nama' => 'Admin Master',
            'username' => 'admin_master',
            'email' => 'admin_master@gmail.com',
            'password' => Hash::make('123456789'),
            'hint_password' => '123456789',
            'role' => 'MASTER',
            'status_request' => 1,
        ]);

        /**
         * =====================
         * RELATIONS
         * =====================
         */
        // $zones = DB::table('zones')->get();
        // $villages = DB::table('villages')->get();
        // $groups = DB::table('groups')->get();
        // $interests = DB::table('interests')->get();
        // $subInterests = DB::table('sub_interests')->get();
        // $works = DB::table('works')->get();

        // /**
        //  * =====================
        //  * USERS
        //  * =====================
        //  */
        // $users = [
        //     ['nama' => 'Budi Santoso', 'kelamin' => 'LK'],
        //     ['nama' => 'Siti Aminah', 'kelamin' => 'PR'],
        //     ['nama' => 'Andi Wijaya', 'kelamin' => 'LK'],
        // ];

        // foreach ($users as $index => $data) {
        //     $slug = strtolower(str_replace(' ', '_', $data['nama']));

        //     User::create([
        //         'id' => Str::uuid(),
        //         'nama' => $data['nama'],
        //         'username' => $slug,
        //         'email' => $slug . '@gmail.com',
        //         'password' => Hash::make('123456789'),
        //         'hint_password' => '123456789',
        //         'role' => 'USER',

        //         // data opsional
        //         'kelamin' => $data['kelamin'],
        //         'umur' => rand(20, 45),
        //         'nfc_id' => rand(100000000, 999999999),
        //         'status_kawin' => rand(0, 1) ? 'SUDAH' : 'BELUM',
        //         'status_request' => 1,

        //         // relasi (aman walau kosong)
        //         'zone_id' => $zones->isNotEmpty()
        //             ? $zones[$index % $zones->count()]->id
        //             : null,

        //         'village_id' => $villages->isNotEmpty()
        //             ? $villages[$index % $villages->count()]->id
        //             : null,

        //         'group_id' => $groups->isNotEmpty()
        //             ? $groups[$index % $groups->count()]->id
        //             : null,

        //         'interest_id' => $interests->isNotEmpty()
        //             ? $interests[$index % $interests->count()]->id
        //             : null,

        //         'sub_interest_id' => $subInterests->isNotEmpty()
        //             ? $subInterests[$index % $subInterests->count()]->id
        //             : null,

        //         'work_id' => $works->isNotEmpty()
        //             ? $works[$index % $works->count()]->id
        //             : null,
        //     ]);
        // }
    }
}
