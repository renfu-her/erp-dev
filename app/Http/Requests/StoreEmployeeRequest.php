<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermission('employee.manage') ?? false;
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'position_id' => ['nullable', 'integer', 'exists:positions,id'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'employee_no' => ['required', 'string', 'max:64', 'unique:employees,employee_no'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:32'],
            'national_id' => ['nullable', 'string', 'max:64'],
            'personal_data' => ['nullable', 'array'],
            'status' => ['nullable', 'string', 'in:active,inactive,onboarding,blocked,terminated'],
            'hired_at' => ['nullable', 'date'],
            'terminated_at' => ['nullable', 'date', 'after_or_equal:hired_at'],
            'contacts' => ['nullable', 'array'],
            'contacts.*.type' => ['required_with:contacts', 'string', 'max:32'],
            'contacts.*.value' => ['required_with:contacts', 'string', 'max:255'],
            'contacts.*.is_primary' => ['sometimes', 'boolean'],
            'contacts.*.metadata' => ['nullable', 'array'],
            'addresses' => ['nullable', 'array'],
            'addresses.*.type' => ['required_with:addresses', 'string', 'max:32'],
            'addresses.*.line1' => ['required_with:addresses', 'string', 'max:255'],
            'addresses.*.line2' => ['nullable', 'string', 'max:255'],
            'addresses.*.city' => ['nullable', 'string', 'max:255'],
            'addresses.*.state' => ['nullable', 'string', 'max:255'],
            'addresses.*.postal_code' => ['nullable', 'string', 'max:32'],
            'addresses.*.country' => ['nullable', 'string', 'size:2'],
            'addresses.*.is_primary' => ['sometimes', 'boolean'],
        ];
    }
}
