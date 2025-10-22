<?php

namespace Tests\Feature;

use App\Models\InsuranceBracket;
use App\Support\InsuranceSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InsuranceScheduleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed insurance brackets
        $this->artisan('db:seed', ['--class' => 'InsuranceBracketSeeder']);
    }

    public function test_loads_insurance_brackets_from_database(): void
    {
        $schedule = InsuranceSchedule::fromDatabase();
        
        $this->assertNotNull($schedule);
        $this->assertCount(36, $schedule->brackets());
    }

    public function test_finds_correct_bracket_for_salary(): void
    {
        $schedule = InsuranceSchedule::fromDatabase();
        
        // Test various salary levels
        $bracket1 = $schedule->findBracketForSalary(30000);
        $this->assertEquals(2, $bracket1['grade']);
        $this->assertEquals(30000, $bracket1['salary']);
        
        $bracket2 = $schedule->findBracketForSalary(50000);
        $this->assertEquals(12, $bracket2['grade']);
        $this->assertEquals(50000, $bracket2['salary']);
        
        $bracket3 = $schedule->findBracketForSalary(80000);
        $this->assertEquals(22, $bracket3['grade']);
        $this->assertEquals(80000, $bracket3['salary']);
    }

    public function test_returns_highest_bracket_for_salary_above_maximum(): void
    {
        $schedule = InsuranceSchedule::fromDatabase();
        
        $bracket = $schedule->findBracketForSalary(200000);
        
        $this->assertEquals(36, $bracket['grade']);
        $this->assertEquals(150000, $bracket['salary']);
    }

    public function test_finds_bracket_by_grade(): void
    {
        $schedule = InsuranceSchedule::fromDatabase();
        
        $bracket = $schedule->findBracketByGrade(10);
        
        $this->assertNotNull($bracket);
        $this->assertEquals(10, $bracket['grade']);
        $this->assertEquals(45800, $bracket['salary']);
        $this->assertEquals(1054, $bracket['labor_employee_local']);
        $this->assertEquals(3686, $bracket['labor_employer_local']);
    }

    public function test_insurance_bracket_model_has_all_fields(): void
    {
        $bracket = InsuranceBracket::where('grade', 1)->first();
        
        $this->assertNotNull($bracket);
        $this->assertEquals(1, $bracket->grade);
        $this->assertEquals(28590, $bracket->salary);
        $this->assertEquals(658, $bracket->labor_employee_local);
        $this->assertEquals(2301, $bracket->labor_employer_local);
        $this->assertEquals(444, $bracket->health_employee);
        $this->assertEquals(886, $bracket->health_employer);
        $this->assertEquals(60, $bracket->occupational_employer);
        $this->assertEquals(1715, $bracket->labor_pension_6_percent);
    }

    public function test_all_brackets_have_valid_data(): void
    {
        $schedule = InsuranceSchedule::fromDatabase();
        
        foreach ($schedule->brackets() as $bracket) {
            $this->assertArrayHasKey('grade', $bracket);
            $this->assertArrayHasKey('salary', $bracket);
            $this->assertArrayHasKey('labor_employee_local', $bracket);
            $this->assertArrayHasKey('health_employee', $bracket);
            
            $this->assertIsInt($bracket['grade']);
            $this->assertIsInt($bracket['salary']);
            $this->assertGreaterThan(0, $bracket['salary']);
        }
    }

    public function test_loads_from_storage_json_file(): void
    {
        $schedule = InsuranceSchedule::fromStorage();
        
        $this->assertNotNull($schedule);
        $this->assertCount(36, $schedule->brackets());
        
        $brackets = $schedule->brackets();
        $first = reset($brackets);
        $last = end($brackets);
        
        $this->assertEquals(1, $first['grade']);
        $this->assertEquals(28590, $first['salary']);
        
        $this->assertEquals(36, $last['grade']);
        $this->assertEquals(150000, $last['salary']);
    }
}

