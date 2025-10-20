@extends('layouts.app')

@section('title', '登入系統')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-3 text-center">登入</h1>
                    <p class="text-muted text-center small mb-4">請輸入帳號與密碼，選擇對應入口以進入 ERP 模組。</p>

                    <form method="POST" action="{{ route('login.store') }}" class="row g-3">
                        @csrf

                        <div class="col-12">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="password" class="form-label">密碼</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">記住我</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-grid">
                                        <button type="submit" name="login_target" value="employee" class="btn btn-outline-primary">
                                            員工入口登入
                                        </button>
                                    </div>
                                    <p class="text-muted small mt-2 mb-0">
                                        適用員工自助服務與打卡。需具備 <code>frontend.portal.access</code> 或相關前臺權限。
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-grid">
                                        <button type="submit" name="login_target" value="backend" class="btn btn-primary">
                                            後台管理登入
                                        </button>
                                    </div>
                                    <p class="text-muted small mt-2 mb-0">
                                        適用系統管理員與 HR 維運。需具備 <code>backend.access</code> 及對應功能權限。
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
