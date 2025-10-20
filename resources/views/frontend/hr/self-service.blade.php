@extends('layouts.app')

@section('title', '員工自助服務')

@section('content')
    <section class="gy-4">
        <div class="card mb-4">
            <div class="card-body d-flex flex-column flex-lg-row align-items-lg-center justify-content-between">
                <div>
                    <h2 class="card-title h3 mb-2">員工自助服務中心</h2>
                    <p class="card-text text-muted mb-0">
                        管理個人資料、假勤申請與公告資訊。所有操作皆會同步至 HR 後台模組。
                    </p>
                </div>
                <div class="mt-3 mt-lg-0">
                    <a href="{{ route('frontend.home') }}" class="btn btn-dark me-2">返回前臺</a>
                    <a href="{{ route('backend.hr.dashboard') }}" class="btn btn-outline-secondary">前往 HR 後台</a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title h5 mb-0">個人資料摘要</h3>
                        <button type="button" class="btn btn-sm btn-outline-secondary">編輯資料</button>
                    </div>
                    <div class="card-body">
                        @php
                            $employee = auth()->user()?->employee;
                        @endphp
                        @if ($employee)
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <small class="text-muted d-block">員工編號</small>
                                    <span class="fw-semibold">{{ $employee->employee_no }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <small class="text-muted d-block">部門 / 職稱</small>
                                    <span class="fw-semibold">
                                        {{ $employee->department->name ?? '—' }}
                                        @if ($employee->position)
                                            · {{ $employee->position->title }}
                                        @endif
                                    </span>
                                </div>
                                <div class="col-sm-6">
                                    <small class="text-muted d-block">到職日</small>
                                    <span class="fw-semibold">{{ optional($employee->hired_at)->format('Y-m-d') ?? '—' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <small class="text-muted d-block">狀態</small>
                                    <span class="badge bg-success">{{ ucfirst($employee->status) }}</span>
                                </div>
                            </div>
                        @else
                            <p class="text-muted small mb-0">尚未綁定員工資料。</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="card-title h5 mb-0">最新公告</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0 small text-muted">
                            <li class="mb-2"><span class="fw-semibold text-dark">2024-11-18：</span>12 月 1 日起啟用新版差旅報銷流程。</li>
                            <li class="mb-2"><span class="fw-semibold text-dark">2024-11-15：</span>年度績效面談預約已開放，請於 11 月底前完成。</li>
                            <li><span class="fw-semibold text-dark">2024-11-08：</span>新增「家庭照護假」項目，詳情請洽 HR。</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-lg-6">
                <x-employee-attendance title="我的打卡紀錄" />
            </div>
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h3 class="card-title h5">請假申請</h3>
                        <p class="card-text text-muted small">提交假單後將自動送交直屬主管與 HR 審核，審核結果會以通知與信件同步告知。</p>
                        <ul class="small text-muted mb-3 ps-3">
                            <li>填寫假別、日期與說明。</li>
                            <li>追蹤審核進度與餘額。</li>
                            <li>支援補休與加班兌換流程。</li>
                        </ul>
                        <a href="{{ route('frontend.hr.leave-request') }}" class="btn btn-primary">前往請假申請</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
