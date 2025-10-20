<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermission('role.manage') ?? false;
    }

    public function rules(): array
    {
        $roleId = $this->route('role')?->id ?? null;

        return [
            'slug' => [
                'sometimes',
                'string',
                'max:64',
                Rule::unique('roles', 'slug')->ignore($roleId),
            ],
            'display_name' => ['sometimes', 'string', 'max:255'],
            'level' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'permissions' => ['sometimes', 'array'],
            'permissions.*' => ['string', 'exists:permissions,slug'],
        ];
    }
}
