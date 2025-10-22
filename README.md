# ERP Development Project

An Enterprise Resource Planning (ERP) system built with Laravel 12, focusing on Human Resources Management functionality.

## Quick Start

### System Requirements
- PHP >= 8.2
- Composer
- SQLite or MySQL
- Git

### Installation Steps

```bash
# 1. Clone the repository
git clone <repository-url> erp-dev
cd erp-dev

# 2. Install dependencies
composer install

# 3. Setup environment variables
cp .env.example .env
php artisan key:generate

# 4. Create database and seed data
php artisan migrate --seed

# 5. Start development server
php artisan serve
```

Visit http://localhost:8000

## Main Features

### ✨ Core Modules
- **Company Organization Management** - Multi-company, departments, positions, employee data maintenance
- **Attendance Management** - Clock in/out records, attendance statistics, manual entry management
- **Leave Management** - Leave type settings, leave applications, approval workflow
- **Payroll Management** - Payroll periods, calculation batches, insurance brackets
- **Access Control** - Role-based permissions, company-scoped access, activity audit logs

### 🎨 Technical Highlights
- Separate frontend and backend design
- Responsive UI with Bootstrap 5 + jQuery
- Complete RBAC permission system
- RESTful API architecture
- PHPUnit test coverage

## System Functionality Overview

### Dashboard & Navigation
- Unified `layouts.app` layout with sticky topbar, collapsible sidebar, and responsive shell
- Backend dashboard (`/backend`) summarising company counts, employee statistics, attendance alerts, and approval queues
- Frontend employee portal (`/frontend`) with quick access to personal attendance, leave balances, and HR announcements

### Authentication & Access Control
- Session-based authentication with Laravel Sanctum API support
- Role/permission seeding via `AccessControlSeeder`
- Middleware `EnsureHasPermission` enforcing granular permissions (`backend.access`, `leave.manage`, etc.)
- Company-level scoping through `role_scopes` for multi-tenant data segregation

### Company & Organization Management
- `backend/companies` CRUD for company profiles, status toggling, and contact information
- `backend/departments` hierarchy management (parent-child departments, department lead assignment)
- `backend/positions` with managerial flag, reference salary, insurance grade, level mapping
- `backend/employees` comprehensive employee roster: filtering, blocking/unblocking, role assignment, export options, and nested tabs for profile sections

### Attendance Management
- Frontend check-in/check-out endpoints (`/frontend/attendance/check-in`, `/frontend/attendance/check-out`)
- Backend attendance console with manual entry, device tagging, and audit trails
- Attendance summaries (`attendance_summaries` table) with late/early leave indicators
- Attendance devices registry (`AttendanceDevice` model) for hardware integration

### Leave Management
- Leave types index (`backend/leave-types`) supporting quota configuration, approval requirements, attendance impact toggle
- Leave requests workflow (`backend/leave-requests`) with status transitions (pending/approved/rejected/cancelled)
- Employee self-service leave form (`frontend/hr/leave-request`) using form requests for validation
- Leave balances (`LeaveBalance` model) seeded with annual allowances per employee

### Payroll & Compensation
- Payroll overview (`backend/payroll`) covering period setup, payroll runs, and entry summaries
- Salary components definitions (earnings/deductions) with taxability flags
- Insurance bracket management (`backend/insurance-brackets`) aligned with Ministry of Labor tables
- Support classes (`InsuranceSchedule`, `InsuranceContributionSummary`) for contribution calculations

### HR Supporting Modules
- Position levels reference (`PositionLevelSeeder`) and salary grading
- Reward & performance tracking models (`RewardRecord`, `PerformanceReview`)
- Employment contracts, addresses, contacts stored via dedicated models for data normalization
- Activity log (`ActivityLog` model) capturing CRUD operations across backend modules

### Frontend Employee Portal
- Attendance widget showing recent logs and check-in/out actions
- Leave summary cards with balances and outstanding approvals
- Responsive Bootstrap 5 interface with custom styling in `public/css/app.css`
- jQuery-powered interactions for modal forms, confirmations, and AJAX submissions

### Notifications & Workflow (Planned/partial)
- Approval request model (`ApprovalRequest`) backing leave and overtime flows
- Scheduler-ready artisan command structure for future automation (`app/Console/Commands`)
- Supervisor-ready queue scaffolding for background jobs (`queue:work` configuration guidance in docs)

### API Endpoints
- RESTful routes under `routes/api.php` for employees, attendance logs, leave requests, payroll resources
- Sanctum token issuance via `/api/login` supporting mobile/3rd-party integration
- Consistent JSON error structure with validation details (`422` responses)

### Testing & Quality Assurance
- PHPUnit feature tests grouped under `tests/Feature/Backend` and `tests/Feature/Frontend`
- `CreatesApplication` trait bootstrapping Laravel testing environment
- Laravel Pint configuration ensuring PSR-12 compliance
- Sample tests covering employee backend access, blocking, and route protections

### Seeded Demo Data
- Admin account (`admin@erp.local / password`) with System Owner role
- Frontend demo users (employee1, employee2, manager) mapped to Alpha Manufacturing & Beta Logistics sample companies
- `CompanyDataSeeder` provisioning companies, departments, positions, and role assignments
- Holiday seeders (`Holiday2024Seeder`, `Holiday2025Seeder`) populating calendar data for attendance/leave modules

### Configuration & Extensibility
- All runtime flags managed via `config/` directory (queue, mail, cache, session)
- Environment-specific `.env` usage with recommended production tuning (config caching, route caching)
- Modular controller organization (`app/Http/Controllers/Backend` and `Frontend`) for feature separation
- Support for additional modules via documented roadmap in README & `PROJECT_DOCUMENTATION.md`

## Test Accounts

### Backend Admin
- **Email**: admin@erp.local
- **Password**: password
- **Role**: System Administrator (Full permissions)

### Frontend Employee
- **Email**: employee1@erp.local / employee2@erp.local
- **Password**: password
- **Role**: Employee (Clock in/out, leave requests)

### Frontend Manager
- **Email**: manager@erp.local
- **Password**: password
- **Role**: Company Manager (Approval permissions)

⚠️ **Security Notice**: Please change passwords immediately after first login!

## Project Structure

```
erp-dev/
├── app/                    # Application logic
│   ├── Http/Controllers/   # Controllers
│   │   ├── Auth/          # Authentication
│   │   ├── Backend/       # Backend controllers
│   │   └── Frontend/      # Frontend controllers
│   ├── Models/            # Eloquent models
│   └── View/              # View components
├── database/              # Database
│   ├── migrations/        # Database migrations
│   └── seeders/           # Data seeders
├── resources/views/       # Blade templates
│   ├── backend/          # Backend views
│   └── frontend/         # Frontend views
├── routes/
│   ├── web.php           # Web routes
│   └── api.php           # API routes
├── tests/                # Test files
└── public/               # Public assets
```

## Development Commands

```bash
# Run tests
php artisan test

# Code formatting (PSR-12)
./vendor/bin/pint

# Rebuild database
php artisan migrate:fresh --seed

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Main Routes

### Authentication
- `GET /login` - Login page
- `POST /logout` - Logout

### Frontend (Employee Portal)
- `GET /frontend` - Employee home
- `POST /frontend/attendance/{action}` - Clock in/out (check-in/check-out)
- `GET /frontend/hr/leave-request` - Leave request form

### Backend (Admin Panel)
- `GET /backend` - Backend dashboard
- `/backend/companies` - Company management
- `/backend/departments` - Department management
- `/backend/positions` - Position management
- `/backend/employees` - Employee management
- `/backend/attendance` - Attendance management
- `/backend/leave-types` - Leave type management
- `/backend/leave-requests` - Leave request approval
- `/backend/payroll` - Payroll management

## Documentation

📖 **Complete Project Documentation**: [PROJECT_DOCUMENTATION.md](PROJECT_DOCUMENTATION.md)

Includes:
- Detailed module descriptions
- Database architecture
- API documentation
- Development guidelines
- Deployment guide
- Future development roadmap

📋 **Other Documents**:
- [AGENTS.md](AGENTS.md) - Development guidelines
- [docs/HR_MODULES.md](docs/HR_MODULES.md) - HR module details

## Development Guidelines

### Code Style
- Follow PSR-12 standards
- Use Laravel Pint for formatting
- 4-space indentation

### Git Commits
```
<type>: <subject>

feat: New feature
fix: Bug fix
docs: Documentation update
style: Code style/formatting
refactor: Code refactoring
test: Test-related changes
```

### Testing
- Run `php artisan test` to ensure tests pass
- New features must include tests
- Target coverage: 80%+

## Permission System

### Default Roles
1. **System Owner** - System administrator (cross-company full permissions)
2. **Company Manager** - Company administrator (single company full permissions)
3. **HR Manager** - HR supervisor (personnel, attendance, payroll)
4. **Department Manager** - Department head (department management, approvals)
5. **Employee** - Regular employee (personal data, clock in/out, leave requests)

### Permission List
- `backend.access` - Backend access
- `company.manage` - Company management
- `employee.manage` - Employee management
- `attendance.manage` - Attendance management
- `leave.manage` - Leave management
- `payroll.view` / `payroll.manage` - Payroll view/management
- `frontend.portal.access` - Frontend portal access
- `frontend.leave.submit` - Leave request submission

## Development Roadmap

### Short-term (1-3 months)
- [ ] Automated attendance summary scheduling
- [ ] Leave approval notification system
- [ ] Payroll calculation engine
- [ ] Permission system optimization

### Mid-term (3-6 months)
- [ ] Recruitment management module
- [ ] Training & development module
- [ ] Performance management module
- [ ] Reporting system

### Long-term (6-12 months)
- [ ] Mobile application development
- [ ] AI feature integration
- [ ] Third-party system integration
- [ ] Multi-language support

## Technology Stack

### Backend
- **Framework**: Laravel 12.x
- **Language**: PHP 8.2+
- **Database**: SQLite / MySQL
- **Authentication**: Laravel Session + Sanctum
- **Testing**: PHPUnit 11.x

### Frontend
- **UI**: Bootstrap 5.3.x
- **JavaScript**: jQuery 3.7.x
- **Template**: Blade
- **Icons**: Bootstrap Icons

## License

This project is licensed under the [MIT License](https://opensource.org/licenses/MIT).

## Contributing

Issues and Pull Requests are welcome!

## Contact

For questions or suggestions, please contact the project maintainer.

---

**Created**: 2025-10-22  
**Laravel Version**: 12.x  
**PHP Version**: 8.2+
