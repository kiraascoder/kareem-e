<?php

namespace Database\Seeders;

use App\Models\Season;
use Illuminate\Database\Seeder;

class SeasonSeeder extends Seeder
{
    public function run(): void
    {

        Season::updateOrCreate(
            ['kode' => 'high'],
            [
                'nama'        => 'High Season',
                'start_month' => 10,
                'end_month'   => 1,
                'is_active'   => true,
            ]
        );


        Season::updateOrCreate(
            ['kode' => 'low'],
            [
                'nama'        => 'Low Season',
                'start_month' => 2,
                'end_month'   => 9,
                'is_active'   => true,
            ]
        );
    }
}
