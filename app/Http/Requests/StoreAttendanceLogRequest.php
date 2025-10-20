<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'exists:employees,id'],
            'recorded_at' => ['required', 'date'],
            'type' => ['required', 'in:check_in,check_out'],
            'source' => ['nullable', 'string', 'max:50'],
            'device_id' => ['nullable', 'exists:attendance_devices,id'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ];
    }
}
