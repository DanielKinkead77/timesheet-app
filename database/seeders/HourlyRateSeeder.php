<?php

namespace Database\Seeders;

use App\Models\HourlyRate;
use Illuminate\Database\Seeder;

class HourlyRateSeeder extends Seeder
{
    public function run(): void
    {
        $rates = [
            ['rate_code' => 'NRM', 'description' => 'Normal Rate'],
            ['rate_code' => 'OVT', 'description' => 'Overtime'],
            ['rate_code' => 'DBL', 'description' => 'Double Time'],
        ];

        foreach ($rates as $rate) {
            HourlyRate::firstOrCreate(
                ['rate_code' => $rate['rate_code']],
                ['description' => $rate['description']]
            );
        }
    }
}