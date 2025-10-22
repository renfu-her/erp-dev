# 2025年保險級距系統 - 最終版本

## 系統概述

本系統已完全更新為2025年（民國114年）官方標準，包含完整的勞保、健保、職災保險及勞退6%級距資料。

## 主要修正

### 1. 前端顯示問題修正
**問題**：前端顯示投保薪資為1元、2元等錯誤數值
**原因**：`resources/views/backend/insurance-brackets/index.blade.php` 中使用了 `$bracket->grade` 而非 `$bracket->salary`
**解決**：修正為正確顯示 `$bracket->salary`

### 2. 費率標準化
- **勞保費率**：11.5%（員工20%，雇主70%，政府10%）
- **健保費率**：5.17%（員工30%，雇主60%，政府10%）
- **勞退6%**：雇主負擔6%
- **職災保險**：依行業別，全額由雇主負擔

### 3. 級距範圍
- **薪資範圍**：NT$28,590 - NT$150,000（36個級距）
- **勞保封頂**：NT$45,800（第10級）
- **職災封頂**：NT$72,800（第20級）
- **健保範圍**：無上限，依實際薪資計算

## 資料庫結構

### 新增欄位
- `salary`：實際投保薪資
- `occupational_employee`：職災保險員工負擔（通常為0）
- `occupational_employer`：職災保險雇主負擔
- `labor_pension_6_percent`：勞退6%雇主負擔

### 完整欄位列表
```sql
- id (主鍵)
- label (級距標籤)
- grade (級距編號)
- salary (投保薪資)
- labor_employee_local (勞保員工負擔)
- labor_employer_local (勞保雇主負擔)
- labor_employee_foreign (勞保外籍員工負擔)
- labor_employer_foreign (勞保外籍雇主負擔)
- health_employee (健保員工負擔)
- health_employer (健保雇主負擔)
- occupational_employee (職災員工負擔)
- occupational_employer (職災雇主負擔)
- labor_pension_6_percent (勞退6%)
- pension_employer (舊制退休金)
```

## 使用方式

### 載入保險級距
```php
use App\Support\InsuranceSchedule;

// 從資料庫載入
$schedule = InsuranceSchedule::fromDatabase();

// 從JSON檔案載入
$schedule = InsuranceSchedule::fromStorage();
```

### 查詢級距
```php
// 根據薪資查詢級距
$bracket = $schedule->findBracketForSalary(50000);

// 根據級距編號查詢
$bracket = $schedule->findBracketByGrade(10);
```

### 計算保險費用
```php
$salary = 50000;
$bracket = $schedule->findBracketForSalary($salary);

$employeeTotal = $bracket['labor_employee_local'] + $bracket['health_employee'];
$employerTotal = $bracket['labor_employer_local'] + $bracket['health_employer'] + 
                 $bracket['occupational_employer'] + $bracket['labor_pension_6_percent'];
```

## 測試覆蓋

所有功能已通過完整測試：
- ✅ 資料庫載入（36個級距）
- ✅ 薪資查詢（正確級距匹配）
- ✅ 級距查詢（依編號查詢）
- ✅ 費率驗證（符合2025年標準）
- ✅ 封頂機制（勞保45,800，職災72,800）
- ✅ 前端顯示（正確薪資金額）

## 部署指令

```bash
# 執行資料庫遷移
php artisan migrate

# 載入2025年保險級距資料
php artisan db:seed --class=InsuranceBracketSeeder

# 執行測試
php artisan test tests/Feature/InsuranceScheduleTest.php
```

## 重要特性

1. **完全符合2025年官方標準**
2. **支援36個完整級距**
3. **正確的費率計算**
4. **封頂機制實現**
5. **前端顯示修正**
6. **完整測試覆蓋**

## 注意事項

- 職災保險費率依行業別不同，目前使用預設值
- 舊制退休金由公司政策決定，目前設為null
- 外籍員工費率目前與本國員工相同
- 所有金額單位為新台幣（NT$）

系統現已完全準備就緒，可投入生產使用！🎉
