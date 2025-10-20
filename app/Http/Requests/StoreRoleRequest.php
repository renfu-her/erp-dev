<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermission('role.manage') ?? false;
    }

    public function rules(): array
    {
        return [
            'slug' => ['required', 'string', 'max:64', 'unique:roles,slug'],
            'display_name' => ['required', 'string', 'max:255'],
            'level' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'permissions' => ['sometimes', 'array'],
            'permissions.*' => ['string', 'exists:permissions,slug'],
        ];
    }
}
