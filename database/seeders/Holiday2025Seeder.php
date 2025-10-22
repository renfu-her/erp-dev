<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Holiday2025Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $holidays = [
            // 元旦
            ['name' => '元旦', 'date' => '2025-01-01', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
            
            // 春節 (2025-01-25 to 2025-02-02)
            ['name' => '春節除夕', 'date' => '2025-01-25', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
            ['name' => '春節初一', 'date' => '2025-01-26', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
            ['name' => '春節初二', 'date' => '2025-01-27', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
            ['name' => '春節初三', 'date' => '2025-01-28', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
            ['name' => '春節初四', 'date' => '2025-01-29', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
            ['name' => '春節初五', 'date' => '2025-01-30', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
            ['name' => '春節初六', 'date' => '2025-01-31', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
            ['name' => '春節初七', 'date' => '2025-02-01', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
            ['name' => '春節初八', 'date' => '2025-02-02', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
            
            // 228和平紀念日 (2025-02-28 to 2025-03-02)
            ['name' => '228和平紀念日', 'date' => '2025-02-28', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
            ['name' => '228和平紀念日補假', 'date' => '2025-03-01', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
            ['name' => '228和平紀念日補假', 'date' => '2025-03-02', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
            
            // 清明節 (2025-04-03 to 2025-04-06)
            ['name' => '清明節', 'date' => '2025-04-03', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
            ['name' => '清明節補假', 'date' => '2025-04-04', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
            ['name' => '清明節補假', 'date' => '2025-04-05', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
            ['name' => '清明節補假', 'date' => '2025-04-06', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
            
            // 勞動節
            ['name' => '勞動節', 'date' => '2025-05-01', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
            
            // 端午節 (2025-05-30 to 2025-06-01)
            ['name' => '端午節', 'date' => '2025-05-30', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
            ['name' => '端午節補假', 'date' => '2025-05-31', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
            ['name' => '端午節補假', 'date' => '2025-06-01', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
            
            // 中秋節 (2025-10-04 to 2025-10-06)
            ['name' => '中秋節', 'date' => '2025-10-04', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
            ['name' => '中秋節補假', 'date' => '2025-10-05', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
            ['name' => '中秋節補假', 'date' => '2025-10-06', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
            
            // 國慶日 (2025-10-10 to 2025-10-12)
            ['name' => '國慶日', 'date' => '2025-10-10', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
            ['name' => '國慶日補假', 'date' => '2025-10-11', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
            ['name' => '國慶日補假', 'date' => '2025-10-12', 'type' => 'national', 'is_working_day' => false, 'year' => 2025],
        ];

        foreach ($holidays as $holiday) {
            Holiday::updateOrCreate(
                ['date' => $holiday['date']],
                $holiday
            );
        }
    }
}
