<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        $leaveTypes = [
            '特別休假' => [
                'code' => 'ANNUAL',
                'default_quota' => null,
                'rules' => [
                    'legal_reference' => '勞基法第38條',
                    'annual_limit' => null,
                    'pay' => '全薪',
                    'notes' => '依年資遞增：6個月至1年3日、1-2年7日、2-3年10日、3-5年14日、5-10年15日、10年以上每年加1日至30日；每年 1/1 擷取年資重新計算。',
                    'reset_policy' => [
                        'type' => 'annual',
                        'date' => '01-01',
                    ],
                ],
            ],
            '婚假' => [
                'code' => 'MARRIAGE',
                'default_quota' => 8,
                'rules' => [
                    'legal_reference' => '勞工請假規則第3條',
                    'annual_limit' => 8,
                    'pay' => '全薪',
                    'notes' => '限申請一次，須於結婚前後十日內請假。',
                ],
            ],
            '喪假' => [
                'code' => 'FUNERAL',
                'default_quota' => 8,
                'rules' => [
                    'legal_reference' => '勞工請假規則第4條',
                    'annual_limit' => null,
                    'pay' => '全薪',
                    'notes' => '直系血親尊親屬8日、方親屬及子女6日、曾祖父母及兄弟姐妹3日。',
                ],
            ],
            '產假' => [
                'code' => 'MATERNITY',
                'default_quota' => 56,
                'rules' => [
                    'legal_reference' => '勞工請假規則第5條',
                    'annual_limit' => 56,
                    'pay' => '全薪或產假津貼依規定給付',
                    'notes' => '懷孕滿6個月者得請產前後合計8週；未滿6個月者4至6週依胎齡減半。',
                ],
            ],
            '陪產假' => [
                'code' => 'PATERNITY',
                'default_quota' => 7,
                'rules' => [
                    'legal_reference' => '性別工作平等法第15條',
                    'annual_limit' => 7,
                    'pay' => '全薪',
                    'notes' => '於配偶分娩前後十五日內請假，共七日。',
                ],
            ],
            '產檢假' => [
                'code' => 'PRENATAL_CHECKUP',
                'default_quota' => 5,
                'rules' => [
                    'legal_reference' => '性別工作平等法第15條',
                    'annual_limit' => 5,
                    'pay' => '全薪',
                    'notes' => '孕婦可請五日產前檢查假，得分次申請。',
                ],
            ],
            '流產假' => [
                'code' => 'MISCARRIAGE',
                'default_quota' => 14,
                'rules' => [
                    'legal_reference' => '性別工作平等法第15條',
                    'annual_limit' => 14,
                    'pay' => '全薪',
                    'notes' => '妊娠未滿3個月：5日；3-6個月：14日；6個月以上視同產假。',
                ],
            ],
            '事假' => [
                'code' => 'PERSONAL',
                'default_quota' => 14,
                'rules' => [
                    'legal_reference' => '勞工請假規則第7條',
                    'annual_limit' => 14,
                    'pay' => '無薪',
                    'notes' => '含家庭照顧假，全年合計14日。',
                ],
            ],
            '公假' => [
                'code' => 'OFFICIAL',
                'default_quota' => null,
                'rules' => [
                    'legal_reference' => '勞工請假規則第8條',
                    'annual_limit' => null,
                    'pay' => '全薪',
                    'notes' => '依法令得請之假別，如服兵役、選舉、法院傳喚等。',
                ],
            ],
            '普通傷病假' => [
                'code' => 'SICK',
                'default_quota' => 30,
                'rules' => [
                    'legal_reference' => '勞工請假規則第5條',
                    'annual_limit' => 30,
                    'pay' => '半薪',
                    'notes' => '未住院全年30日；住院兩年內合計不超過1年，可申請留職停薪。',
                ],
            ],
            '公傷病假' => [
                'code' => 'OCCUPATIONAL_SICK',
                'default_quota' => null,
                'rules' => [
                    'legal_reference' => '勞工請假規則第6條',
                    'annual_limit' => null,
                    'pay' => '全薪',
                    'notes' => '因職災療養休養期間，工資照給。',
                ],
            ],
            '生理假' => [
                'code' => 'MENSTRUAL',
                'default_quota' => 3,
                'rules' => [
                    'legal_reference' => '性別工作平等法第14條',
                    'annual_limit' => 3,
                    'pay' => '全薪 (3日內)',
                    'notes' => '每月1日，全年3日；超過部分併入普通傷病假計算。',
                ],
            ],
            '育嬰留職停薪假' => [
                'code' => 'PARENTAL_LEAVE',
                'default_quota' => null,
                'rules' => [
                    'legal_reference' => '性別工作平等法第16條',
                    'annual_limit' => 730,
                    'pay' => '無薪 (可依法領育嬰津貼)',
                    'notes' => '可請至子女滿三歲止，最多兩年。',
                ],
            ],
        ];

        foreach ($leaveTypes as $name => $settings) {
            LeaveType::updateOrCreate(
                ['name' => $name],
                [
                    'code' => $settings['code'],
                    'requires_approval' => true,
                    'default_quota' => $settings['default_quota'],
                    'affects_attendance' => true,
                    'rules' => $settings['rules'] ?? null,
                ]
            );
        }

        LeaveType::whereNotIn('name', array_keys($leaveTypes))->delete();
    }
}
