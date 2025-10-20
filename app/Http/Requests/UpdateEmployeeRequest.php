<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermission('employee.manage') ?? false;
    }

    public function rules(): array
    {
        $employeeId = $this->route('employee')?->id ?? null;

        return [
            'company_id' => ['sometimes', 'integer', 'exists:companies,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'position_id' => ['nullable', 'integer', 'exists:positions,id'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'employee_no' => [
                'sometimes',
                'string',
                'max:64',
                Rule::unique('employees', 'employee_no')->ignore($employeeId),
            ],
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:32'],
            'national_id' => ['nullable', 'string', 'max:64'],
            'personal_data' => ['nullable', 'array'],
            'status' => ['nullable', 'string', 'in:active,inactive,onboarding,blocked,terminated'],
            'hired_at' => ['nullable', 'date'],
            'terminated_at' => ['nullable', 'date', 'after_or_equal:hired_at'],
        ];
    }
}
