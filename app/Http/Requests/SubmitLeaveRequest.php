<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Employee;
use Illuminate\Validation\Rule;

class SubmitLeaveRequest extends FormRequest
{
    public const ALLOWED_LEAVE_NAMES = [
        '特別休假',
        '婚假',
        '喪假',
        '產假',
        '陪產假',
        '產檢假',
        '流產假',
        '事假',
        '公假',
        '普通傷病假',
        '公傷病假',
        '生理假',
        '家庭照顧假',
        '育嬰留職停薪假',
    ];

    public function authorize(): bool
    {
        return $this->user()?->hasPermission('frontend.leave.submit') ?? false;
    }

    public function rules(): array
    {
        return [
            'leave_type_id' => [
                'required',
                Rule::exists('leave_types', 'id')->where(function ($query) {
                    $query->whereIn('name', self::ALLOWED_LEAVE_NAMES);
                }),
            ],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['nullable', 'string', 'max:1000'],
            'delegate_employee_id' => [
                'nullable',
                'integer',
                Rule::exists('employees', 'id'),
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $delegateId = $this->input('delegate_employee_id');
            $employee = $this->user()?->employee;

            if (! $delegateId) {
                return;
            }

            $delegate = Employee::with('user.roles')->find($delegateId);
            if (! $delegate) {
                return;
            }

            if ($employee && $delegate->id === $employee->id) {
                $validator->errors()->add('delegate_employee_id', '代理人不得為申請人本人。');
            }

            if ($employee && $delegate->company_id !== $employee->company_id) {
                $validator->errors()->add('delegate_employee_id', '代理人需與申請人同公司。');
            }

            if ($delegate->user && $delegate->user->roles()->where('slug', 'system-owner')->exists()) {
                $validator->errors()->add('delegate_employee_id', '代理人不得為系統管理者。');
            }
        });
    }
}
