# HR Module Additions

## Frontend Stack
- Blade 版面改採 Bootstrap 5 + jQuery（CDN 載入），所有頁面共享 `resources/views/layouts/app.blade.php`。
- 自訂樣式集中於 `public/css/app.css`，無需再執行 npm / Vite。
- 路由透過 `permission` middleware 控管：前臺需要 `frontend.portal.access` / `frontend.leave.submit` 權限。
- 後台入口 `/backend/*` 需登入並具備 `backend.access`。
- 登入頁：`/login`（`Auth\WebAuthController`），支援記住我與登出 `/logout`。
- 登入後若具 `backend.access`，將導向後台總覽；否則若具 `frontend.portal.access`，導向員工自助中心；再者回首頁。
- 前臺入口 `/frontend` 提供上班／下班打卡表單，會寫入 `attendance_logs` 並顯示最近 10 筆紀錄。
- 範例公司/部門/職位由 `PositionLevelSeeder` 與 `CompanyDataSeeder` 建立，包含總裁、總經理、副總、部長、科長、課長、特助、主任、職員等層級。
- 預設管理帳號：`admin@erp.local / password`（`AdminUserSeeder` 建立並指派 System Owner）。
- 預設前臺帳號：`employee1@erp.local / password`、`employee2@erp.local / password`、`manager@erp.local / password`（`FrontendUserSeeder` 建立）。
- 範例公司與組織資料：`CompanyDataSeeder` 建立 Alpha Manufacturing、Beta Logistics 及其部門、職位，示範員工帳號皆綁定 Alpha 公司並依公司範圍設定角色。

## Attendance Management
- 員工資料新增薪資等級、勞工等級、原住民與身心障礙標記，可於後台員工管理維護。
- HR 後台：`/backend/attendance` 提供篩選員工/日期並查看打卡紀錄，支援補登表單。
- 使用表單欄位 `employee_id`, `recorded_at`, `type`, `remarks` 新增出勤紀錄。
- 針對單一員工可檢視最近兩週出勤摘要（需後續排程產生 `attendance_summaries`).
- 需具備 `attendance.manage` 權限才可存取。

## Leave Module
- 假別設定：`/backend/leave-types` 可新增/編輯假別、預設額度與審核規則。
- 假單審核：`/backend/leave-requests` 顯示請假清單，對應表單支援核准、退回、取消流程。
- 員工前台：`/frontend/hr/leave-request` 提供請假表單，送出後新增 `leave_requests` 記錄。
- 權限說明：後台需 `leave.manage`，員工送假需 `frontend.leave.submit`。

## Payroll Overview
- `/backend/payroll` 顯示最近薪資期間、薪資項目與批次概況，作為薪資模組後續開發入口。
- 資料表：`payroll_periods`, `salary_components`, `payroll_runs`, `payroll_entries`, `payroll_entry_components`, `performance_reviews`, `reward_records`。
- 需具備 `payroll.view` 權限。

## Next Steps
1. 建立排程計算出勤摘要與假勤餘額（同步更新 `attendance_summaries`, `leave_balances`）。
2. 擴充薪資頁：新增建立薪資期間與薪資批次的 Blade 表單與計薪服務。
3. 整合通知：假單審核後透過 Email/Line 等通知員工與主管。
4. 加入存取管控：為出勤、假勤、薪資頁面套用角色/權限檢查。
