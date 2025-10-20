<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:leave_types,code'],
            'requires_approval' => ['sometimes', 'boolean'],
            'default_quota' => ['nullable', 'numeric', 'min:0'],
            'affects_attendance' => ['sometimes', 'boolean'],
            'rules' => ['nullable', 'string'],
        ];
    }
}
