<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermission('role.manage') ?? false;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'scope_type' => ['nullable', 'string', 'max:64'],
            'scope_id' => ['nullable', 'integer'],
            'rules' => ['nullable', 'array'],
        ];
    }
}
