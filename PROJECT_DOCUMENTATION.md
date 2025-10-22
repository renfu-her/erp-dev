# ERP 開發專案 - 完整文件

## 目錄
1. [專案概述](#專案概述)
2. [技術棧](#技術棧)
3. [專案結構](#專案結構)
4. [核心模組](#核心模組)
5. [數據庫架構](#數據庫架構)
6. [權限與角色系統](#權限與角色系統)
7. [路由設計](#路由設計)
8. [前端架構](#前端架構)
9. [開發環境設置](#開發環境設置)
10. [測試帳號](#測試帳號)
11. [開發規範](#開發規範)
12. [API 文件](#api-文件)
13. [部署指南](#部署指南)
14. [後續開發計劃](#後續開發計劃)

---

## 專案概述

這是一個基於 Laravel 12 開發的企業資源規劃（ERP）系統，主要聚焦於人力資源管理功能。系統採用現代化的 Web 應用架構，提供後台管理介面和員工自助服務入口。

### 主要功能模組
- **公司組織管理**：公司、部門、職位、員工資料維護
- **出勤管理**：打卡記錄、出勤統計、補登管理
- **請假管理**：假別設定、請假申請、審核流程
- **薪資管理**：薪資期間、薪資計算、保險級距
- **權限控制**：角色權限、公司範圍權限、操作審計

### 系統特色
- ✅ 前後台分離設計
- ✅ 完整的權限控制系統
- ✅ 多公司/多部門支援
- ✅ 響應式 Bootstrap 5 介面
- ✅ RESTful API 架構
- ✅ 完整的測試覆蓋

---

## 技術棧

### 後端技術
- **框架**: Laravel 12.x (PHP 8.2+)
- **資料庫**: SQLite (開發環境) / MySQL (生產環境)
- **認證**: Laravel Session Authentication
- **API**: Laravel Sanctum (Token Authentication)
- **測試**: PHPUnit 11.x

### 前端技術
- **UI 框架**: Bootstrap 5.3.x (CDN)
- **JavaScript**: jQuery 3.7.x (CDN)
- **樣式**: 自訂 CSS (`public/css/app.css`)
- **模板引擎**: Blade Templates
- **圖示**: Bootstrap Icons

### 開發工具
- **程式碼風格**: Laravel Pint (PSR-12)
- **依賴管理**: Composer
- **版本控制**: Git
- **除錯工具**: Laravel Telescope (可選)

---

## 專案結構

```
erp-dev/
├── app/
│   ├── Console/
│   │   └── Commands/          # Artisan 命令
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/          # 認證控制器
│   │   │   ├── Backend/       # 後台控制器
│   │   │   └── Frontend/      # 前台控制器
│   │   ├── Middleware/        # 自訂中介層
│   │   ├── Requests/          # 表單驗證請求
│   │   └── Resources/         # API 資源
│   ├── Models/                # Eloquent 模型
│   ├── Observers/             # 模型觀察者
│   ├── Providers/             # 服務提供者
│   ├── Support/               # 支援類別
│   └── View/
│       ├── Components/        # Blade 元件
│       └── Composers/         # 視圖組合器
├── bootstrap/                 # 框架啟動檔案
├── config/                    # 設定檔案
├── database/
│   ├── factories/             # 模型工廠
│   ├── migrations/            # 資料庫遷移
│   └── seeders/               # 資料填充
├── docs/                      # 文件目錄
├── public/                    # 公開資源
│   └── css/
│       └── app.css            # 自訂樣式
├── resources/
│   └── views/
│       ├── auth/              # 認證頁面
│       ├── backend/           # 後台視圖
│       ├── frontend/          # 前台視圖
│       ├── components/        # Blade 元件
│       └── layouts/           # 版面配置
├── routes/
│   ├── api.php                # API 路由
│   ├── console.php            # 命令列路由
│   └── web.php                # Web 路由
├── storage/                   # 儲存目錄
├── tests/
│   ├── Feature/               # 功能測試
│   └── Unit/                  # 單元測試
├── vendor/                    # Composer 依賴
├── .env.example               # 環境變數範例
├── composer.json              # PHP 依賴定義
├── phpunit.xml                # PHPUnit 設定
└── README.md                  # 專案說明
```

---

## 核心模組

### 1. 公司組織管理模組

#### 功能說明
- 多公司管理（Companies）
- 部門層級管理（Departments）
- 職位定義（Positions）
- 員工資料管理（Employees）

#### 主要檔案
- **Models**: `Company.php`, `Department.php`, `Position.php`, `Employee.php`
- **Controllers**: 
  - `CompanyManagementController.php`
  - `DepartmentManagementController.php`
  - `PositionManagementController.php`
  - `EmployeeManagementController.php`
- **Migrations**:
  - `2024_01_01_100000_create_companies_table.php`
  - `2024_01_01_100100_create_departments_table.php`
  - `2024_01_01_100150_create_positions_table.php`
  - `2024_01_01_100200_create_employees_table.php`

#### 資料表結構

**companies 表**
- `id`: 主鍵
- `name`: 公司名稱
- `code`: 公司代碼
- `tax_id`: 統一編號
- `address`: 地址
- `phone`: 電話
- `is_active`: 啟用狀態
- `timestamps`

**departments 表**
- `id`: 主鍵
- `company_id`: 所屬公司
- `parent_id`: 父部門（支援層級）
- `name`: 部門名稱
- `code`: 部門代碼
- `lead_employee_id`: 部門主管
- `is_active`: 啟用狀態
- `timestamps`

**positions 表**
- `id`: 主鍵
- `company_id`: 所屬公司
- `department_id`: 所屬部門
- `name`: 職位名稱
- `code`: 職位代碼
- `level_id`: 職級
- `reference_salary`: 參考薪資
- `insurance_grade`: 保險級距
- `is_active`: 啟用狀態
- `timestamps`

**employees 表**
- `id`: 主鍵
- `user_id`: 關聯使用者帳號
- `company_id`: 所屬公司
- `department_id`: 所屬部門
- `position_id`: 職位
- `employee_number`: 員工編號
- `full_name`: 全名
- `id_number`: 身分證字號
- `birth_date`: 出生日期
- `hire_date`: 到職日期
- `salary_grade`: 薪資等級
- `labor_grade`: 勞工等級
- `is_indigenous`: 原住民身分
- `is_disabled`: 身心障礙身分
- `is_blocked`: 封鎖狀態
- `blocked_at`: 封鎖時間
- `blocked_reason`: 封鎖原因
- `timestamps`
- `softDeletes`

### 2. 出勤管理模組

#### 功能說明
- 員工打卡（上班/下班）
- 出勤記錄查詢
- 補登出勤記錄
- 出勤統計摘要

#### 主要檔案
- **Models**: 
  - `AttendanceLog.php`
  - `AttendanceSummary.php`
  - `AttendanceDevice.php`
- **Controllers**:
  - `Frontend/AttendanceController.php` (員工打卡)
  - `Backend/AttendanceManagementController.php` (後台管理)
- **Migration**: `2025_10_18_151134_create_attendance_tables.php`

#### 資料表結構

**attendance_logs 表**
- `id`: 主鍵
- `employee_id`: 員工 ID
- `recorded_at`: 打卡時間
- `type`: 打卡類型 (check-in/check-out)
- `device_id`: 打卡設備
- `ip_address`: IP 位址
- `remarks`: 備註
- `created_by`: 建立者
- `timestamps`

**attendance_summaries 表**
- `id`: 主鍵
- `employee_id`: 員工 ID
- `date`: 日期
- `first_check_in`: 首次打卡
- `last_check_out`: 最後打卡
- `total_hours`: 總工時
- `late_minutes`: 遲到分鐘數
- `early_leave_minutes`: 早退分鐘數
- `timestamps`

**attendance_devices 表**
- `id`: 主鍵
- `name`: 設備名稱
- `code`: 設備代碼
- `location`: 設備位置
- `is_active`: 啟用狀態
- `timestamps`

#### 路由
```php
// 前台打卡
POST /frontend/attendance/check-in    // 上班打卡
POST /frontend/attendance/check-out   // 下班打卡

// 後台管理
GET  /backend/attendance              // 出勤記錄列表
POST /backend/attendance              // 補登出勤記錄
```

### 3. 請假管理模組

#### 功能說明
- 假別設定與管理
- 員工請假申請
- 請假審核流程
- 假勤餘額管理

#### 主要檔案
- **Models**:
  - `LeaveType.php`
  - `LeaveRequest.php`
  - `LeaveBalance.php`
- **Controllers**:
  - `Frontend/EmployeeLeaveController.php` (員工申請)
  - `Backend/LeaveTypeManagementController.php` (假別管理)
  - `Backend/LeaveRequestManagementController.php` (審核管理)
- **Requests**:
  - `StoreLeaveTypeRequest.php`
  - `UpdateLeaveTypeRequest.php`
  - `SubmitLeaveRequest.php`

#### 資料表結構

**leave_types 表**
- `id`: 主鍵
- `company_id`: 所屬公司
- `code`: 假別代碼
- `name`: 假別名稱
- `default_days`: 預設天數
- `requires_approval`: 是否需審核
- `is_paid`: 是否支薪
- `is_active`: 啟用狀態
- `timestamps`

**leave_requests 表**
- `id`: 主鍵
- `employee_id`: 申請員工
- `leave_type_id`: 假別
- `start_date`: 開始日期
- `end_date`: 結束日期
- `start_time`: 開始時間
- `end_time`: 結束時間
- `total_days`: 總天數
- `reason`: 請假事由
- `status`: 狀態 (pending/approved/rejected/cancelled)
- `approved_by`: 審核者
- `approved_at`: 審核時間
- `remarks`: 審核備註
- `timestamps`

**leave_balances 表**
- `id`: 主鍵
- `employee_id`: 員工 ID
- `leave_type_id`: 假別
- `year`: 年度
- `total_days`: 總額度
- `used_days`: 已使用
- `remaining_days`: 剩餘額度
- `timestamps`

#### 請假狀態流程
```
pending (待審核)
  ├─> approved (已核准)
  ├─> rejected (已拒絕)
  └─> cancelled (已取消)
```

#### 路由
```php
// 前台申請
GET  /frontend/hr/leave-request       // 請假表單
POST /frontend/hr/leave-request       // 送出請假

// 後台管理
GET  /backend/leave-types             // 假別列表
POST /backend/leave-types             // 新增假別
PUT  /backend/leave-types/{id}        // 更新假別
DELETE /backend/leave-types/{id}      // 刪除假別

GET  /backend/leave-requests          // 請假審核列表
PUT  /backend/leave-requests/{id}     // 審核請假
```

### 4. 薪資管理模組

#### 功能說明
- 薪資期間管理
- 薪資項目設定
- 薪資計算批次
- 保險級距管理
- 績效考核記錄
- 獎懲記錄

#### 主要檔案
- **Models**:
  - `PayrollPeriod.php`
  - `SalaryComponent.php`
  - `PayrollRun.php`
  - `PayrollEntry.php`
  - `PayrollEntryComponent.php`
  - `InsuranceBracket.php`
  - `PerformanceReview.php`
  - `RewardRecord.php`
- **Controllers**:
  - `Backend/PayrollController.php`
  - `Backend/InsuranceBracketController.php`
- **Support**:
  - `InsuranceSchedule.php`
  - `InsuranceContributionSummary.php`
- **Migrations**:
  - `2025_10_18_151135_create_payroll_tables.php`
  - `2025_10_19_150000_create_insurance_brackets_table.php`

#### 資料表結構

**payroll_periods 表**
- `id`: 主鍵
- `company_id`: 所屬公司
- `name`: 期間名稱
- `start_date`: 開始日期
- `end_date`: 結束日期
- `payment_date`: 發放日期
- `status`: 狀態 (draft/processing/completed/cancelled)
- `timestamps`

**salary_components 表**
- `id`: 主鍵
- `company_id`: 所屬公司
- `code`: 項目代碼
- `name`: 項目名稱
- `type`: 類型 (earning/deduction)
- `is_taxable`: 是否計稅
- `is_active`: 啟用狀態
- `timestamps`

**payroll_runs 表**
- `id`: 主鍵
- `payroll_period_id`: 薪資期間
- `name`: 批次名稱
- `total_employees`: 總員工數
- `total_amount`: 總金額
- `status`: 狀態
- `processed_at`: 處理時間
- `processed_by`: 處理者
- `timestamps`

**payroll_entries 表**
- `id`: 主鍵
- `payroll_run_id`: 薪資批次
- `employee_id`: 員工 ID
- `base_salary`: 本薪
- `total_earnings`: 總收入
- `total_deductions`: 總扣款
- `net_salary`: 實發薪資
- `payment_date`: 發放日期
- `status`: 狀態
- `timestamps`

**payroll_entry_components 表**
- `id`: 主鍵
- `payroll_entry_id`: 薪資條目
- `salary_component_id`: 薪資項目
- `amount`: 金額
- `remarks`: 備註
- `timestamps`

**insurance_brackets 表**
- `id`: 主鍵
- `insurance_type`: 保險類型 (labor/health/pension)
- `grade`: 級距
- `monthly_salary_min`: 月薪下限
- `monthly_salary_max`: 月薪上限
- `insured_amount`: 投保金額
- `employee_rate`: 員工負擔比率
- `employer_rate`: 雇主負擔比率
- `government_rate`: 政府負擔比率
- `effective_date`: 生效日期
- `expiry_date`: 失效日期
- `timestamps`

**performance_reviews 表**
- `id`: 主鍵
- `employee_id`: 員工 ID
- `reviewer_id`: 考核者
- `review_period`: 考核期間
- `score`: 分數
- `comments`: 評語
- `reviewed_at`: 考核日期
- `timestamps`

**reward_records 表**
- `id`: 主鍵
- `employee_id`: 員工 ID
- `type`: 類型 (reward/penalty)
- `category`: 類別
- `amount`: 金額
- `reason`: 原因
- `recorded_at`: 記錄日期
- `recorded_by`: 記錄者
- `timestamps`

#### 路由
```php
GET  /backend/payroll                 // 薪資總覽
POST /backend/payroll/periods         // 新增薪資期間
POST /backend/payroll/runs            // 建立薪資批次
GET  /backend/insurance-brackets      // 保險級距查詢
```

### 5. 權限控制模組

#### 功能說明
- 角色定義與管理
- 權限分配
- 公司範圍權限
- 操作審計日誌

#### 主要檔案
- **Models**:
  - `Role.php`
  - `Permission.php`
  - `UserRole.php`
  - `RoleScope.php`
  - `ActivityLog.php`
- **Middleware**: `CheckPermission.php`
- **Seeders**: `AccessControlSeeder.php`
- **Migration**: `2024_01_01_100400_create_access_control_tables.php`

#### 資料表結構

**roles 表**
- `id`: 主鍵
- `name`: 角色名稱
- `code`: 角色代碼
- `description`: 描述
- `is_system`: 系統角色
- `timestamps`

**permissions 表**
- `id`: 主鍵
- `name`: 權限名稱
- `code`: 權限代碼
- `module`: 所屬模組
- `description`: 描述
- `timestamps`

**permission_role 表** (多對多樞紐表)
- `role_id`: 角色 ID
- `permission_id`: 權限 ID

**user_roles 表**
- `id`: 主鍵
- `user_id`: 使用者 ID
- `role_id`: 角色 ID
- `timestamps`

**role_scopes 表** (公司範圍權限)
- `id`: 主鍵
- `user_role_id`: 使用者角色
- `company_id`: 公司 ID
- `timestamps`

**activity_logs 表**
- `id`: 主鍵
- `user_id`: 操作者
- `action`: 動作
- `model_type`: 模型類型
- `model_id`: 模型 ID
- `old_values`: 舊值 (JSON)
- `new_values`: 新值 (JSON)
- `ip_address`: IP 位址
- `user_agent`: 使用者代理
- `timestamps`

#### 預設角色與權限

**系統角色**
1. **System Owner** (系統擁有者)
   - 完整系統管理權限
   - 跨公司操作權限

2. **Company Manager** (公司管理者)
   - 公司內完整管理權限
   - 人事、薪資、考勤管理

3. **HR Manager** (人資主管)
   - 員工資料管理
   - 出勤假勤管理
   - 薪資檢視權限

4. **Department Manager** (部門主管)
   - 部門員工管理
   - 請假審核權限
   - 出勤查詢權限

5. **Employee** (一般員工)
   - 個人資料查看
   - 打卡、請假申請
   - 薪資查詢

**權限列表**
- `backend.access` - 後台存取
- `company.manage` - 公司管理
- `department.manage` - 部門管理
- `employee.manage` - 員工管理
- `attendance.manage` - 出勤管理
- `leave.manage` - 請假管理
- `payroll.view` - 薪資檢視
- `payroll.manage` - 薪資管理
- `frontend.portal.access` - 員工入口存取
- `frontend.leave.submit` - 請假申請

---

## 數據庫架構

### 資料表關聯圖

```
users (使用者)
  ├─ employees (員工資料)
  │    ├─ companies (公司)
  │    ├─ departments (部門)
  │    ├─ positions (職位)
  │    │    └─ position_levels (職級)
  │    ├─ employee_contacts (聯絡資訊)
  │    ├─ employee_addresses (地址資訊)
  │    ├─ employment_contracts (僱傭合約)
  │    ├─ attendance_logs (出勤記錄)
  │    ├─ attendance_summaries (出勤摘要)
  │    ├─ leave_requests (請假申請)
  │    ├─ leave_balances (假勤餘額)
  │    ├─ payroll_entries (薪資條目)
  │    ├─ performance_reviews (績效考核)
  │    └─ reward_records (獎懲記錄)
  └─ user_roles (使用者角色)
       ├─ roles (角色)
       │    └─ permissions (權限)
       └─ role_scopes (角色範圍)

companies (公司)
  ├─ departments (部門)
  ├─ positions (職位)
  ├─ employees (員工)
  ├─ leave_types (假別)
  ├─ salary_components (薪資項目)
  └─ payroll_periods (薪資期間)
       └─ payroll_runs (薪資批次)
            └─ payroll_entries (薪資條目)
```

### 索引策略
- 所有外鍵欄位建立索引
- 經常查詢的欄位 (如 `employee_number`, `id_number`) 建立唯一索引
- 時間欄位 (如 `recorded_at`, `created_at`) 建立索引
- 複合索引用於多條件查詢 (如 `employee_id + date`)

---

## 權限與角色系統

### 中介層 (Middleware)

```php
// 檢查權限
Route::middleware(['auth', 'permission:backend.access'])

// 範例
Route::get('/backend/payroll', [PayrollController::class, 'index'])
    ->middleware('permission:payroll.view');
```

### 權限檢查方法

```php
// 在控制器中
if (auth()->user()->hasPermission('payroll.manage')) {
    // 執行操作
}

// 在 Blade 中
@can('payroll.manage')
    <button>編輯薪資</button>
@endcan

// 檢查公司範圍權限
$user->hasCompanyAccess($companyId);
```

### 公司範圍權限

使用 `role_scopes` 表限制使用者只能操作特定公司的資料：

```php
// 取得使用者可存取的公司
$companies = auth()->user()->accessibleCompanies();

// 在查詢中套用範圍
Employee::whereIn('company_id', $companies->pluck('id'))->get();
```

---

## 路由設計

### 認證路由
```php
GET  /login              // 登入頁面
POST /login              // 處理登入
POST /logout             // 登出
```

### 前台路由 (Frontend)
```php
// 首頁
GET  /                                    // 首頁
GET  /frontend                            // 員工入口

// 員工自助中心 (需 frontend.portal.access)
GET  /frontend/hr                         // 自助中心首頁
POST /frontend/attendance/check-in        // 上班打卡
POST /frontend/attendance/check-out       // 下班打卡

// 請假申請 (需 frontend.leave.submit)
GET  /frontend/hr/leave-request           // 請假表單
POST /frontend/hr/leave-request           // 送出請假
```

### 後台路由 (Backend)
```php
// 總覽 (需 backend.access)
GET  /backend                             // 後台總覽
GET  /backend/hr                          // 人資總覽

// 公司管理
GET     /backend/companies                // 公司列表
GET     /backend/companies/create         // 新增表單
POST    /backend/companies                // 儲存新公司
GET     /backend/companies/{id}/edit      // 編輯表單
PUT     /backend/companies/{id}           // 更新公司
DELETE  /backend/companies/{id}           // 刪除公司

// 部門管理
GET     /backend/departments              // 部門列表
POST    /backend/departments              // 新增部門
PUT     /backend/departments/{id}         // 更新部門
DELETE  /backend/departments/{id}         // 刪除部門

// 職位管理
GET     /backend/positions                // 職位列表
POST    /backend/positions                // 新增職位
PUT     /backend/positions/{id}           // 更新職位
DELETE  /backend/positions/{id}           // 刪除職位

// 員工管理
GET     /backend/employees                // 員工列表
GET     /backend/employees/create         // 新增表單
POST    /backend/employees                // 新增員工
GET     /backend/employees/{id}/edit      // 編輯表單
PUT     /backend/employees/{id}           // 更新員工
DELETE  /backend/employees/{id}           // 刪除員工
POST    /backend/employees/{id}/block     // 封鎖員工
POST    /backend/employees/{id}/unblock   // 解封員工

// 出勤管理 (需 attendance.manage)
GET     /backend/attendance               // 出勤記錄
POST    /backend/attendance               // 補登記錄

// 假別管理 (需 leave.manage)
GET     /backend/leave-types              // 假別列表
POST    /backend/leave-types              // 新增假別
PUT     /backend/leave-types/{id}         // 更新假別
DELETE  /backend/leave-types/{id}         // 刪除假別

// 請假審核 (需 leave.manage)
GET     /backend/leave-requests           // 請假列表
PUT     /backend/leave-requests/{id}      // 審核請假

// 薪資管理 (需 payroll.view)
GET     /backend/payroll                  // 薪資總覽
POST    /backend/payroll/periods          // 新增期間 (需 payroll.manage)
POST    /backend/payroll/runs             // 新增批次 (需 payroll.manage)

// 保險級距 (需 payroll.view)
GET     /backend/insurance-brackets       // 級距查詢
```

---

## 前端架構

### Blade 版面配置

**主版面**: `resources/views/layouts/app.blade.php`

```blade
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ERP 系統')</title>
    
    <!-- Bootstrap 5.3.x -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- 自訂樣式 -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    <!-- 導航列 -->
    @include('components.navbar')
    
    <!-- 主要內容 -->
    <main class="container-fluid py-4">
        @yield('content')
    </main>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
```

### 前台頁面範例

**出勤打卡頁面**: `resources/views/frontend/index.blade.php`
- 顯示目前時間
- 上班/下班打卡按鈕
- 最近 10 筆打卡記錄
- 使用 AJAX 送出表單

**請假申請頁面**: `resources/views/frontend/hr/leave-request.blade.php`
- 假別選擇
- 日期範圍選擇器
- 請假事由輸入
- 表單驗證

### 後台頁面範例

**員工管理頁面**: `resources/views/backend/employees/index.blade.php`
- 資料表格 (搜尋、分頁)
- 新增/編輯/刪除按鈕
- 封鎖/解封功能
- 匯出功能

**出勤管理頁面**: `resources/views/backend/attendance/index.blade.php`
- 員工篩選器
- 日期範圍選擇
- 打卡記錄表格
- 補登表單

### 自訂樣式

**public/css/app.css**
```css
/* 導航列樣式 */
.navbar-brand {
    font-weight: bold;
}

/* 側邊欄 */
.sidebar {
    min-height: calc(100vh - 56px);
    background-color: #f8f9fa;
}

/* 卡片樣式 */
.card-hover:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
}

/* 表格樣式 */
.table-action-buttons .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

/* 狀態標籤 */
.badge-pending {
    background-color: #ffc107;
}

.badge-approved {
    background-color: #28a745;
}

.badge-rejected {
    background-color: #dc3545;
}
```

### JavaScript 互動

使用 jQuery 處理常見互動：

```javascript
// AJAX 表單送出
$('#attendance-form').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            // 顯示成功訊息
            alert('打卡成功！');
            // 重新載入記錄
            location.reload();
        },
        error: function(xhr) {
            alert('打卡失敗，請稍後再試。');
        }
    });
});

// 日期範圍選擇器
$('#date-range').daterangepicker({
    locale: {
        format: 'YYYY-MM-DD'
    }
});

// 確認刪除
$('.delete-btn').on('click', function() {
    return confirm('確定要刪除此項目嗎？');
});
```

---

## 開發環境設置

### 系統需求
- PHP >= 8.2
- Composer
- SQLite 或 MySQL
- Git

### 安裝步驟

#### 1. 克隆專案
```bash
git clone <repository-url> erp-dev
cd erp-dev
```

#### 2. 安裝依賴
```bash
composer install
```

#### 3. 環境設定
```bash
# 複製環境變數檔案
cp .env.example .env

# 產生應用程式金鑰
php artisan key:generate
```

#### 4. 設定資料庫

編輯 `.env` 檔案：

**使用 SQLite (開發環境)**
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite
```

**使用 MySQL (生產環境)**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=erp_dev
DB_USERNAME=root
DB_PASSWORD=
```

#### 5. 執行遷移與填充資料
```bash
# 建立資料表並填充測試資料
php artisan migrate --seed

# 或分開執行
php artisan migrate
php artisan db:seed
```

#### 6. 啟動開發伺服器
```bash
php artisan serve
```

預設網址：http://localhost:8000

### 資料填充說明

執行 `php artisan db:seed` 會依序執行：

1. **AccessControlSeeder** - 建立角色與權限
2. **AdminUserSeeder** - 建立系統管理員帳號
3. **PositionLevelSeeder** - 建立職級資料
4. **InsuranceBracketSeeder** - 建立保險級距資料
5. **LeaveTypeSeeder** - 建立假別資料
6. **CompanyDataSeeder** - 建立範例公司、部門、職位
7. **FrontendUserSeeder** - 建立前台測試帳號

---

## 測試帳號

### 後台管理帳號

| 帳號 | 密碼 | 角色 | 權限 |
|------|------|------|------|
| admin@erp.local | password | System Owner | 完整系統管理權限 |

**說明**：
- 由 `AdminUserSeeder` 建立
- 具備所有後台管理權限
- 可跨公司操作
- **請登入後立即修改密碼**

### 前台員工帳號

| 帳號 | 密碼 | 角色 | 所屬公司 | 部門 | 職位 |
|------|------|------|----------|------|------|
| employee1@erp.local | password | Employee | Alpha Manufacturing | 研發部 | 資深工程師 |
| employee2@erp.local | password | Employee | Alpha Manufacturing | 業務部 | 業務專員 |
| manager@erp.local | password | Company Manager | Alpha Manufacturing | 管理部 | 總經理 |

**說明**：
- 由 `FrontendUserSeeder` 建立
- 具備前台打卡、請假權限
- Manager 帳號額外具備審核權限

### 範例公司資料

#### Alpha Manufacturing (製造業)
- **部門**：
  - 管理部 (總經理、特助)
  - 研發部 (研發經理、資深工程師、工程師)
  - 生產部 (生產經理、組長、作業員)
  - 業務部 (業務經理、業務專員)
- **員工數**：15 人

#### Beta Logistics (物流業)
- **部門**：
  - 管理部 (總經理)
  - 營運部 (營運經理、調度員)
  - 倉儲部 (倉管、理貨員)
- **員工數**：10 人

---

## 開發規範

### 程式碼風格

#### PSR-12 標準
- 四空格縮排（不使用 Tab）
- 類別採用 PascalCase
- 方法採用 camelCase
- 常數採用 UPPER_SNAKE_CASE

#### Laravel 慣例
- 模型命名：單數名詞 (User, Employee)
- 控制器命名：複數 + Controller (UsersController)
- 資料表命名：複數蛇底式 (users, employees)
- 欄位命名：蛇底式 (first_name, created_at)

### 格式化工具

```bash
# 執行 Laravel Pint 格式化
./vendor/bin/pint

# 檢查但不修改
./vendor/bin/pint --test
```

### Git 提交規範

#### Commit Message 格式
```
<type>: <subject>

<body>

<footer>
```

**類型 (type)**：
- `feat`: 新功能
- `fix`: 錯誤修復
- `docs`: 文件更新
- `style`: 程式碼格式調整
- `refactor`: 重構
- `test`: 測試相關
- `chore`: 建置工具或輔助工具變動

**範例**：
```
feat: add employee blocking functionality

Implement block/unblock features for employee management:
- Add blocked_at, blocked_reason columns to employees table
- Create block/unblock routes and controller methods
- Add UI buttons in employee list page

Closes #42
```

#### 分支策略

```
main (生產環境)
  └─ develop (開發環境)
       ├─ feature/attendance-module (功能分支)
       ├─ feature/leave-management (功能分支)
       └─ bugfix/fix-login-redirect (修復分支)
```

**流程**：
1. 從 `develop` 建立 feature 分支
2. 完成開發並通過測試
3. 建立 Pull Request 到 `develop`
4. Code Review 通過後合併
5. 定期從 `develop` 合併到 `main`

### 測試規範

#### 執行測試
```bash
# 執行所有測試
php artisan test

# 執行特定測試檔案
php artisan test tests/Feature/EmployeeAccessTest.php

# 顯示詳細輸出
php artisan test --parallel --coverage
```

#### 測試命名

```php
// 功能測試
class EmployeeManagementTest extends TestCase
{
    public function test_admin_can_create_employee(): void
    {
        // Arrange
        $admin = User::factory()->create();
        $admin->givePermission('employee.manage');
        
        // Act
        $response = $this->actingAs($admin)->post('/backend/employees', [
            'employee_number' => 'E001',
            'full_name' => 'Test Employee',
            // ...
        ]);
        
        // Assert
        $response->assertStatus(302);
        $this->assertDatabaseHas('employees', [
            'employee_number' => 'E001',
        ]);
    }
    
    public function test_employee_cannot_access_backend(): void
    {
        $employee = User::factory()->create();
        
        $response = $this->actingAs($employee)->get('/backend');
        
        $response->assertStatus(403);
    }
}
```

#### 測試組織

```
tests/
├── Feature/
│   ├── Auth/
│   │   └── LoginTest.php
│   ├── Backend/
│   │   ├── CompanyManagementTest.php
│   │   ├── EmployeeManagementTest.php
│   │   └── AttendanceManagementTest.php
│   └── Frontend/
│       ├── AttendanceTest.php
│       └── LeaveRequestTest.php
└── Unit/
    ├── Models/
    │   └── EmployeeTest.php
    └── Services/
        └── PayrollCalculatorTest.php
```

### 安全性考量

#### 1. 防止 SQL Injection
```php
// ✅ 正確：使用參數綁定
Employee::where('employee_number', $number)->first();

// ❌ 錯誤：直接拼接
DB::select("SELECT * FROM employees WHERE employee_number = '$number'");
```

#### 2. 防止 XSS
```blade
{{-- ✅ 正確：自動跳脫 --}}
{{ $employee->full_name }}

{{-- ❌ 錯誤：原始輸出 --}}
{!! $employee->full_name !!}
```

#### 3. CSRF 保護
```blade
<form method="POST" action="/backend/employees">
    @csrf
    <!-- 表單欄位 -->
</form>
```

#### 4. Mass Assignment 保護
```php
class Employee extends Model
{
    protected $fillable = [
        'employee_number',
        'full_name',
        // 明確列出可填充欄位
    ];
    
    protected $guarded = [
        'id',
        'is_blocked',
        // 保護敏感欄位
    ];
}
```

#### 5. 權限檢查
```php
// 在控制器中
public function destroy(Employee $employee)
{
    // 檢查權限
    abort_unless(auth()->user()->can('employee.manage'), 403);
    
    // 檢查公司範圍
    abort_unless(
        auth()->user()->hasCompanyAccess($employee->company_id),
        403
    );
    
    $employee->delete();
    
    return redirect()->route('backend.employees.index');
}
```

---

## API 文件

### 認證

使用 Laravel Sanctum 進行 API 認證：

```bash
# 取得 Token
POST /api/login
Content-Type: application/json

{
    "email": "admin@erp.local",
    "password": "password"
}

# 回應
{
    "token": "1|abc123...",
    "user": {
        "id": 1,
        "name": "Admin",
        "email": "admin@erp.local"
    }
}
```

使用 Token：
```bash
GET /api/employees
Authorization: Bearer 1|abc123...
```

### API 端點

#### 員工資源
```bash
# 取得員工列表
GET /api/employees
Query Parameters:
  - page: 頁碼 (預設 1)
  - per_page: 每頁筆數 (預設 15)
  - search: 搜尋關鍵字
  - company_id: 公司 ID
  - department_id: 部門 ID

# 取得單一員工
GET /api/employees/{id}

# 建立員工
POST /api/employees
Content-Type: application/json

{
    "employee_number": "E001",
    "full_name": "張三",
    "company_id": 1,
    "department_id": 1,
    "position_id": 1,
    "hire_date": "2025-01-01"
}

# 更新員工
PUT /api/employees/{id}

# 刪除員工
DELETE /api/employees/{id}
```

#### 出勤資源
```bash
# 打卡
POST /api/attendance/check-in
POST /api/attendance/check-out

# 取得出勤記錄
GET /api/attendance/logs?employee_id={id}&start_date={date}&end_date={date}

# 取得出勤摘要
GET /api/attendance/summary?employee_id={id}&month={YYYY-MM}
```

#### 請假資源
```bash
# 送出請假
POST /api/leave-requests
{
    "leave_type_id": 1,
    "start_date": "2025-01-10",
    "end_date": "2025-01-12",
    "reason": "家庭事務"
}

# 取得請假記錄
GET /api/leave-requests?status=pending

# 審核請假
PUT /api/leave-requests/{id}/approve
PUT /api/leave-requests/{id}/reject
{
    "remarks": "核准/拒絕原因"
}
```

### 錯誤處理

API 錯誤回應格式：

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "employee_number": [
            "員工編號已存在"
        ],
        "email": [
            "Email 格式不正確"
        ]
    }
}
```

HTTP 狀態碼：
- `200` - 成功
- `201` - 建立成功
- `204` - 刪除成功
- `400` - 請求錯誤
- `401` - 未認證
- `403` - 權限不足
- `404` - 資源不存在
- `422` - 驗證失敗
- `500` - 伺服器錯誤

---

## 部署指南

### 生產環境設定

#### 1. 環境變數設定

編輯 `.env` 檔案：

```env
APP_NAME="ERP 系統"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://erp.example.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=erp_production
DB_USERNAME=erp_user
DB_PASSWORD=secure_password

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

#### 2. 優化設定

```bash
# 清除並快取設定
php artisan config:cache

# 快取路由
php artisan route:cache

# 快取視圖
php artisan view:cache

# 優化 Composer 自動載入
composer install --optimize-autoloader --no-dev
```

#### 3. 資料庫遷移

```bash
# 執行遷移（注意：生產環境建議先備份）
php artisan migrate --force

# 填充基礎資料（不包含測試資料）
php artisan db:seed --class=AccessControlSeeder
php artisan db:seed --class=PositionLevelSeeder
php artisan db:seed --class=InsuranceBracketSeeder
php artisan db:seed --class=LeaveTypeSeeder
```

#### 4. 檔案權限設定

```bash
# 設定 storage 和 bootstrap/cache 可寫入
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Web 伺服器設定

#### Nginx 設定範例

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name erp.example.com;
    root /var/www/erp-dev/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### Apache 設定範例

確保 `.htaccess` 檔案存在於 `public/` 目錄：

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### SSL 憑證設定

使用 Let's Encrypt：

```bash
# 安裝 Certbot
sudo apt install certbot python3-certbot-nginx

# 取得憑證
sudo certbot --nginx -d erp.example.com

# 自動更新設定
sudo certbot renew --dry-run
```

### 背景任務設定

#### Cron 排程

編輯 crontab：
```bash
crontab -e
```

新增：
```
* * * * * cd /var/www/erp-dev && php artisan schedule:run >> /dev/null 2>&1
```

#### Queue Worker (Supervisor)

建立設定檔 `/etc/supervisor/conf.d/erp-worker.conf`：

```ini
[program:erp-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/erp-dev/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/erp-dev/storage/logs/worker.log
stopwaitsecs=3600
```

啟動：
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start erp-worker:*
```

### 備份策略

#### 資料庫備份

建立備份腳本 `backup-db.sh`：

```bash
#!/bin/bash

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/erp"
DB_NAME="erp_production"
DB_USER="erp_user"
DB_PASS="secure_password"

mkdir -p $BACKUP_DIR

mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_backup_$DATE.sql.gz

# 保留最近 30 天的備份
find $BACKUP_DIR -name "db_backup_*.sql.gz" -mtime +30 -delete
```

設定每日自動備份：
```bash
0 2 * * * /path/to/backup-db.sh
```

#### 檔案備份

```bash
# 備份整個專案（排除 vendor 和 node_modules）
tar -czf erp-backup-$(date +%Y%m%d).tar.gz \
    --exclude='vendor' \
    --exclude='node_modules' \
    --exclude='storage/logs' \
    /var/www/erp-dev
```

### 監控與日誌

#### 應用程式日誌

日誌位置：`storage/logs/laravel.log`

設定日誌輪替 `/etc/logrotate.d/erp`：

```
/var/www/erp-dev/storage/logs/*.log {
    daily
    rotate 14
    compress
    delaycompress
    notifempty
    missingok
    create 0644 www-data www-data
}
```

#### 效能監控

安裝 Laravel Telescope (開發環境)：

```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

在 `.env` 中啟用：
```env
TELESCOPE_ENABLED=true
```

存取：`https://erp.example.com/telescope`

---

## 後續開發計劃

### 短期目標 (1-3 個月)

#### 1. 出勤模組增強
- [ ] 實作排程自動計算每日出勤摘要
- [ ] 加入遲到/早退規則設定
- [ ] 支援彈性工時制度
- [ ] 整合實體打卡機 API
- [ ] 異常出勤通知機制

#### 2. 請假模組完善
- [ ] 實作請假審核通知 (Email/Line)
- [ ] 支援代理人設定
- [ ] 批次核准功能
- [ ] 請假統計報表
- [ ] 特休自動結轉

#### 3. 薪資計算功能
- [ ] 建立薪資計算引擎
- [ ] 整合出勤、請假資料
- [ ] 支援各類加給/扣款項目
- [ ] 勞健保自動計算
- [ ] 所得稅自動扣繳
- [ ] 薪資單產生與發送

#### 4. 權限系統優化
- [ ] 資料層級權限 (Row-Level Security)
- [ ] 審核流程引擎
- [ ] 委派權限功能
- [ ] 權限稽核日誌

### 中期目標 (3-6 個月)

#### 5. 招募管理模組
- [ ] 職缺發佈管理
- [ ] 應徵者資料庫
- [ ] 面試排程系統
- [ ] 錄取流程追蹤

#### 6. 訓練發展模組
- [ ] 訓練課程管理
- [ ] 員工受訓記錄
- [ ] 證照管理
- [ ] 訓練需求分析

#### 7. 績效管理模組
- [ ] 目標設定 (OKR/KPI)
- [ ] 定期考核流程
- [ ] 360 度評估
- [ ] 績效面談記錄

#### 8. 報表系統
- [ ] 人力統計報表
- [ ] 出勤分析報表
- [ ] 薪資成本分析
- [ ] 自訂報表建立器
- [ ] 匯出 Excel/PDF

### 長期目標 (6-12 個月)

#### 9. 行動應用
- [ ] 開發 iOS App
- [ ] 開發 Android App
- [ ] 行動打卡功能
- [ ] 推播通知

#### 10. 進階功能
- [ ] AI 履歷篩選
- [ ] 排班優化演算法
- [ ] 離職風險預測
- [ ] 薪資市場分析

#### 11. 整合功能
- [ ] 與會計系統整合
- [ ] 與門禁系統整合
- [ ] 與 Google Calendar 整合
- [ ] 與 Slack/Teams 整合

#### 12. 多國化支援
- [ ] 多語系介面
- [ ] 多時區支援
- [ ] 各國勞動法規適配
- [ ] 多幣別薪資

### 技術債務與優化

#### 效能優化
- [ ] 資料庫查詢優化
- [ ] Redis 快取策略
- [ ] 前端資源最小化
- [ ] CDN 部署

#### 程式碼品質
- [ ] 提高測試覆蓋率至 80%+
- [ ] 重構遺留程式碼
- [ ] 建立 CI/CD Pipeline
- [ ] 自動化部署流程

#### 文件完善
- [ ] API 文件自動生成
- [ ] 使用者操作手冊
- [ ] 開發者指南
- [ ] 架構設計文件

---

## 附錄

### A. 常見問題 (FAQ)

**Q1: 如何重設管理員密碼？**

A: 使用 tinker 重設：
```bash
php artisan tinker
>>> $user = User::where('email', 'admin@erp.local')->first();
>>> $user->password = bcrypt('new-password');
>>> $user->save();
```

**Q2: 如何清除快取？**

A: 執行清除指令：
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

**Q3: 測試環境資料如何重置？**

A: 使用 migrate:fresh：
```bash
php artisan migrate:fresh --seed
```

**Q4: 如何新增自訂權限？**

A: 在 `AccessControlSeeder` 中新增，然後重新 seed：
```php
Permission::create([
    'code' => 'custom.permission',
    'name' => '自訂權限',
    'module' => 'custom',
]);
```

**Q5: 如何修改預設公司資料？**

A: 編輯 `CompanyDataSeeder.php` 後重新執行：
```bash
php artisan db:seed --class=CompanyDataSeeder
```

### B. 資料字典

#### 員工狀態碼
- `active`: 在職
- `on_leave`: 留職停薪
- `resigned`: 已離職
- `retired`: 退休
- `terminated`: 解僱

#### 請假狀態碼
- `pending`: 待審核
- `approved`: 已核准
- `rejected`: 已拒絕
- `cancelled`: 已取消

#### 薪資狀態碼
- `draft`: 草稿
- `processing`: 計算中
- `completed`: 已完成
- `paid`: 已發放
- `cancelled`: 已取消

#### 打卡類型
- `check-in`: 上班打卡
- `check-out`: 下班打卡
- `break-out`: 休息開始
- `break-in`: 休息結束

### C. 保險級距對照表

參考來源：勞動部公告（2023 年）

| 級距 | 月投保薪資 | 勞保費（員工） | 健保費（員工） |
|------|-----------|---------------|---------------|
| 1 | 27,470 | 687 | 412 |
| 2 | 28,800 | 720 | 432 |
| 3 | 30,300 | 758 | 455 |
| 4 | 31,800 | 795 | 477 |
| 5 | 33,300 | 833 | 500 |
| ... | ... | ... | ... |

完整資料由 `InsuranceBracketSeeder` 填充。

### D. 職級對照表

| 代碼 | 名稱 | 等級 |
|------|------|------|
| P01 | 總裁 | 1 |
| P02 | 總經理 | 2 |
| P03 | 副總經理 | 3 |
| P04 | 協理 | 4 |
| P05 | 部長/經理 | 5 |
| P06 | 副理 | 6 |
| P07 | 科長/課長 | 7 |
| P08 | 主任 | 8 |
| P09 | 資深專員 | 9 |
| P10 | 專員/職員 | 10 |
| P11 | 助理 | 11 |

### E. 聯絡資訊

- **專案負責人**: [Your Name]
- **Email**: [your.email@example.com]
- **文件版本**: 1.0.0
- **最後更新**: 2025-10-22

---

## 變更歷史

| 版本 | 日期 | 說明 |
|------|------|------|
| 1.0.0 | 2025-10-22 | 初始版本，包含完整專案文件 |

---

**文件結束**

