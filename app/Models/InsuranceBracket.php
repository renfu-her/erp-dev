<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceBracket extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'grade',
        'labor_employee_local',
        'labor_employer_local',
        'labor_employee_foreign',
        'labor_employer_foreign',
        'health_employee',
        'health_employer',
        'pension_employer',
    ];

    protected $casts = [
        'grade' => 'integer',
        'labor_employee_local' => 'integer',
        'labor_employer_local' => 'integer',
        'labor_employee_foreign' => 'integer',
        'labor_employer_foreign' => 'integer',
        'health_employee' => 'integer',
        'health_employer' => 'integer',
        'pension_employer' => 'integer',
    ];
}

