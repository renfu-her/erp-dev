@extends('layouts.app')

@section('title', '勞健保級距一覽表')

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h2 class="page-title fw-bold mb-1">勞健保級距一覽表</h2>
            <p class="text-muted small mb-0">顯示投保薪資級距與對應的保費資料。</p>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if ($brackets->isEmpty())
        <div class="alert alert-info">
            目前尚未建立任何級距。可先透過 Seeder 或「新增級距」建立勞保與健保資料。
        </div>
    @else
        @php
            $formatInteger = fn (?int $value) => is_null($value) ? '—' : number_format($value);
        @endphp

        <div class="row g-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h3 class="card-title h5 mb-0">勞保級距一覽</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-muted small text-uppercase">
                                    <tr>
                                        <th scope="col">級距名稱</th>
                                        <th scope="col" class="text-end">投保薪資</th>
                                        <th scope="col" class="text-end">員工負擔</th>
                                        <th scope="col" class="text-end">雇主負擔</th>
                                        <th scope="col" class="text-end">外籍員工</th>
                                        <th scope="col" class="text-end">操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($brackets as $bracket)
                                        <tr>
                                            <td class="fw-semibold">{{ $bracket->label }}</td>
                                            <td class="text-end">{{ number_format($bracket->salary) }} 元</td>
                                            <td class="text-end">
                                                {{ $formatInteger($bracket->labor_employee_local) }}
                                            </td>
                                            <td class="text-end">
                                                {{ $formatInteger($bracket->labor_employer_local) }}
                                            </td>
                                            <td class="text-end">
                                                {{ $formatInteger($bracket->labor_employee_foreign) }}／{{ $formatInteger($bracket->labor_employer_foreign) }}
                                            </td>
                                            <td class="text-end text-muted">—</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h3 class="card-title h5 mb-0">健保級距一覽</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-muted small text-uppercase">
                                    <tr>
                                        <th scope="col">級距名稱</th>
                                        <th scope="col" class="text-end">投保薪資</th>
                                        <th scope="col" class="text-end">員工負擔</th>
                                        <th scope="col" class="text-end">雇主負擔</th>
                                        <th scope="col" class="text-end">勞退 6%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($brackets as $bracket)
                                        <tr>
                                            <td class="fw-semibold">{{ $bracket->label }}</td>
                                            <td class="text-end">{{ number_format($bracket->salary) }} 元</td>
                                            <td class="text-end">
                                                {{ $formatInteger($bracket->health_employee) }}
                                            </td>
                                            <td class="text-end">
                                                {{ $formatInteger($bracket->health_employer) }}
                                            </td>
                                            <td class="text-end">
                                                {{ $formatInteger($bracket->pension_employer) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            {{ $brackets->links('pagination::bootstrap-5') }}
        </div>
    @endif
@endsection
