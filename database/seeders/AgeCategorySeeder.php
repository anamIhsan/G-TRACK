<?php

namespace Database\Seeders;

use App\Models\AgeCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AgeCategory::truncate();

        $age_categories = [
            [
                "nama" => "PAUD",
                "range_one" => 1,
                "range_two" => 9
            ],
            [
                "nama" => "Anak-Anak",
                "range_one" => 10,
                "range_two" => 14
            ],
            [
                "nama" => "Pra Remaja",
                "range_one" => 15,
                "range_two" => 20
            ],
            [
                "nama" => "Remaja",
                "range_one" => 21,
                "range_two" => 29
            ],
            [
                "nama" => "Pra Nikah",
                "range_one" => 30,
                "range_two" => 40
            ],
        ];

        foreach ($age_categories as $age_category) {
            AgeCategory::create([
                'nama' => $age_category['nama'],
                'range_one' => $age_category['range_one'],
                'range_two' => $age_category['range_two'],
            ]);
        }
    }
}
