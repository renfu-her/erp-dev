<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AccessControlSeeder::class,
            InsuranceBracketSeeder::class,
            PositionLevelSeeder::class,
            LeaveTypeSeeder::class,
            CompanyDataSeeder::class,
            AdminUserSeeder::class,
            FrontendUserSeeder::class,
        ]);
    }
}
