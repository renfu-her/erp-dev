<?php

namespace Database\Seeders;

use App\Models\PositionLevel;
use Illuminate\Database\Seeder;

class PositionLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            ['code' => 'CEO', 'name' => '總裁', 'rank' => 900, 'description' => 'Chief Executive Officer'],
            ['code' => 'PRESIDENT', 'name' => '總經理', 'rank' => 850, 'description' => 'President / General Manager'],
            ['code' => 'VICE_PRESIDENT', 'name' => '副總經理', 'rank' => 800, 'description' => 'Vice President'],
            ['code' => 'DIRECTOR', 'name' => '部長', 'rank' => 750, 'description' => 'Division Director'],
            ['code' => 'SECTION_CHIEF', 'name' => '科長', 'rank' => 700, 'description' => 'Section Chief'],
            ['code' => 'TEAM_LEAD', 'name' => '課長', 'rank' => 650, 'description' => 'Team Leader / Manager'],
            ['code' => 'SPECIAL_ASSISTANT', 'name' => '特助', 'rank' => 630, 'description' => 'Special Assistant'],
            ['code' => 'SUPERVISOR', 'name' => '主任', 'rank' => 600, 'description' => 'Supervisor'],
            ['code' => 'STAFF', 'name' => '職員', 'rank' => 500, 'description' => 'Staff / Specialist'],
        ];

        foreach ($levels as $level) {
            PositionLevel::updateOrCreate(
                ['code' => $level['code']],
                [
                    'name' => $level['name'],
                    'rank' => $level['rank'],
                    'description' => $level['description'] ?? null,
                ]
            );
        }
    }
}
