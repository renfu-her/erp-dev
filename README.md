# ERP 開發專案

一個基於 Laravel 12 開發的企業資源規劃（ERP）系統，專注於人力資源管理功能。

## 快速開始

### 系統需求
- PHP >= 8.2
- Composer
- SQLite 或 MySQL
- Git

### 安裝步驟

```bash
# 1. 克隆專案
git clone <repository-url> erp-dev
cd erp-dev

# 2. 安裝依賴
composer install

# 3. 設定環境變數
cp .env.example .env
php artisan key:generate

# 4. 建立資料庫並填充資料
php artisan migrate --seed

# 5. 啟動開發伺服器
php artisan serve
```

訪問 http://localhost:8000

## 主要功能

### ✨ 核心模組
- **公司組織管理** - 多公司、多部門、職位、員工資料維護
- **出勤管理** - 打卡記錄、出勤統計、補登管理
- **請假管理** - 假別設定、請假申請、審核流程
- **薪資管理** - 薪資期間、計算批次、保險級距
- **權限控制** - 角色權限、公司範圍權限、操作審計

### 🎨 技術特色
- 前後台分離設計
- Bootstrap 5 + jQuery 響應式介面
- 完整的 RBAC 權限系統
- RESTful API 架構
- PHPUnit 測試覆蓋

## 測試帳號

### 後台管理
- **帳號**: admin@erp.local
- **密碼**: password
- **權限**: 系統管理員（完整權限）

### 前台員工
- **帳號**: employee1@erp.local / employee2@erp.local
- **密碼**: password
- **權限**: 員工（打卡、請假）

### 前台主管
- **帳號**: manager@erp.local
- **密碼**: password
- **權限**: 公司管理者（審核權限）

⚠️ **安全提醒**: 請在首次登入後立即修改密碼！

## 專案結構

```
erp-dev/
├── app/                    # 應用程式邏輯
│   ├── Http/Controllers/   # 控制器
│   │   ├── Auth/          # 認證
│   │   ├── Backend/       # 後台
│   │   └── Frontend/      # 前台
│   ├── Models/            # Eloquent 模型
│   └── View/              # 視圖元件
├── database/              # 資料庫
│   ├── migrations/        # 資料表遷移
│   └── seeders/           # 資料填充
├── resources/views/       # Blade 模板
│   ├── backend/          # 後台視圖
│   └── frontend/         # 前台視圖
├── routes/
│   ├── web.php           # Web 路由
│   └── api.php           # API 路由
├── tests/                # 測試檔案
└── public/               # 公開資源
```

## 開發指令

```bash
# 執行測試
php artisan test

# 程式碼格式化（PSR-12）
./vendor/bin/pint

# 重建資料庫
php artisan migrate:fresh --seed

# 清除快取
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 主要路由

### 認證
- `GET /login` - 登入頁面
- `POST /logout` - 登出

### 前台（員工入口）
- `GET /frontend` - 員工首頁
- `POST /frontend/attendance/{action}` - 打卡（check-in/check-out）
- `GET /frontend/hr/leave-request` - 請假申請

### 後台（管理介面）
- `GET /backend` - 後台總覽
- `/backend/companies` - 公司管理
- `/backend/departments` - 部門管理
- `/backend/positions` - 職位管理
- `/backend/employees` - 員工管理
- `/backend/attendance` - 出勤管理
- `/backend/leave-types` - 假別管理
- `/backend/leave-requests` - 請假審核
- `/backend/payroll` - 薪資管理

## 文件

📖 **完整專案文件**: [PROJECT_DOCUMENTATION.md](PROJECT_DOCUMENTATION.md)

包含：
- 詳細模組說明
- 資料庫架構
- API 文件
- 開發規範
- 部署指南
- 後續開發計劃

📋 **其他文件**:
- [AGENTS.md](AGENTS.md) - 開發指南
- [docs/HR_MODULES.md](docs/HR_MODULES.md) - HR 模組說明

## 開發規範

### 程式碼風格
- 遵循 PSR-12 標準
- 使用 Laravel Pint 格式化
- 四空格縮排

### Git 提交
```
<type>: <subject>

feat: 新功能
fix: 錯誤修復
docs: 文件更新
style: 格式調整
refactor: 重構
test: 測試相關
```

### 測試
- 執行 `php artisan test` 確保測試通過
- 新功能必須包含測試
- 目標覆蓋率 80%+

## 權限系統

### 預設角色
1. **System Owner** - 系統擁有者（跨公司完整權限）
2. **Company Manager** - 公司管理者（單一公司完整權限）
3. **HR Manager** - 人資主管（人事、出勤、薪資）
4. **Department Manager** - 部門主管（部門管理、審核）
5. **Employee** - 一般員工（個人資料、打卡、請假）

### 權限列表
- `backend.access` - 後台存取
- `company.manage` - 公司管理
- `employee.manage` - 員工管理
- `attendance.manage` - 出勤管理
- `leave.manage` - 請假管理
- `payroll.view` / `payroll.manage` - 薪資檢視/管理
- `frontend.portal.access` - 前台存取
- `frontend.leave.submit` - 請假申請

## 後續開發計劃

### 短期（1-3 個月）
- [ ] 出勤自動統計排程
- [ ] 請假審核通知機制
- [ ] 薪資計算引擎
- [ ] 權限系統優化

### 中期（3-6 個月）
- [ ] 招募管理模組
- [ ] 訓練發展模組
- [ ] 績效管理模組
- [ ] 報表系統

### 長期（6-12 個月）
- [ ] 行動應用開發
- [ ] AI 功能整合
- [ ] 第三方系統整合
- [ ] 多國化支援

## 技術棧

### 後端
- **框架**: Laravel 12.x
- **語言**: PHP 8.2+
- **資料庫**: SQLite / MySQL
- **認證**: Laravel Session + Sanctum
- **測試**: PHPUnit 11.x

### 前端
- **UI**: Bootstrap 5.3.x
- **JavaScript**: jQuery 3.7.x
- **模板**: Blade
- **圖示**: Bootstrap Icons

## 授權

本專案採用 [MIT License](https://opensource.org/licenses/MIT) 授權。

## 貢獻

歡迎提交 Issue 和 Pull Request！

## 聯絡資訊

如有問題或建議，請聯繫專案負責人。

---

**建立於**: 2025-10-22  
**Laravel 版本**: 12.x  
**PHP 版本**: 8.2+
