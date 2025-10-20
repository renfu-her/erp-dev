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

                    @php
                        $frontendMenu = [
                            [
                                'label' => '前臺首頁',
                                'route' => 'frontend.home',
                                'active' => ['frontend.home'],
                                'permissions' => [],
                            ],
                            [
                                'label' => '員工自助服務',
                                'route' => 'frontend.hr.self-service',
                                'active' => ['frontend.hr.self-service'],
                                'permissions' => ['frontend.portal.access'],
                            ],
                            [
                                'label' => '請假申請',
                                'route' => 'frontend.hr.leave-request',
                                'active' => ['frontend.hr.leave-request'],
                                'permissions' => ['frontend.leave.submit'],
                            ],
                        ];

                        $backendMenu = [
                            [
                                'label' => '後台總覽',
                                'route' => 'backend.dashboard',
                                'active' => ['backend.dashboard'],
                                'permissions' => ['backend.access'],
                            ],
                            [
                                'label' => 'HR 控制台',
                                'route' => 'backend.hr.dashboard',
                                'active' => ['backend.hr.dashboard'],
                                'permissions' => ['backend.access'],
                            ],
                            [
                                'label' => '出勤管理',
                                'route' => 'backend.attendance.index',
                                'active' => ['backend.attendance.*'],
                                'permissions' => ['backend.access', 'attendance.manage'],
                            ],
                            [
                                'label' => '部門管理',
                                'route' => 'backend.departments.index',
                                'active' => ['backend.departments.*'],
                                'permissions' => ['backend.access', 'company.manage'],
                            ],
                            [
                                'label' => '職位管理',
                                'route' => 'backend.positions.index',
                                'active' => ['backend.positions.*'],
                                'permissions' => ['backend.access', 'company.manage'],
                            ],
                            [
                                'label' => '假勤審核',
                                'route' => 'backend.leave-requests.index',
                                'active' => ['backend.leave-requests.*'],
                                'permissions' => ['backend.access', 'attendance.manage'],
                            ],
                            [
                                'label' => '假別設定',
                                'route' => 'backend.leave-types.index',
                                'active' => ['backend.leave-types.*'],
                                'permissions' => ['backend.access', 'attendance.manage'],
                            ],
                            [
                                'label' => '公司管理',
                                'route' => 'backend.companies.index',
                                'active' => ['backend.companies.*'],
                                'permissions' => ['backend.access', 'company.manage'],
                            ],
                            [
                                'label' => '員工管理',
                                'route' => 'backend.employees.index',
                                'active' => ['backend.employees.*'],
                                'permissions' => ['backend.access', 'company.manage'],
                            ],
                            [
                                'label' => '薪資概況',
                                'route' => 'backend.payroll.index',
                                'active' => ['backend.payroll.*'],
                                'permissions' => ['backend.access', 'payroll.manage'],
                            ],
                        ];

                        $canSeeBackend = auth()->check() && auth()->user()->can('backend.access');
                    @endphp

                    <nav class="app-sidebar-nav flex-column gap-4 small">
                        <div>
                            <div class="text-uppercase text-muted fw-semibold small mb-2 px-2">前臺入口</div>
                            <div class="d-flex flex-column gap-1">
                                @foreach ($frontendMenu as $item)
                                    @php
                                        $patterns = (array) ($item['active'] ?? $item['route']);
                                        $isActive = false;
                                        foreach ($patterns as $pattern) {
                                            if (request()->routeIs($pattern)) {
                                                $isActive = true;
                                                break;
                                            }
                                        }

                                        $permissions = $item['permissions'] ?? [];
                                        $hasAccess = true;
                                        $badgeText = empty($permissions) ? '公開' : '可存取';
                                        $badgeClass = empty($permissions) ? 'badge-public' : 'badge-access';
                                        if (! empty($permissions)) {
                                            if (!auth()->check()) {
                                                $hasAccess = false;
                                                $badgeText = '需登入';
                                                $badgeClass = 'badge-login';
                                            } else {
                                                $hasAccess = collect($permissions)->every(fn ($permission) => auth()->user()->can($permission));
                                                $badgeText = $hasAccess ? '可存取' : '權限不足';
                                                $badgeClass = $hasAccess ? 'badge-access' : 'badge-locked';
                                            }
                                        }

                                        $linkClasses = 'nav-link px-3 py-2 rounded d-flex flex-column align-items-start gap-1';
                                        if ($isActive) {
                                            $linkClasses .= ' active';
                                        }
                                        if (! $hasAccess && ! empty($permissions)) {
                                            $linkClasses .= ' locked';
                                        }

                                        $url = route($item['route']);
                                    @endphp
                                    <a class="{{ $linkClasses }}" href="{{ $hasAccess ? $url : '#!' }}" @if (! $hasAccess && ! empty($permissions)) aria-disabled="true" data-target-url="{{ $url }}" @endif>
                                        <div class="d-flex w-100 align-items-center justify-content-between">
                                            <span class="fw-semibold">{{ $item['label'] }}</span>
                                            <span class="badge requirement-badge {{ $badgeClass }}">{{ $badgeText }}</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        @if ($canSeeBackend)
                            <div>
                                <div class="text-uppercase text-muted fw-semibold small mb-2 px-2">後台模組</div>
                                <div class="d-flex flex-column gap-1">
                                    @php
                                        $visibleBackendItems = 0;
                                    @endphp
                                    @foreach ($backendMenu as $item)
                                        @php
                                            $patterns = (array) ($item['active'] ?? $item['route']);
                                            $isActive = false;
                                            foreach ($patterns as $pattern) {
                                                if (request()->routeIs($pattern)) {
                                                    $isActive = true;
                                                    break;
                                                }
                                            }

                                            $permissions = $item['permissions'] ?? ['backend.access'];
                                            $user = auth()->user();
                                            $hasAccess = $user ? collect($permissions)->every(fn ($permission) => $user->can($permission)) : false;
                                            if (! $hasAccess) {
                                                continue;
                                            }
                                            $visibleBackendItems++;

                                            $linkClasses = 'nav-link px-3 py-2 rounded d-flex flex-column align-items-start gap-1';
                                            if ($isActive) {
                                                $linkClasses .= ' active';
                                            }

                                            $url = route($item['route']);
                                        @endphp
                                        <a class="{{ $linkClasses }}" href="{{ $url }}">
                                            <div class="d-flex w-100 align-items-center justify-content-between">
                                                <span class="fw-semibold">{{ $item['label'] }}</span>
                                                <span class="badge requirement-badge badge-access">具權限</span>
                                            </div>
                                        </a>
                                    @endforeach

                                    @if ($visibleBackendItems === 0)
                                        <div class="px-3 py-2 text-muted small">尚未開通任何後台模組權限。</div>
                                    @endif
                                </div>
                            </div>
                        @endif
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
