<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => '系統管理員',
            'email' => 'admin@admin.com',
            'password' => Hash::make('Qq123456'),
            'email_verified_at' => now(),
            'is_admin' => true,
        ]);

        // 創建測試管理員
        User::create([
            'name' => '測試管理員',
            'email' => 'test@admin.com',
            'password' => Hash::make('Qq123456'),
            'email_verified_at' => now(),
            'is_admin' => true,
        ]);

        // 創建一般使用者
        User::create([
            'name' => '一般使用者',
            'email' => 'user@gmail.com',
            'password' => Hash::make('Qq123456'),
            'email_verified_at' => now(),
            'is_admin' => false,
        ]);
    }
}
