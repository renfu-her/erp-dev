@extends('layouts.app')

@section('title', '新增部門')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title h3 mb-2">新增部門</h2>
                    <p class="card-text text-muted">建立新的部門，並可設定上層部門與主管。</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('backend.departments.store') }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-md-6">
                            <label for="company_id" class="form-label">公司</label>
                            <select name="company_id" id="company_id" class="form-select" required>
                                <option value="" disabled selected>請選擇</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="form-label">部門名稱</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="code" class="form-label">部門代碼</label>
                            <input type="text" name="code" id="code" value="{{ old('code') }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="parent_id" class="form-label">上層部門</label>
                            <select name="parent_id" id="parent_id" class="form-select">
                                <option value="">無</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('parent_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }} ({{ $dept->company->name ?? '—' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">描述</label>
                            <textarea name="description" id="description" rows="3" class="form-control" placeholder="部門職責或備註">{{ old('description') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label for="lead_employee_id" class="form-label">部門主管</label>
                            <select name="lead_employee_id" id="lead_employee_id" class="form-select">
                                <option value="">未指定</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('lead_employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->last_name }}{{ $employee->first_name }} ({{ $employee->company->name ?? '—' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 d-flex justify-content-end gap-2">
                            <a href="{{ route('backend.departments.index') }}" class="btn btn-outline-secondary">取消</a>
                            <button type="submit" class="btn btn-primary">建立部門</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
