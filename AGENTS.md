# Repository Guidelines

## Project Structure & Module Organization
Laravel 域邏輯位於 `app/`，HTTP 進入點在 `routes/` (`web.php` 為 Blade、`api.php` 為 JSON)。前端改採 Bootstrap 5 + jQuery（CDN 載入），自訂樣式集中在 `public/css/app.css` 等靜態資源，不再經由 Vite。資料庫 migrations、seeders、factories 位於 `database/`，自動化測試在 `tests/`。`config/` 仍是執行時設定與旗標來源。

## Build, Test, and Development Commands
- `composer install`: 安裝 PHP 依賴。
- `php artisan serve`: 啟動開發伺服器。
- `php artisan migrate --seed`: 重建資料庫並填充測試資料。
- `php artisan test`: 執行測試套件。

## Coding Style & Naming Conventions
採用 PSR-12、四空格縮排；提交前執行 `./vendor/bin/pint`。類別命名具描述性（如 `InventoryAdjustmentService`），資料庫欄位使用 snake_case。Blade 檔案維持 kebab-case，若需額外樣式請於 `public/css/app.css` 撰寫，必要時可加入簡單的 jQuery snippet。

## Testing Guidelines
Write feature and unit tests with PHPUnit (`php artisan test`). Group test classes by context (e.g., `tests/Feature/Inventory/`). Name methods with `test_` prefixes describing behavior, and seed deterministic fixtures via factories. Aim for meaningful assertions around HTTP status, events, and database state. Use the `tests/CreatesApplication.php` helpers for bootstrapping.

## Commit & Pull Request Guidelines
Keep commit subjects imperative and under 72 characters (e.g., `inventory: add stock reconciliation job`). Squash WIP commits before opening a PR. Pull requests should explain the problem, summarize the solution, list migrations or breaking changes, and attach screenshots for UI updates. Link tracker tickets with `Closes #ID`. Ensure CI (`composer run test`, `npm run build`) passes locally before requesting review.

## Security & Environment Tips
Never commit `.env` or generated keys; use `.env.example` for defaults. Rotate queue, cache, and database credentials through the environment rather than code. When sharing logs, scrub customer data and tokens.
- Backend Blade 頁面需登入並具備相對應權限（如 `backend.access`, `company.manage`, `attendance.manage`）。前臺員工入口使用 `frontend.portal.access` / `frontend.leave.submit` 控制存取。
- 使用 `/login` 進行登入，成功後可透過 `/logout` 登出並清除 Session。
- 預設後台系統管理帳號：`admin@erp.local / password`（由 `AdminUserSeeder` 建立並綁定 System Owner 角色）。請登入後立即修改密碼。
- 預設前台帳號：`employee1@erp.local / password`、`employee2@erp.local / password`（Employee 角色）以及 `manager@erp.local / password`（Company Manager 角色）。
- 登入完成後：具 `backend.access` 會導向後台總覽；僅具前台權限則導向員工自助中心，否則回首頁。
- `/frontend` 首頁提供上班／下班打卡表單，成功送出會寫入 `attendance_logs` 並顯示最近紀錄。
- 後台員工管理可維護薪資等級、勞工等級與原住民/身心障礙標記（請執行最新 migration）。
- `CompanyDataSeeder` 會建立 Alpha Manufacturing、Beta Logistics 等範例公司與部門/職位，並讓示範帳號綁定對應公司、角色範圍。
