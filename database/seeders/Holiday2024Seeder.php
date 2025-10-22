<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Holiday2024Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $holidays = [
            // 元旦 (2023-12-30 to 2024-01-01)
            ['name' => '元旦', 'date' => '2023-12-30', 'type' => 'national', 'is_working_day' => false, 'year' => 2024],
            ['name' => '元旦', 'date' => '2023-12-31', 'type' => 'national', 'is_working_day' => false, 'year' => 2024],
            ['name' => '元旦', 'date' => '2024-01-01', 'type' => 'national', 'is_working_day' => false, 'year' => 2024],
            
            // 農曆春節 (2024-02-08 to 2024-02-14)
            ['name' => '春節除夕', 'date' => '2024-02-08', 'type' => 'national', 'is_working_day' => false, 'year' => 2024],
            ['name' => '春節初一', 'date' => '2024-02-09', 'type' => 'national', 'is_working_day' => false, 'year' => 2024],
            ['name' => '春節初二', 'date' => '2024-02-10', 'type' => 'national', 'is_working_day' => false, 'year' => 2024],
            ['name' => '春節初三', 'date' => '2024-02-11', 'type' => 'national', 'is_working_day' => false, 'year' => 2024],
            ['name' => '春節初四', 'date' => '2024-02-12', 'type' => 'national', 'is_working_day' => false, 'year' => 2024],
            ['name' => '春節初五', 'date' => '2024-02-13', 'type' => 'national', 'is_working_day' => false, 'year' => 2024],
            ['name' => '春節初六', 'date' => '2024-02-14', 'type' => 'national', 'is_working_day' => false, 'year' => 2024],
            
            // 228和平紀念日
            ['name' => '228和平紀念日', 'date' => '2024-02-28', 'type' => 'national', 'is_working_day' => false, 'year' => 2024],
            
            // 兒童節及清明節 (2024-04-04 to 2024-04-07)
            ['name' => '兒童節', 'date' => '2024-04-04', 'type' => 'national', 'is_working_day' => false, 'year' => 2024],
            ['name' => '清明節', 'date' => '2024-04-05', 'type' => 'national', 'is_working_day' => false, 'year' => 2024],
            ['name' => '清明節補假', 'date' => '2024-04-06', 'type' => 'national', 'is_working_day' => false, 'year' => 2024],
            ['name' => '清明節補假', 'date' => '2024-04-07', 'type' => 'national', 'is_working_day' => false, 'year' => 2024],
            
            // 五一勞動節 (限勞工放假)
            ['name' => '勞動節', 'date' => '2024-05-01', 'type' => 'national', 'is_working_day' => false, 'year' => 2024],
            
            // 端午節 (2024-06-08 to 2024-06-10)
            ['name' => '端午節', 'date' => '2024-06-08', 'type' => 'national', 'is_working_day' => false, 'year' => 2024],
            ['name' => '端午節補假', 'date' => '2024-06-09', 'type' => 'national', 'is_working_day' => false, 'year' => 2024],
            ['name' => '端午節補假', 'date' => '2024-06-10', 'type' => 'national', 'is_working_day' => false, 'year' => 2024],
            
            // 中秋節
            ['name' => '中秋節', 'date' => '2024-09-17', 'type' => 'national', 'is_working_day' => false, 'year' => 2024],
            
            // 國慶日
            ['name' => '國慶日', 'date' => '2024-10-10', 'type' => 'national', 'is_working_day' => false, 'year' => 2024],
        ];

        foreach ($holidays as $holiday) {
            Holiday::updateOrCreate(
                ['date' => $holiday['date']],
                $holiday
            );
        }
    }
}
