<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'ERP Platform') }} - @yield('title', '系統')</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body class="app-shell">
        <div class="app-layout d-lg-flex min-vh-100">
            <nav id="sidebarMenu" class="app-sidebar offcanvas-lg offcanvas-start bg-white border-end">
                <div class="offcanvas-header border-bottom d-lg-none">
                    <h2 class="h5 mb-0 fw-semibold">{{ config('app.name', 'ERP Platform') }}</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body d-flex flex-column px-3 px-lg-2">
                    <div class="app-sidebar-brand d-none d-lg-flex align-items-center px-2 mb-4">
                        <a class="fw-semibold text-decoration-none text-dark" href="{{ route('frontend.home') }}">
                            {{ config('app.name', 'ERP Platform') }}
                        </a>
                    </div>

                    <nav class="app-sidebar-nav flex-column gap-3 small">
                        <div>
                            <div class="text-uppercase text-muted fw-semibold small mb-2 px-2">前臺入口</div>
                            <a class="nav-link px-3 py-2 rounded {{ request()->routeIs('frontend.home') ? 'active' : '' }}" href="{{ route('frontend.home') }}">
                                前臺首頁
                            </a>
                            @can('frontend.portal.access')
                                <a class="nav-link px-3 py-2 rounded {{ request()->routeIs('frontend.hr.self-service') ? 'active' : '' }}" href="{{ route('frontend.hr.self-service') }}">
                                    員工自助服務
                                </a>
                                <a class="nav-link px-3 py-2 rounded {{ request()->routeIs('frontend.hr.leave-request') ? 'active' : '' }}" href="{{ route('frontend.hr.leave-request') }}">
                                    請假申請
                                </a>
                            @endcan
                        </div>

                        @can('backend.access')
                            <div>
                                <div class="text-uppercase text-muted fw-semibold small mb-2 px-2">後台模組</div>
                                <a class="nav-link px-3 py-2 rounded {{ request()->routeIs('backend.dashboard') ? 'active' : '' }}" href="{{ route('backend.dashboard') }}">後台總覽</a>
                                <a class="nav-link px-3 py-2 rounded {{ request()->routeIs('backend.hr.dashboard') ? 'active' : '' }}" href="{{ route('backend.hr.dashboard') }}">HR 控制台</a>
                                <a class="nav-link px-3 py-2 rounded {{ request()->routeIs('backend.attendance.*') ? 'active' : '' }}" href="{{ route('backend.attendance.index') }}">出勤管理</a>
                                <a class="nav-link px-3 py-2 rounded {{ request()->routeIs('backend.departments.*') ? 'active' : '' }}" href="{{ route('backend.departments.index') }}">部門管理</a>
                                <a class="nav-link px-3 py-2 rounded {{ request()->routeIs('backend.positions.*') ? 'active' : '' }}" href="{{ route('backend.positions.index') }}">職位管理</a>
                                <a class="nav-link px-3 py-2 rounded {{ request()->routeIs('backend.leave-requests.*') ? 'active' : '' }}" href="{{ route('backend.leave-requests.index') }}">假勤審核</a>
                                <a class="nav-link px-3 py-2 rounded {{ request()->routeIs('backend.leave-types.*') ? 'active' : '' }}" href="{{ route('backend.leave-types.index') }}">假別設定</a>
                                <a class="nav-link px-3 py-2 rounded {{ request()->routeIs('backend.companies.*') ? 'active' : '' }}" href="{{ route('backend.companies.index') }}">公司管理</a>
                                <a class="nav-link px-3 py-2 rounded {{ request()->routeIs('backend.employees.*') ? 'active' : '' }}" href="{{ route('backend.employees.index') }}">員工管理</a>
                                <a class="nav-link px-3 py-2 rounded {{ request()->routeIs('backend.payroll.*') ? 'active' : '' }}" href="{{ route('backend.payroll.index') }}">薪資概況</a>
                            </div>
                        @endcan
                    </nav>

                    <div class="mt-auto pt-4 border-top">
                        @auth
                            <div class="px-2 mb-2 text-muted small">登入為：{{ auth()->user()->name ?? auth()->user()->email }}</div>
                            <form action="{{ route('logout') }}" method="POST" class="px-2">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100">登出</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm w-100">登入系統</a>
                        @endauth
                    </div>
                </div>
            </nav>

            <div class="app-main flex-grow-1 d-flex flex-column min-vh-100">
                <header class="app-topbar border-bottom bg-white d-lg-none">
                    <div class="container-fluid py-2 d-flex justify-content-between align-items-center">
                        <a class="fw-semibold text-decoration-none text-dark" href="{{ route('frontend.home') }}">
                            {{ config('app.name', 'ERP Platform') }}
                        </a>
                        <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
                            功能選單
                        </button>
                    </div>
                </header>

                <main class="app-content flex-grow-1 py-4">
                    <div class="container-fluid container-xxl">
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <h6 class="mb-2">表單提交存在以下問題：</h6>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </main>

                <footer class="border-top bg-white py-3">
                    <div class="container-fluid container-xxl text-center app-footer">
                        &copy; {{ now()->year }} {{ config('app.name', 'ERP Platform') }}. All rights reserved.
                    </div>
                </footer>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-3gJwYp4gk+SeE/PrN0marIXDRm9C+X1Hp1N9f2Q6Y7E=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        @stack('scripts')
    </body>
</html>
