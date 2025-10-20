@extends('layouts.app')

@section('title', '提交請假申請')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title h3 mb-2">請假申請表</h2>
                    <p class="card-text text-muted">填寫假別與期間後送出，HR 與主管將在後台進行審核。</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('frontend.hr.leave-request.submit') }}" method="POST" class="row g-3">
                        @csrf

                        <div class="col-12">
                            <label class="form-label">申請人</label>
                            <input type="text" class="form-control" value="{{ $employee->last_name }}{{ $employee->first_name }} ({{ $employee->employee_no }})" disabled>
                            <div class="form-text">請假申請僅能代表本人提交。</div>
                        </div>

                        <div class="col-12">
                            <label for="delegate_employee_id" class="form-label">代理人 <span class="text-muted small">(選填)</span></label>
                            <select name="delegate_employee_id" id="delegate_employee_id" class="form-select @error('delegate_employee_id') is-invalid @enderror">
                                <option value="" {{ old('delegate_employee_id') ? '' : 'selected' }}>無</option>
                                @foreach ($delegates as $delegate)
                                    <option value="{{ $delegate->id }}" {{ old('delegate_employee_id') == $delegate->id ? 'selected' : '' }}>
                                        {{ $delegate->last_name }}{{ $delegate->first_name }} ({{ $delegate->employee_no }})
                                    </option>
                                @endforeach
                            </select>
                            @error('delegate_employee_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="form-text">代理人將協助處理請假期間的工作，不得為系統管理者。</div>
                        </div>

                        <div class="col-12">
                            <label for="leave_type_id" class="form-label">假別</label>
                            <select name="leave_type_id" id="leave_type_id" class="form-select @error('leave_type_id') is-invalid @enderror" required>
                                <option value="" {{ old('leave_type_id') ? '' : 'selected' }} disabled>請選擇</option>
                                @foreach ($leaveTypes as $leaveType)
                                    <option value="{{ $leaveType->id }}" {{ old('leave_type_id') == $leaveType->id ? 'selected' : '' }}>{{ $leaveType->name }}</option>
                                @endforeach
                            </select>
                            @error('leave_type_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="start_date" class="form-label">開始日期</label>
                            <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">結束日期</label>
                            <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="reason" class="form-label">請假說明</label>
                            <textarea name="reason" id="reason" rows="4" class="form-control @error('reason') is-invalid @enderror" placeholder="例如：家庭事件、健康檢查等">{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 d-flex justify-content-end gap-2">
                            <a href="{{ route('frontend.hr.self-service') }}" class="btn btn-outline-secondary">返回自助頁</a>
                            <button type="submit" class="btn btn-primary">送出申請</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
