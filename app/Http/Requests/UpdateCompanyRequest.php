<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermission('company.manage') ?? false;
    }

    public function rules(): array
    {
        $companyId = $this->route('company')?->id ?? null;

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'code' => [
                'sometimes',
                'string',
                'max:32',
                Rule::unique('companies', 'code')->ignore($companyId),
            ],
            'tax_id' => ['nullable', 'string', 'max:64'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
