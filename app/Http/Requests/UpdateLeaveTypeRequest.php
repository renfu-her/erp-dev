<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLeaveTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $leaveTypeId = $this->route('leave_type')?->id ?? null;

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'code' => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('leave_types', 'code')->ignore($leaveTypeId),
            ],
            'requires_approval' => ['sometimes', 'boolean'],
            'default_quota' => ['nullable', 'numeric', 'min:0'],
            'affects_attendance' => ['sometimes', 'boolean'],
            'rules' => ['nullable', 'string'],
        ];
    }
}
