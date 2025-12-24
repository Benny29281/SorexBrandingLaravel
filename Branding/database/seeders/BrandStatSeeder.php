<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BrandStat;

class BrandStatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data = [
            ['label' => 'Impressions', 'value' => 12450, 'meta' => json_encode(['color' => '#60a5fa'])],
            ['label' => 'Engagements', 'value' => 2840, 'meta' => json_encode(['color' => '#f472b6'])],
            ['label' => 'Conversions', 'value' => 312, 'meta' => json_encode(['color' => '#34d399'])],
        ];

        foreach ($data as $item) {
            BrandStat::create($item);
        }
    }
}
