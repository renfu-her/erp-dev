@extends('layouts.app')

@section('title', 'HR 後台操作介面')

@section('content')
    <div class="row gy-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body d-flex flex-column flex-lg-row align-items-lg-center justify-content-between">
                    <div>
                        <h2 class="card-title h3 mb-2">Human Resource 控制中心</h2>
                        <p class="card-text text-muted mb-0">
                            從此介面管理公司結構、員工資料與人力資源流程。所有操作皆透過 Blade 表單提交，與 API 分離實作。
                        </p>
                    </div>
                    <div class="mt-3 mt-lg-0">
                        <a href="{{ route('frontend.hr.self-service') }}" class="btn btn-outline-secondary me-2">切換員工自助介面</a>
                        <a href="{{ route('backend.companies.create') }}" class="btn btn-primary">建立公司</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="card-title h5">公司管理</h3>
                    <p class="card-text text-muted small">
                        檢視公司清單、調整代碼與統編，為後續部門與員工建立清晰層級架構。
                    </p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('backend.companies.index') }}" class="btn btn-dark">公司列表</a>
                        <a href="{{ route('backend.companies.create') }}" class="btn btn-outline-secondary">新增公司</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="card-title h5">員工管理</h3>
                    <p class="card-text text-muted small">
                        以 Blade 表單新增或編輯員工資料，並可直接阻擋或解除員工。
                    </p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('backend.employees.index') }}" class="btn btn-dark">員工列表</a>
                        <a href="{{ route('backend.employees.create') }}" class="btn btn-outline-secondary">新增員工</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="card-title h5">人資流程</h3>
                    <p class="card-text text-muted small">
                        後續可擴充假勤審核、阻擋審批等流程頁面，確保 HR 作業一目了然。
                    </p>
                    <button class="btn btn-outline-secondary" type="button" disabled>流程面板 (待開發)</button>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title h5">操作指南</h3>
                    <ol class="text-muted small ps-3 mb-3">
                        <li class="mb-1">先在「公司管理」建立公司，再建立部門與職位。</li>
                        <li class="mb-1">於「員工管理」新增員工，指派公司、部門與職位。</li>
                        <li class="mb-1">如需暫停帳號，使用員工列表中的「阻擋」功能並填寫原因。</li>
                        <li>解除阻擋會自動復原員工狀態，持續追蹤活動紀錄。</li>
                    </ol>
                    <p class="text-muted small mb-0">
                        提示：若需保留 API，可在服務層共用商業邏輯，Blade 與 API 控制器皆可呼叫，同時保持權限控管一致。
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
