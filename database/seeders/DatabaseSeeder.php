<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            HourlyRateSeeder::class,
        ]);

        User::where('email', 'test1@test.com')
            ->update(['is_admin' => true]);
    }
}