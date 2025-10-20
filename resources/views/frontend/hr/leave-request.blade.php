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
                            <label for="employee_id" class="form-label">員工</label>
                            <select name="employee_id" id="employee_id" class="form-select" required>
                                <option value="" selected disabled>請選擇</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">
                                        {{ $employee->last_name }}{{ $employee->first_name }} ({{ $employee->employee_no }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="leave_type_id" class="form-label">假別</label>
                            <select name="leave_type_id" id="leave_type_id" class="form-select" required>
                                <option value="" selected disabled>請選擇</option>
                                @foreach ($leaveTypes as $leaveType)
                                    <option value="{{ $leaveType->id }}">{{ $leaveType->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="start_date" class="form-label">開始日期</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">結束日期</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" required>
                        </div>

                        <div class="col-12">
                            <label for="reason" class="form-label">請假說明</label>
                            <textarea name="reason" id="reason" rows="4" class="form-control" placeholder="例如：家庭事件、健康檢查等"></textarea>
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
