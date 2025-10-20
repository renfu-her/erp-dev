@extends('layouts.app')

@section('title', '員工入口')

@section('content')
    <section class="gy-4">
        <div class="card mb-4">
            <div class="card-body d-flex flex-column flex-lg-row align-items-lg-center justify-content-between">
                <div>
                    <h2 class="card-title h3 mb-2">Frontend Portal</h2>
                    <p class="card-text text-muted mb-0">登入後即可進行打卡、閱覽公告並前往常用模組。</p>
                </div>
                @auth
                    <div class="mt-3 mt-lg-0">
                        <a href="{{ route('frontend.hr.self-service') }}" class="btn btn-outline-secondary">前往員工自助服務</a>
                    </div>
                @endauth
            </div>
        </div>

        @auth
            @php
                $employee = auth()->user()?->employee;
            @endphp
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h3 class="card-title h5">打卡操作</h3>
                            @if ($employee)
                                <p class="card-text text-muted small">請選擇下方按鈕完成上班 / 下班打卡，系統會自動記錄時間。</p>
                                <div class="d-flex gap-3 flex-wrap">
                                    <form method="POST" action="{{ route('frontend.attendance.store', 'check-in') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success">上班打卡</button>
                                    </form>
                                    <form method="POST" action="{{ route('frontend.attendance.store', 'check-out') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary">下班打卡</button>
                                    </form>
                                </div>
                                @error('attendance')
                                    <p class="text-danger small mt-3">{{ $message }}</p>
                                @enderror
                            @else
                                <p class="text-muted small mb-0">尚未綁定員工資料，請聯絡 HR 建立帳號。</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <x-employee-attendance title="近期打卡紀錄" />
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <h3 class="card-title h5 mb-3">尚未登入</h3>
                    <p class="card-text text-muted mb-4">請先登入帳號，即可進行打卡與存取員工資源。</p>
                    <a href="{{ route('login') }}" class="btn btn-primary">前往登入</a>
                </div>
            </div>
        @endauth

        <div class="row g-4 mt-1">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h3 class="card-title h5">公告資訊</h3>
                        <ul class="mt-3 ps-3 mb-0 small text-muted">
                            <li class="mb-2">2024-11-18：12 月 1 日起啟用新版差旅報銷流程。</li>
                            <li class="mb-2">2024-11-15：年度績效面談預約已開放，請於 11 月底前完成。</li>
                            <li>2024-11-08：新增「家庭照護假」項目，詳情請洽 HR。</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h3 class="card-title h5">常用連結</h3>
                        <ul class="list-unstyled small text-muted mb-0">
                            <li class="mb-2"><a class="text-decoration-none" href="{{ route('frontend.hr.self-service') }}">員工自助服務中心</a></li>
                            <li class="mb-2"><a class="text-decoration-none" href="{{ route('frontend.hr.leave-request') }}">請假申請</a></li>
                            <li><span class="text-muted">即將推出：薪資查詢、教育訓練</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
