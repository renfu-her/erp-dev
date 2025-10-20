@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
    <div class="py-5 text-center">
        <h1 class="display-5 fw-bold">Welcome to ERP Platform</h1>
        <p class="lead text-muted">使用頂層導覽前往前臺或後台模組，開始配置人資與薪資功能。</p>
        <div class="mt-4">
            <a href="{{ route('frontend.hr.self-service') }}" class="btn btn-primary me-2">員工自助服務</a>
            <a href="{{ route('backend.hr.dashboard') }}" class="btn btn-outline-secondary">前往 HR 控制台</a>
        </div>
    </div>
@endsection
