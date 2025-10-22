# ERP Development Project - Complete Documentation

## Table of Contents
1. [Project Overview](#project-overview)
2. [Technology Stack](#technology-stack)
3. [Project Structure](#project-structure)
4. [Core Modules](#core-modules)
5. [Database Architecture](#database-architecture)
6. [Permission & Role System](#permission--role-system)
7. [Routing Design](#routing-design)
8. [Frontend Architecture](#frontend-architecture)
9. [Development Environment Setup](#development-environment-setup)
10. [Test Accounts](#test-accounts)
11. [Development Guidelines](#development-guidelines)
12. [API Documentation](#api-documentation)
13. [Deployment Guide](#deployment-guide)
14. [Future Development Roadmap](#future-development-roadmap)

---

## Project Overview

This is an Enterprise Resource Planning (ERP) system developed with Laravel 12, primarily focused on Human Resources Management functionality. The system adopts a modern web application architecture, providing both backend management interface and employee self-service portal.

### Main Functional Modules
- **Company Organization Management**: Companies, departments, positions, employee data maintenance
- **Attendance Management**: Clock in/out records, attendance statistics, manual entry management
- **Leave Management**: Leave type settings, leave applications, approval workflow
- **Payroll Management**: Payroll periods, payroll calculation, insurance brackets
- **Access Control**: Role-based permissions, company-scoped access, activity audit logs

### System Features
- ✅ Separate frontend and backend design
- ✅ Complete permission control system
- ✅ Multi-company/multi-department support
- ✅ Responsive Bootstrap 5 interface
- ✅ RESTful API architecture
- ✅ Comprehensive test coverage

---

## Technology Stack

### Backend Technologies
- **Framework**: Laravel 12.x (PHP 8.2+)
- **Database**: SQLite (development) / MySQL (production)
- **Authentication**: Laravel Session Authentication
- **API**: Laravel Sanctum (Token Authentication)
- **Testing**: PHPUnit 11.x

### Frontend Technologies
- **UI Framework**: Bootstrap 5.3.x (CDN)
- **JavaScript**: jQuery 3.7.x (CDN)
- **Styling**: Custom CSS (`public/css/app.css`)
- **Template Engine**: Blade Templates
- **Icons**: Bootstrap Icons

### Development Tools
- **Code Style**: Laravel Pint (PSR-12)
- **Dependency Management**: Composer
- **Version Control**: Git
- **Debugging**: Laravel Telescope (optional)

---

## Project Structure

```
erp-dev/
├── app/
│   ├── Console/
│   │   └── Commands/          # Artisan commands
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/          # Authentication controllers
│   │   │   ├── Backend/       # Backend controllers
│   │   │   └── Frontend/      # Frontend controllers
│   │   ├── Middleware/        # Custom middleware
│   │   ├── Requests/          # Form request validation
│   │   └── Resources/         # API resources
│   ├── Models/                # Eloquent models
│   ├── Observers/             # Model observers
│   ├── Providers/             # Service providers
│   ├── Support/               # Support classes
│   └── View/
│       ├── Components/        # Blade components
│       └── Composers/         # View composers
├── bootstrap/                 # Framework bootstrap files
├── config/                    # Configuration files
├── database/
│   ├── factories/             # Model factories
│   ├── migrations/            # Database migrations
│   └── seeders/               # Data seeders
├── docs/                      # Documentation directory
├── public/                    # Public assets
│   └── css/
│       └── app.css            # Custom styles
├── resources/
│   └── views/
│       ├── auth/              # Authentication pages
│       ├── backend/           # Backend views
│       ├── frontend/          # Frontend views
│       ├── components/        # Blade components
│       └── layouts/           # Layout templates
├── routes/
│   ├── api.php                # API routes
│   ├── console.php            # Console routes
│   └── web.php                # Web routes
├── storage/                   # Storage directory
├── tests/
│   ├── Feature/               # Feature tests
│   └── Unit/                  # Unit tests
├── vendor/                    # Composer dependencies
├── .env.example               # Environment variables example
├── composer.json              # PHP dependencies definition
├── phpunit.xml                # PHPUnit configuration
└── README.md                  # Project readme
```

---

## Core Modules

### 1. Company Organization Management Module

#### Features
- Multi-company management (Companies)
- Department hierarchy management (Departments)
- Position definitions (Positions)
- Employee data management (Employees)

#### Main Files
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

#### Table Structures

**companies table**
- `id`: Primary key
- `name`: Company name
- `code`: Company code
- `tax_id`: Tax identification number
- `address`: Address
- `phone`: Phone number
- `is_active`: Active status
- `timestamps`

**departments table**
- `id`: Primary key
- `company_id`: Parent company
- `parent_id`: Parent department (supports hierarchy)
- `name`: Department name
- `code`: Department code
- `lead_employee_id`: Department head
- `is_active`: Active status
- `timestamps`

**positions table**
- `id`: Primary key
- `company_id`: Parent company
- `department_id`: Parent department
- `name`: Position name
- `code`: Position code
- `level_id`: Position level
- `reference_salary`: Reference salary
- `insurance_grade`: Insurance grade
- `is_active`: Active status
- `timestamps`

**employees table**
- `id`: Primary key
- `user_id`: Linked user account
- `company_id`: Parent company
- `department_id`: Parent department
- `position_id`: Position
- `employee_number`: Employee number
- `full_name`: Full name
- `id_number`: National ID number
- `birth_date`: Birth date
- `hire_date`: Hire date
- `salary_grade`: Salary grade
- `labor_grade`: Labor grade
- `is_indigenous`: Indigenous status
- `is_disabled`: Disability status
- `is_blocked`: Blocked status
- `blocked_at`: Blocked timestamp
- `blocked_reason`: Block reason
- `timestamps`
- `softDeletes`

### 2. Attendance Management Module

#### Features
- Employee clock in/out (check-in/check-out)
- Attendance record queries
- Manual attendance entry
- Attendance summary statistics

#### Main Files
- **Models**: 
  - `AttendanceLog.php`
  - `AttendanceSummary.php`
  - `AttendanceDevice.php`
- **Controllers**:
  - `Frontend/AttendanceController.php` (Employee clock in/out)
  - `Backend/AttendanceManagementController.php` (Backend management)
- **Migration**: `2025_10_18_151134_create_attendance_tables.php`

#### Table Structures

**attendance_logs table**
- `id`: Primary key
- `employee_id`: Employee ID
- `recorded_at`: Clock time
- `type`: Clock type (check-in/check-out)
- `device_id`: Device ID
- `ip_address`: IP address
- `remarks`: Remarks
- `created_by`: Creator
- `timestamps`

**attendance_summaries table**
- `id`: Primary key
- `employee_id`: Employee ID
- `date`: Date
- `first_check_in`: First check-in time
- `last_check_out`: Last check-out time
- `total_hours`: Total work hours
- `late_minutes`: Late minutes
- `early_leave_minutes`: Early leave minutes
- `timestamps`

**attendance_devices table**
- `id`: Primary key
- `name`: Device name
- `code`: Device code
- `location`: Device location
- `is_active`: Active status
- `timestamps`

#### Routes
```php
// Frontend clock in/out
POST /frontend/attendance/check-in    // Check in
POST /frontend/attendance/check-out   // Check out

// Backend management
GET  /backend/attendance              // Attendance records list
POST /backend/attendance              // Manual attendance entry
```

### 3. Leave Management Module

#### Features
- Leave type settings and management
- Employee leave applications
- Leave approval workflow
- Leave balance management

#### Main Files
- **Models**:
  - `LeaveType.php`
  - `LeaveRequest.php`
  - `LeaveBalance.php`
- **Controllers**:
  - `Frontend/EmployeeLeaveController.php` (Employee applications)
  - `Backend/LeaveTypeManagementController.php` (Leave type management)
  - `Backend/LeaveRequestManagementController.php` (Approval management)
- **Requests**:
  - `StoreLeaveTypeRequest.php`
  - `UpdateLeaveTypeRequest.php`
  - `SubmitLeaveRequest.php`

#### Table Structures

**leave_types table**
- `id`: Primary key
- `company_id`: Parent company
- `code`: Leave type code
- `name`: Leave type name
- `default_days`: Default days
- `requires_approval`: Requires approval
- `is_paid`: Is paid leave
- `is_active`: Active status
- `timestamps`

**leave_requests table**
- `id`: Primary key
- `employee_id`: Applicant employee
- `leave_type_id`: Leave type
- `start_date`: Start date
- `end_date`: End date
- `start_time`: Start time
- `end_time`: End time
- `total_days`: Total days
- `reason`: Leave reason
- `status`: Status (pending/approved/rejected/cancelled)
- `approved_by`: Approver
- `approved_at`: Approval timestamp
- `remarks`: Approval remarks
- `timestamps`

**leave_balances table**
- `id`: Primary key
- `employee_id`: Employee ID
- `leave_type_id`: Leave type
- `year`: Year
- `total_days`: Total allowance
- `used_days`: Used days
- `remaining_days`: Remaining balance
- `timestamps`

#### Leave Status Flow
```
pending (Pending approval)
  ├─> approved (Approved)
  ├─> rejected (Rejected)
  └─> cancelled (Cancelled)
```

#### Routes
```php
// Frontend applications
GET  /frontend/hr/leave-request       // Leave request form
POST /frontend/hr/leave-request       // Submit leave request

// Backend management
GET  /backend/leave-types             // Leave types list
POST /backend/leave-types             // Create leave type
PUT  /backend/leave-types/{id}        // Update leave type
DELETE /backend/leave-types/{id}      // Delete leave type

GET  /backend/leave-requests          // Leave requests approval list
PUT  /backend/leave-requests/{id}     // Approve/reject leave request
```

### 4. Payroll Management Module

#### Features
- Payroll period management
- Salary component settings
- Payroll calculation batches
- Insurance bracket management
- Performance review records
- Reward and penalty records

#### Main Files
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

#### Table Structures

**payroll_periods table**
- `id`: Primary key
- `company_id`: Parent company
- `name`: Period name
- `start_date`: Start date
- `end_date`: End date
- `payment_date`: Payment date
- `status`: Status (draft/processing/completed/cancelled)
- `timestamps`

**salary_components table**
- `id`: Primary key
- `company_id`: Parent company
- `code`: Component code
- `name`: Component name
- `type`: Type (earning/deduction)
- `is_taxable`: Is taxable
- `is_active`: Active status
- `timestamps`

**payroll_runs table**
- `id`: Primary key
- `payroll_period_id`: Payroll period
- `name`: Batch name
- `total_employees`: Total employees
- `total_amount`: Total amount
- `status`: Status
- `processed_at`: Process timestamp
- `processed_by`: Processor
- `timestamps`

**payroll_entries table**
- `id`: Primary key
- `payroll_run_id`: Payroll batch
- `employee_id`: Employee ID
- `base_salary`: Base salary
- `total_earnings`: Total earnings
- `total_deductions`: Total deductions
- `net_salary`: Net salary
- `payment_date`: Payment date
- `status`: Status
- `timestamps`

**payroll_entry_components table**
- `id`: Primary key
- `payroll_entry_id`: Payroll entry
- `salary_component_id`: Salary component
- `amount`: Amount
- `remarks`: Remarks
- `timestamps`

**insurance_brackets table**
- `id`: Primary key
- `insurance_type`: Insurance type (labor/health/pension)
- `grade`: Grade level
- `monthly_salary_min`: Monthly salary minimum
- `monthly_salary_max`: Monthly salary maximum
- `insured_amount`: Insured amount
- `employee_rate`: Employee contribution rate
- `employer_rate`: Employer contribution rate
- `government_rate`: Government contribution rate
- `effective_date`: Effective date
- `expiry_date`: Expiry date
- `timestamps`

**performance_reviews table**
- `id`: Primary key
- `employee_id`: Employee ID
- `reviewer_id`: Reviewer ID
- `review_period`: Review period
- `score`: Score
- `comments`: Comments
- `reviewed_at`: Review date
- `timestamps`

**reward_records table**
- `id`: Primary key
- `employee_id`: Employee ID
- `type`: Type (reward/penalty)
- `category`: Category
- `amount`: Amount
- `reason`: Reason
- `recorded_at`: Record date
- `recorded_by`: Recorder
- `timestamps`

#### Routes
```php
GET  /backend/payroll                 // Payroll overview
POST /backend/payroll/periods         // Create payroll period
POST /backend/payroll/runs            // Create payroll batch
GET  /backend/insurance-brackets      // Insurance brackets query
```

### 5. Access Control Module

#### Features
- Role definition and management
- Permission assignment
- Company-scoped permissions
- Activity audit logs

#### Main Files
- **Models**:
  - `Role.php`
  - `Permission.php`
  - `UserRole.php`
  - `RoleScope.php`
  - `ActivityLog.php`
- **Middleware**: `CheckPermission.php`
- **Seeders**: `AccessControlSeeder.php`
- **Migration**: `2024_01_01_100400_create_access_control_tables.php`

#### Table Structures

**roles table**
- `id`: Primary key
- `name`: Role name
- `code`: Role code
- `description`: Description
- `is_system`: Is system role
- `timestamps`

**permissions table**
- `id`: Primary key
- `name`: Permission name
- `code`: Permission code
- `module`: Module name
- `description`: Description
- `timestamps`

**permission_role table** (many-to-many pivot)
- `role_id`: Role ID
- `permission_id`: Permission ID

**user_roles table**
- `id`: Primary key
- `user_id`: User ID
- `role_id`: Role ID
- `timestamps`

**role_scopes table** (company-scoped permissions)
- `id`: Primary key
- `user_role_id`: User role
- `company_id`: Company ID
- `timestamps`

**activity_logs table**
- `id`: Primary key
- `user_id`: User ID
- `action`: Action
- `model_type`: Model type
- `model_id`: Model ID
- `old_values`: Old values (JSON)
- `new_values`: New values (JSON)
- `ip_address`: IP address
- `user_agent`: User agent
- `timestamps`

#### Default Roles & Permissions

**System Roles**
1. **System Owner**
   - Full system administration permissions
   - Cross-company operation permissions

2. **Company Manager**
   - Full management permissions within company
   - HR, payroll, attendance management

3. **HR Manager**
   - Employee data management
   - Attendance and leave management
   - Payroll view permissions

4. **Department Manager**
   - Department employee management
   - Leave approval permissions
   - Attendance query permissions

5. **Employee**
   - Personal data view
   - Clock in/out, leave requests
   - Payroll query

**Permission List**
- `backend.access` - Backend access
- `company.manage` - Company management
- `department.manage` - Department management
- `employee.manage` - Employee management
- `attendance.manage` - Attendance management
- `leave.manage` - Leave management
- `payroll.view` - Payroll view
- `payroll.manage` - Payroll management
- `frontend.portal.access` - Employee portal access
- `frontend.leave.submit` - Leave request submission

---

## Database Architecture

### Entity Relationship Diagram

```
users (Users)
  ├─ employees (Employee Data)
  │    ├─ companies (Companies)
  │    ├─ departments (Departments)
  │    ├─ positions (Positions)
  │    │    └─ position_levels (Position Levels)
  │    ├─ employee_contacts (Contact Information)
  │    ├─ employee_addresses (Address Information)
  │    ├─ employment_contracts (Employment Contracts)
  │    ├─ attendance_logs (Attendance Records)
  │    ├─ attendance_summaries (Attendance Summaries)
  │    ├─ leave_requests (Leave Requests)
  │    ├─ leave_balances (Leave Balances)
  │    ├─ payroll_entries (Payroll Entries)
  │    ├─ performance_reviews (Performance Reviews)
  │    └─ reward_records (Reward Records)
  └─ user_roles (User Roles)
       ├─ roles (Roles)
       │    └─ permissions (Permissions)
       └─ role_scopes (Role Scopes)

companies (Companies)
  ├─ departments (Departments)
  ├─ positions (Positions)
  ├─ employees (Employees)
  ├─ leave_types (Leave Types)
  ├─ salary_components (Salary Components)
  └─ payroll_periods (Payroll Periods)
       └─ payroll_runs (Payroll Runs)
            └─ payroll_entries (Payroll Entries)
```

### Index Strategy
- Create indexes on all foreign key columns
- Create unique indexes on frequently queried columns (e.g., `employee_number`, `id_number`)
- Create indexes on timestamp columns (e.g., `recorded_at`, `created_at`)
- Create composite indexes for multi-condition queries (e.g., `employee_id + date`)

---

## Permission & Role System

### Middleware

```php
// Check permissions
Route::middleware(['auth', 'permission:backend.access'])

// Example
Route::get('/backend/payroll', [PayrollController::class, 'index'])
    ->middleware('permission:payroll.view');
```

### Permission Check Methods

```php
// In controllers
if (auth()->user()->hasPermission('payroll.manage')) {
    // Execute operation
}

// In Blade templates
@can('payroll.manage')
    <button>Edit Payroll</button>
@endcan

// Check company-scoped permissions
$user->hasCompanyAccess($companyId);
```

### Company-Scoped Permissions

Use `role_scopes` table to restrict users to specific company data:

```php
// Get user's accessible companies
$companies = auth()->user()->accessibleCompanies();

// Apply scope in queries
Employee::whereIn('company_id', $companies->pluck('id'))->get();
```

---

## Routing Design

### Authentication Routes
```php
GET  /login              // Login page
POST /login              // Process login
POST /logout             // Logout
```

### Frontend Routes (Employee Portal)
```php
// Home
GET  /                                    // Home page
GET  /frontend                            // Employee portal

// Employee self-service (requires frontend.portal.access)
GET  /frontend/hr                         // Self-service center
POST /frontend/attendance/check-in        // Check in
POST /frontend/attendance/check-out       // Check out

// Leave requests (requires frontend.leave.submit)
GET  /frontend/hr/leave-request           // Leave request form
POST /frontend/hr/leave-request           // Submit leave request
```

### Backend Routes (Admin Panel)
```php
// Dashboard (requires backend.access)
GET  /backend                             // Backend dashboard
GET  /backend/hr                          // HR dashboard

// Company management
GET     /backend/companies                // Companies list
GET     /backend/companies/create         // Create form
POST    /backend/companies                // Store company
GET     /backend/companies/{id}/edit      // Edit form
PUT     /backend/companies/{id}           // Update company
DELETE  /backend/companies/{id}           // Delete company

// Department management
GET     /backend/departments              // Departments list
POST    /backend/departments              // Create department
PUT     /backend/departments/{id}         // Update department
DELETE  /backend/departments/{id}         // Delete department

// Position management
GET     /backend/positions                // Positions list
POST    /backend/positions                // Create position
PUT     /backend/positions/{id}           // Update position
DELETE  /backend/positions/{id}           // Delete position

// Employee management
GET     /backend/employees                // Employees list
GET     /backend/employees/create         // Create form
POST    /backend/employees                // Create employee
GET     /backend/employees/{id}/edit      // Edit form
PUT     /backend/employees/{id}           // Update employee
DELETE  /backend/employees/{id}           // Delete employee
POST    /backend/employees/{id}/block     // Block employee
POST    /backend/employees/{id}/unblock   // Unblock employee

// Attendance management (requires attendance.manage)
GET     /backend/attendance               // Attendance records
POST    /backend/attendance               // Manual entry

// Leave type management (requires leave.manage)
GET     /backend/leave-types              // Leave types list
POST    /backend/leave-types              // Create leave type
PUT     /backend/leave-types/{id}         // Update leave type
DELETE  /backend/leave-types/{id}         // Delete leave type

// Leave approval (requires leave.manage)
GET     /backend/leave-requests           // Leave requests list
PUT     /backend/leave-requests/{id}      // Approve/reject request

// Payroll management (requires payroll.view)
GET     /backend/payroll                  // Payroll overview
POST    /backend/payroll/periods          // Create period (requires payroll.manage)
POST    /backend/payroll/runs             // Create batch (requires payroll.manage)

// Insurance brackets (requires payroll.view)
GET     /backend/insurance-brackets       // Brackets query
```

---

## Frontend Architecture

### Blade Layout

**Main Layout**: `resources/views/layouts/app.blade.php`

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ERP System')</title>
    
    <!-- Bootstrap 5.3.x -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    <!-- Navigation bar -->
    @include('components.navbar')
    
    <!-- Main content -->
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

### Frontend Page Examples

**Attendance Clock Page**: `resources/views/frontend/index.blade.php`
- Display current time
- Check-in/check-out buttons
- Last 10 clock records
- AJAX form submission

**Leave Request Page**: `resources/views/frontend/hr/leave-request.blade.php`
- Leave type selection
- Date range picker
- Leave reason input
- Form validation

### Backend Page Examples

**Employee Management Page**: `resources/views/backend/employees/index.blade.php`
- Data table (search, pagination)
- Create/edit/delete buttons
- Block/unblock functionality
- Export functionality

**Attendance Management Page**: `resources/views/backend/attendance/index.blade.php`
- Employee filters
- Date range selection
- Clock records table
- Manual entry form

### Custom Styles

**public/css/app.css**
```css
/* Navigation bar styles */
.navbar-brand {
    font-weight: bold;
}

/* Sidebar */
.sidebar {
    min-height: calc(100vh - 56px);
    background-color: #f8f9fa;
}

/* Card styles */
.card-hover:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
}

/* Table styles */
.table-action-buttons .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

/* Status badges */
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

### JavaScript Interactions

Use jQuery for common interactions:

```javascript
// AJAX form submission
$('#attendance-form').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            // Show success message
            alert('Clock in successful!');
            // Reload records
            location.reload();
        },
        error: function(xhr) {
            alert('Clock in failed, please try again.');
        }
    });
});

// Date range picker
$('#date-range').daterangepicker({
    locale: {
        format: 'YYYY-MM-DD'
    }
});

// Confirm deletion
$('.delete-btn').on('click', function() {
    return confirm('Are you sure you want to delete this item?');
});
```

---

## Development Environment Setup

### System Requirements
- PHP >= 8.2
- Composer
- SQLite or MySQL
- Git

### Installation Steps

#### 1. Clone Repository
```bash
git clone <repository-url> erp-dev
cd erp-dev
```

#### 2. Install Dependencies
```bash
composer install
```

#### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

#### 4. Configure Database

Edit `.env` file:

**Using SQLite (Development)**
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite
```

**Using MySQL (Production)**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=erp_dev
DB_USERNAME=root
DB_PASSWORD=
```

#### 5. Run Migrations & Seed Data
```bash
# Create tables and seed test data
php artisan migrate --seed

# Or run separately
php artisan migrate
php artisan db:seed
```

#### 6. Start Development Server
```bash
php artisan serve
```

Default URL: http://localhost:8000

### Seeder Description

Running `php artisan db:seed` executes in order:

1. **AccessControlSeeder** - Create roles and permissions
2. **AdminUserSeeder** - Create system admin account
3. **PositionLevelSeeder** - Create position level data
4. **InsuranceBracketSeeder** - Create insurance bracket data
5. **LeaveTypeSeeder** - Create leave type data
6. **CompanyDataSeeder** - Create sample companies, departments, positions
7. **FrontendUserSeeder** - Create frontend test accounts

---

## Test Accounts

### Backend Admin Accounts

| Email | Password | Role | Permissions |
|-------|----------|------|-------------|
| admin@erp.local | password | System Owner | Full system administration |

**Notes**:
- Created by `AdminUserSeeder`
- Has all backend management permissions
- Can operate across companies
- **Please change password immediately after first login**

### Frontend Employee Accounts

| Email | Password | Role | Company | Department | Position |
|-------|----------|------|---------|------------|----------|
| employee1@erp.local | password | Employee | Alpha Manufacturing | R&D Dept | Senior Engineer |
| employee2@erp.local | password | Employee | Alpha Manufacturing | Sales Dept | Sales Specialist |
| manager@erp.local | password | Company Manager | Alpha Manufacturing | Admin Dept | General Manager |

**Notes**:
- Created by `FrontendUserSeeder`
- Have frontend clock in/out and leave request permissions
- Manager account has additional approval permissions

### Sample Company Data

#### Alpha Manufacturing
- **Departments**:
  - Admin Dept (General Manager, Executive Assistant)
  - R&D Dept (R&D Manager, Senior Engineer, Engineer)
  - Production Dept (Production Manager, Team Leader, Operator)
  - Sales Dept (Sales Manager, Sales Specialist)
- **Employee Count**: 15

#### Beta Logistics
- **Departments**:
  - Admin Dept (General Manager)
  - Operations Dept (Operations Manager, Dispatcher)
  - Warehouse Dept (Warehouse Manager, Stockkeeper)
- **Employee Count**: 10

---

## Development Guidelines

### Code Style

#### PSR-12 Standard
- 4-space indentation (no tabs)
- Classes use PascalCase
- Methods use camelCase
- Constants use UPPER_SNAKE_CASE

#### Laravel Conventions
- Model naming: Singular noun (User, Employee)
- Controller naming: Plural + Controller (UsersController)
- Table naming: Plural snake_case (users, employees)
- Column naming: snake_case (first_name, created_at)

### Formatting Tool

```bash
# Run Laravel Pint formatting
./vendor/bin/pint

# Check without modifying
./vendor/bin/pint --test
```

### Git Commit Guidelines

#### Commit Message Format
```
<type>: <subject>

<body>

<footer>
```

**Types**:
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation update
- `style`: Code formatting
- `refactor`: Code refactoring
- `test`: Test-related
- `chore`: Build tools or auxiliary tools changes

**Example**:
```
feat: add employee blocking functionality

Implement block/unblock features for employee management:
- Add blocked_at, blocked_reason columns to employees table
- Create block/unblock routes and controller methods
- Add UI buttons in employee list page

Closes #42
```

#### Branching Strategy

```
main (Production)
  └─ develop (Development)
       ├─ feature/attendance-module (Feature branch)
       ├─ feature/leave-management (Feature branch)
       └─ bugfix/fix-login-redirect (Bugfix branch)
```

**Workflow**:
1. Create feature branch from `develop`
2. Complete development and pass tests
3. Create Pull Request to `develop`
4. Merge after Code Review approval
5. Periodically merge `develop` to `main`

### Testing Guidelines

#### Run Tests
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/EmployeeAccessTest.php

# Show detailed output
php artisan test --parallel --coverage
```

#### Test Naming

```php
// Feature tests
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

#### Test Organization

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

### Security Considerations

#### 1. Prevent SQL Injection
```php
// ✅ Correct: Use parameter binding
Employee::where('employee_number', $number)->first();

// ❌ Wrong: Direct concatenation
DB::select("SELECT * FROM employees WHERE employee_number = '$number'");
```

#### 2. Prevent XSS
```blade
{{-- ✅ Correct: Auto-escaped --}}
{{ $employee->full_name }}

{{-- ❌ Wrong: Raw output --}}
{!! $employee->full_name !!}
```

#### 3. CSRF Protection
```blade
<form method="POST" action="/backend/employees">
    @csrf
    <!-- Form fields -->
</form>
```

#### 4. Mass Assignment Protection
```php
class Employee extends Model
{
    protected $fillable = [
        'employee_number',
        'full_name',
        // Explicitly list fillable fields
    ];
    
    protected $guarded = [
        'id',
        'is_blocked',
        // Protect sensitive fields
    ];
}
```

#### 5. Permission Checks
```php
// In controllers
public function destroy(Employee $employee)
{
    // Check permission
    abort_unless(auth()->user()->can('employee.manage'), 403);
    
    // Check company scope
    abort_unless(
        auth()->user()->hasCompanyAccess($employee->company_id),
        403
    );
    
    $employee->delete();
    
    return redirect()->route('backend.employees.index');
}
```

---

## API Documentation

### Authentication

Use Laravel Sanctum for API authentication:

```bash
# Get Token
POST /api/login
Content-Type: application/json

{
    "email": "admin@erp.local",
    "password": "password"
}

# Response
{
    "token": "1|abc123...",
    "user": {
        "id": 1,
        "name": "Admin",
        "email": "admin@erp.local"
    }
}
```

Use Token:
```bash
GET /api/employees
Authorization: Bearer 1|abc123...
```

### API Endpoints

#### Employee Resource
```bash
# Get employees list
GET /api/employees
Query Parameters:
  - page: Page number (default 1)
  - per_page: Items per page (default 15)
  - search: Search keyword
  - company_id: Company ID
  - department_id: Department ID

# Get single employee
GET /api/employees/{id}

# Create employee
POST /api/employees
Content-Type: application/json

{
    "employee_number": "E001",
    "full_name": "John Doe",
    "company_id": 1,
    "department_id": 1,
    "position_id": 1,
    "hire_date": "2025-01-01"
}

# Update employee
PUT /api/employees/{id}

# Delete employee
DELETE /api/employees/{id}
```

#### Attendance Resource
```bash
# Clock in/out
POST /api/attendance/check-in
POST /api/attendance/check-out

# Get attendance records
GET /api/attendance/logs?employee_id={id}&start_date={date}&end_date={date}

# Get attendance summary
GET /api/attendance/summary?employee_id={id}&month={YYYY-MM}
```

#### Leave Resource
```bash
# Submit leave request
POST /api/leave-requests
{
    "leave_type_id": 1,
    "start_date": "2025-01-10",
    "end_date": "2025-01-12",
    "reason": "Family matters"
}

# Get leave requests
GET /api/leave-requests?status=pending

# Approve/reject leave request
PUT /api/leave-requests/{id}/approve
PUT /api/leave-requests/{id}/reject
{
    "remarks": "Approval/rejection reason"
}
```

### Error Handling

API error response format:

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "employee_number": [
            "The employee number has already been taken."
        ],
        "email": [
            "The email format is invalid."
        ]
    }
}
```

HTTP Status Codes:
- `200` - Success
- `201` - Created
- `204` - Deleted
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Failed
- `500` - Server Error

---

## Deployment Guide

### Production Environment Configuration

#### 1. Environment Variables

Edit `.env` file:

```env
APP_NAME="ERP System"
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

#### 2. Optimization

```bash
# Clear and cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize Composer autoload
composer install --optimize-autoloader --no-dev
```

#### 3. Database Migrations

```bash
# Run migrations (Note: Backup production database first)
php artisan migrate --force

# Seed base data (without test data)
php artisan db:seed --class=AccessControlSeeder
php artisan db:seed --class=PositionLevelSeeder
php artisan db:seed --class=InsuranceBracketSeeder
php artisan db:seed --class=LeaveTypeSeeder
```

#### 4. File Permissions

```bash
# Set storage and bootstrap/cache writable
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Web Server Configuration

#### Nginx Configuration Example

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

#### Apache Configuration Example

Ensure `.htaccess` file exists in `public/` directory:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### SSL Certificate Setup

Using Let's Encrypt:

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Obtain certificate
sudo certbot --nginx -d erp.example.com

# Auto-renewal setup
sudo certbot renew --dry-run
```

### Background Tasks Setup

#### Cron Scheduler

Edit crontab:
```bash
crontab -e
```

Add:
```
* * * * * cd /var/www/erp-dev && php artisan schedule:run >> /dev/null 2>&1
```

#### Queue Worker (Supervisor)

Create configuration file `/etc/supervisor/conf.d/erp-worker.conf`:

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

Start:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start erp-worker:*
```

### Backup Strategy

#### Database Backup

Create backup script `backup-db.sh`:

```bash
#!/bin/bash

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/erp"
DB_NAME="erp_production"
DB_USER="erp_user"
DB_PASS="secure_password"

mkdir -p $BACKUP_DIR

mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_backup_$DATE.sql.gz

# Keep last 30 days of backups
find $BACKUP_DIR -name "db_backup_*.sql.gz" -mtime +30 -delete
```

Setup daily automatic backup:
```bash
0 2 * * * /path/to/backup-db.sh
```

#### File Backup

```bash
# Backup entire project (exclude vendor and node_modules)
tar -czf erp-backup-$(date +%Y%m%d).tar.gz \
    --exclude='vendor' \
    --exclude='node_modules' \
    --exclude='storage/logs' \
    /var/www/erp-dev
```

### Monitoring & Logging

#### Application Logs

Log location: `storage/logs/laravel.log`

Setup log rotation `/etc/logrotate.d/erp`:

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

#### Performance Monitoring

Install Laravel Telescope (development):

```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

Enable in `.env`:
```env
TELESCOPE_ENABLED=true
```

Access: `https://erp.example.com/telescope`

---

## Future Development Roadmap

### Short-term Goals (1-3 months)

#### 1. Attendance Module Enhancement
- [ ] Implement scheduled automatic daily attendance summary calculation
- [ ] Add late/early leave rule settings
- [ ] Support flexible work schedule system
- [ ] Integrate physical clock device API
- [ ] Abnormal attendance notification mechanism

#### 2. Leave Module Improvement
- [ ] Implement leave approval notifications (Email/Line)
- [ ] Support delegate settings
- [ ] Batch approval functionality
- [ ] Leave statistics reports
- [ ] Automatic annual leave carryover

#### 3. Payroll Calculation Features
- [ ] Build payroll calculation engine
- [ ] Integrate attendance and leave data
- [ ] Support various allowance/deduction items
- [ ] Automatic labor/health insurance calculation
- [ ] Automatic income tax withholding
- [ ] Payslip generation and distribution

#### 4. Permission System Optimization
- [ ] Row-level security (data-level permissions)
- [ ] Approval workflow engine
- [ ] Delegation permission functionality
- [ ] Permission audit logs

### Mid-term Goals (3-6 months)

#### 5. Recruitment Management Module
- [ ] Job posting management
- [ ] Applicant database
- [ ] Interview scheduling system
- [ ] Hiring process tracking

#### 6. Training & Development Module
- [ ] Training course management
- [ ] Employee training records
- [ ] Certification management
- [ ] Training needs analysis

#### 7. Performance Management Module
- [ ] Goal setting (OKR/KPI)
- [ ] Regular review process
- [ ] 360-degree assessment
- [ ] Performance interview records

#### 8. Reporting System
- [ ] HR statistics reports
- [ ] Attendance analysis reports
- [ ] Payroll cost analysis
- [ ] Custom report builder
- [ ] Export to Excel/PDF

### Long-term Goals (6-12 months)

#### 9. Mobile Applications
- [ ] Develop iOS App
- [ ] Develop Android App
- [ ] Mobile clock in/out functionality
- [ ] Push notifications

#### 10. Advanced Features
- [ ] AI resume screening
- [ ] Shift optimization algorithms
- [ ] Turnover risk prediction
- [ ] Salary market analysis

#### 11. Integration Features
- [ ] Integrate with accounting systems
- [ ] Integrate with access control systems
- [ ] Integrate with Google Calendar
- [ ] Integrate with Slack/Teams

#### 12. Multi-language Support
- [ ] Multi-language interface
- [ ] Multi-timezone support
- [ ] Adapt to labor laws in different countries
- [ ] Multi-currency payroll

### Technical Debt & Optimization

#### Performance Optimization
- [ ] Database query optimization
- [ ] Redis caching strategy
- [ ] Frontend resource minification
- [ ] CDN deployment

#### Code Quality
- [ ] Increase test coverage to 80%+
- [ ] Refactor legacy code
- [ ] Build CI/CD Pipeline
- [ ] Automated deployment workflow

#### Documentation Improvement
- [ ] API documentation auto-generation
- [ ] User operation manual
- [ ] Developer guide
- [ ] Architecture design documentation

---

## Appendices

### A. Frequently Asked Questions (FAQ)

**Q1: How to reset admin password?**

A: Use tinker to reset:
```bash
php artisan tinker
>>> $user = User::where('email', 'admin@erp.local')->first();
>>> $user->password = bcrypt('new-password');
>>> $user->save();
```

**Q2: How to clear cache?**

A: Run clear commands:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

**Q3: How to reset test environment data?**

A: Use migrate:fresh:
```bash
php artisan migrate:fresh --seed
```

**Q4: How to add custom permissions?**

A: Add in `AccessControlSeeder` and re-seed:
```php
Permission::create([
    'code' => 'custom.permission',
    'name' => 'Custom Permission',
    'module' => 'custom',
]);
```

**Q5: How to modify default company data?**

A: Edit `CompanyDataSeeder.php` and re-run:
```bash
php artisan db:seed --class=CompanyDataSeeder
```

### B. Data Dictionary

#### Employee Status Codes
- `active`: Active
- `on_leave`: On leave
- `resigned`: Resigned
- `retired`: Retired
- `terminated`: Terminated

#### Leave Status Codes
- `pending`: Pending approval
- `approved`: Approved
- `rejected`: Rejected
- `cancelled`: Cancelled

#### Payroll Status Codes
- `draft`: Draft
- `processing`: Processing
- `completed`: Completed
- `paid`: Paid
- `cancelled`: Cancelled

#### Clock Types
- `check-in`: Check in
- `check-out`: Check out
- `break-out`: Break start
- `break-in`: Break end

### C. Insurance Bracket Reference Table

Reference: Ministry of Labor announcement (2023)

| Grade | Monthly Insured Salary | Labor Insurance (Employee) | Health Insurance (Employee) |
|-------|------------------------|----------------------------|------------------------------|
| 1 | 27,470 | 687 | 412 |
| 2 | 28,800 | 720 | 432 |
| 3 | 30,300 | 758 | 455 |
| 4 | 31,800 | 795 | 477 |
| 5 | 33,300 | 833 | 500 |
| ... | ... | ... | ... |

Complete data seeded by `InsuranceBracketSeeder`.

### D. Position Level Reference Table

| Code | Name | Level |
|------|------|-------|
| P01 | Chairman | 1 |
| P02 | General Manager | 2 |
| P03 | Vice General Manager | 3 |
| P04 | Assistant General Manager | 4 |
| P05 | Director/Manager | 5 |
| P06 | Deputy Manager | 6 |
| P07 | Section Chief/Team Leader | 7 |
| P08 | Supervisor | 8 |
| P09 | Senior Specialist | 9 |
| P10 | Specialist/Staff | 10 |
| P11 | Assistant | 11 |

### E. Contact Information

- **Project Lead**: [Your Name]
- **Email**: [your.email@example.com]
- **Documentation Version**: 1.0.0
- **Last Updated**: 2025-10-22

---

## Change History

| Version | Date | Description |
|---------|------|-------------|
| 1.0.0 | 2025-10-22 | Initial version with complete project documentation |

---

**End of Documentation**
