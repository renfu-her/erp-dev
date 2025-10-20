<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        $leaveTypes = [
            '特別休假' => 'ANNUAL',
            '婚假' => 'MARRIAGE',
            '喪假' => 'FUNERAL',
            '產假' => 'MATERNITY',
            '陪產假' => 'PATERNITY',
            '產檢假' => 'PRENATAL_CHECKUP',
            '流產假' => 'MISCARRIAGE',
            '事假' => 'PERSONAL',
            '公假' => 'OFFICIAL',
            '普通傷病假' => 'SICK',
            '公傷病假' => 'OCCUPATIONAL_SICK',
            '生理假' => 'MENSTRUAL',
            '家庭照顧假' => 'FAMILY_CARE',
            '育嬰留職停薪假' => 'PARENTAL_LEAVE',
        ];

        foreach ($leaveTypes as $name => $code) {
            LeaveType::updateOrCreate(
                ['name' => $name],
                [
                    'code' => $code,
                    'requires_approval' => true,
                    'default_quota' => null,
                    'affects_attendance' => true,
                    'rules' => null,
                ]
            );
        }

        LeaveType::whereNotIn('name', array_keys($leaveTypes))->delete();
    }
}
