@php
    $isEdit = $employee->exists ?? false;
    $formMethod = $formMethod ?? 'POST';
    $submitLabel = $submitLabel ?? ($isEdit ? '儲存變更' : '建立員工');
    $pageTitle = $pageTitle ?? ($isEdit ? '編輯員工' : '新增員工');
    $pageDescription = $pageDescription ?? ($isEdit ? '更新員工基本資訊，調整後將影響人力資源相關流程。' : '輸入員工基本資料，建立後即可透過其他模組進行進一步設定。');
    $currentYear = $currentYear ?? now()->year;
@endphp

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title h3 mb-2">{{ $pageTitle }}</h2>
                <p class="card-text text-muted">{{ $pageDescription }}</p>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ $formAction }}" method="POST" class="row g-3">
                    @csrf
                    @if (strtoupper($formMethod) === 'PUT')
                        @method('PUT')
                    @endif

                    <div class="col-md-4">
                        <label for="company_id" class="form-label">公司</label>
                        <select name="company_id" id="company_id" class="form-select" required>
                            <option value="" {{ old('company_id', $employee->company_id) ? '' : 'selected' }} disabled>請選擇</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id', $employee->company_id) == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="department_id" class="form-label">部門</label>
                        <select name="department_id" id="department_id" class="form-select">
                            <option value="">未指定</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="position_id" class="form-label">職位</label>
                        <select name="position_id" id="position_id" class="form-select">
                            <option value="">未指定</option>
                            @foreach ($positions as $position)
                                <option value="{{ $position->id }}" {{ old('position_id', $employee->position_id) == $position->id ? 'selected' : '' }}>
                                    {{ $position->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="employee_no" class="form-label">員工編號</label>
                        <input type="text" name="employee_no" id="employee_no" value="{{ old('employee_no', $employee->employee_no) }}" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label for="last_name" class="form-label">姓氏</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $employee->last_name) }}" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label for="first_name" class="form-label">名字</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $employee->first_name) }}" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label for="middle_name" class="form-label">中間名</label>
                        <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name', $employee->middle_name) }}" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label for="salary_grade" class="form-label">薪資等級</label>
                        <input type="text" name="salary_grade" id="salary_grade" value="{{ old('salary_grade', $employee->salary_grade) }}" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label for="labor_grade" class="form-label">勞工等級</label>
                        <input type="text" name="labor_grade" id="labor_grade" value="{{ old('labor_grade', $employee->labor_grade) }}" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">身份屬性</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_indigenous" id="is_indigenous" value="1" {{ old('is_indigenous', $employee->is_indigenous) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_indigenous">原住民</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_disabled" id="is_disabled" value="1" {{ old('is_disabled', $employee->is_disabled) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_disabled">身心障礙</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="status" class="form-label">狀態</label>
                        <select name="status" id="status" class="form-select">
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" {{ old('status', $employee->status ?? 'active') === $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="hired_at" class="form-label">到職日</label>
                        <input type="date" name="hired_at" id="hired_at" value="{{ old('hired_at', optional($employee->hired_at)->format('Y-m-d')) }}" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label for="terminated_at" class="form-label">離職日</label>
                        <input type="date" name="terminated_at" id="terminated_at" value="{{ old('terminated_at', optional($employee->terminated_at)->format('Y-m-d')) }}" class="form-control">
                    </div>

                    <div class="col-12 d-flex justify-content-end gap-2">
                        <a href="{{ route('backend.employees.index') }}" class="btn btn-outline-secondary">取消</a>
                        <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
                    </div>
                </form>
            </div>
        </div>

        @if ($isEdit && isset($leaveSummaries) && $leaveSummaries->isNotEmpty())
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title h5 mb-0">員工假別概況</h3>
                    <span class="badge bg-light text-muted">年度：{{ $currentYear }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light small text-muted text-uppercase">
                                <tr>
                                    <th scope="col" class="text-nowrap">假別</th>
                                    <th scope="col" class="text-nowrap">工資給付</th>
                                    <th scope="col" class="text-end text-nowrap">年度配額</th>
                                    <th scope="col" class="text-end text-nowrap">已使用</th>
                                    <th scope="col" class="text-end text-nowrap">剩餘</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leaveSummaries as $summary)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold text-body">{{ $summary['type']->name }}</div>
                                            @if (!is_null($summary['type']->default_quota))
                                                <div class="text-muted small">預設配額：{{ rtrim(rtrim(number_format($summary['type']->default_quota, 2, '.', ''), '0'), '.') }} 天</div>
                                            @endif
                                            @if (! empty($summary['notes']))
                                                <div class="text-muted small">{{ $summary['notes'] }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark fw-normal">{{ $summary['pay'] ?? '依公司規定' }}</span>
                                        </td>
                                        <td class="text-end text-nowrap">{{ rtrim(rtrim(number_format($summary['entitled'], 2, '.', ''), '0'), '.') }} 天</td>
                                        <td class="text-end text-nowrap">{{ rtrim(rtrim(number_format($summary['taken'], 2, '.', ''), '0'), '.') }} 天</td>
                                        <td class="text-end text-nowrap">{{ rtrim(rtrim(number_format($summary['remaining'], 2, '.', ''), '0'), '.') }} 天</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-muted small">
                        假別資料依據年度留存，若需調整請透過假別設定或匯入工具更新 leave_balances。
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
