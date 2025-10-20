@extends('layouts.app')

@section('title', '編輯員工')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title h3 mb-2">編輯員工</h2>
                    <p class="card-text text-muted">更新員工基本資訊，調整後將影響人力資源相關流程。</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('backend.employees.update', $employee) }}" method="POST" class="row g-3">
                        @csrf
                        @method('PUT')

                        <div class="col-md-4">
                            <label for="company_id" class="form-label">公司</label>
                            <select name="company_id" id="company_id" class="form-select" required>
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
                                    <option value="{{ $status }}" {{ old('status', $employee->status) === $status ? 'selected' : '' }}>{{ $status }}</option>
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
                            <button type="submit" class="btn btn-primary">儲存變更</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
