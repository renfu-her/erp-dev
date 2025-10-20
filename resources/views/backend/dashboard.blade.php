@extends('layouts.app')

@section('title', 'Backend Control Center')

@section('content')
    <section>
        <div class="mb-4">
            <h2 class="page-title fw-bold">Backend Dashboard</h2>
            <p class="text-muted">集中管理 ERP 後台模組。可依需求串接 `/api/backend` 取得即時資料。</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h3 class="card-title h5">Getting Started</h3>
                        <ul class="mt-3 ps-3 mb-0 small text-muted">
                            <li class="mb-2">建立公司、部門、員工等基礎維運表單。</li>
                            <li class="mb-2">透過 Sanctum 保護後台 API 並整合 Blade 介面。</li>
                            <li>運用 Laravel Policy/Middleware 強化 HR 控制權。</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h3 class="card-title h5">Next Enhancements</h3>
                        <p class="card-text small text-muted mb-2">加入待審核清單、阻擋員工統計及員工進程圖表。</p>
                        <p class="card-text small text-muted mb-0">可搭配 Blade Components、jQuery 或其他前端框架擴充互動功能。</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
