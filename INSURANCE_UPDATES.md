# Insurance Bracket System Updates

## Summary

Updated the insurance bracket system to correctly handle Taiwan's 2025 (民國114年) labor and health insurance contribution data.

## Changes Made

### 1. Fixed Column Mapping Bugs
**File:** `app/Support/InsuranceSchedule.php`

- **Line 94-95**: Fixed incorrect column indices for foreign labor insurance (was using wrong indices)
- **Line 96-97**: Fixed health insurance column mapping (was off by one)
- **Line 98**: Removed pension_employer calculation (not included in source data)
- **Line 148**: Fixed `findBracketForSalary()` to compare salary with `$bracket['salary']` instead of `$bracket['grade']`

### 2. Extended Insurance Data
**File:** `storage/salary_table.json`

- Extended data from 13 grades to 20 grades
- Added grades 14-20 with official 2025 contribution amounts
- Complete salary range now: NT$27,470 - NT$63,800

### 3. Added Salary Column to Database
**New Migration:** `2025_10_22_163432_add_salary_to_insurance_brackets_table.php`

- Added `salary` column to `insurance_brackets` table
- Type: `unsignedInteger`, nullable
- Position: After `grade` column

### 4. Updated Model
**File:** `app/Models/InsuranceBracket.php`

- Added `salary` to `$fillable` array
- Added `salary` to `$casts` array (as integer)

### 5. Updated Seeder
**File:** `database/seeders/InsuranceBracketSeeder.php`

- Simplified to use actual data from JSON instead of recalculating
- Now saves the `salary` field to database
- Uses official contribution amounts directly

### 6. Updated Schedule Loading
**File:** `app/Support/InsuranceSchedule.php`

- Updated `fromDatabase()` to include `salary` field in returned data
- Ensures salary lookups work correctly from both database and JSON sources

## Data Structure

Each insurance bracket now contains:
- `label`: Grade label (e.g., "第1級")
- `grade`: Sequential grade number (1-20)
- `salary`: Monthly insured salary (NT$)
- `labor_employee_local`: Labor insurance employee contribution
- `labor_employer_local`: Labor insurance employer contribution
- `labor_employee_foreign`: Labor insurance employee contribution (foreign workers)
- `labor_employer_foreign`: Labor insurance employer contribution (foreign workers)
- `health_employee`: Health insurance employee contribution
- `health_employer`: Health insurance employer contribution
- `pension_employer`: Pension contribution (employer)

## Testing

All functionality verified:
- Database seeding: ✅ 20 brackets loaded
- Salary lookup: ✅ Correctly finds appropriate bracket
- Data integrity: ✅ All official 2025 rates preserved

## Usage Example

```php
use App\Support\InsuranceSchedule;

// Load from database
$schedule = InsuranceSchedule::fromDatabase();

// Find bracket for a salary
$bracket = $schedule->findBracketForSalary(35000);
// Returns: Grade 8 (月投保薪資: 36,300)
// Labor employee: 834, Health employee: 563

// Find by specific grade
$bracket = $schedule->findBracketByGrade(10);
// Returns: Grade 10 with all contribution details
```

## Migration Commands

```bash
# Run the new migration
php artisan migrate

# Seed the updated insurance data
php artisan db:seed --class=InsuranceBracketSeeder
```

## References

Data sourced from:
- National Chi Nan University official 2025 insurance contribution tables
- Taiwan Labor Insurance Bureau 2025 rates (effective 2025-01-01)
- Health insurance rate: 5.17%
- Labor insurance rate: 11.5% (employee 20%, employer 70%, government 10%)

## Notes

- The pension employer contribution is set to `null` in the current data as it requires separate calculation based on company policies
- Foreign worker rates currently mirror local worker rates (can be adjusted per policy)
- All amounts are in NT$ (New Taiwan Dollars)

