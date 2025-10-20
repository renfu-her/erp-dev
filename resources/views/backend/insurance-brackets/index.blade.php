@extends('layouts.app')

@section('title', '勞健保級距管理')

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h2 class="page-title fw-bold mb-1">勞健保級距管理</h2>
            <p class="text-muted small mb-0">維護每個投保薪資級距的勞保、健保與勞退基準金額。</p>
        </div>
        <a href="{{ route('backend.insurance-brackets.create') }}" class="btn btn-primary">新增級距</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if ($brackets->isEmpty())
        <div class="alert alert-info">
            目前尚未建立任何級距。可透過「新增級距」補齊資料，或將 <code>storage/salary_table.json</code> 匯入資料庫後使用。
        </div>
    @else
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th scope="col">級距名稱</th>
                            <th scope="col" class="text-end">投保薪資</th>
                            <th scope="col" class="text-end">勞保（員／雇）</th>
                            <th scope="col" class="text-end">健保（員／雇）</th>
                            <th scope="col" class="text-end">勞退 6%</th>
                            <th scope="col" class="text-end">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $formatInteger = fn (?int $value) => is_null($value) ? '—' : number_format($value);
                        @endphp
                        @foreach ($brackets as $bracket)
                            <tr>
                                <td class="fw-semibold">{{ $bracket->label }}</td>
                                <td class="text-end">{{ number_format($bracket->grade) }} 元</td>
                                <td class="text-end">
                                    {{ $formatInteger($bracket->labor_employee_local) }}／{{ $formatInteger($bracket->labor_employer_local) }}
                                </td>
                                <td class="text-end">
                                    {{ $formatInteger($bracket->health_employee) }}／{{ $formatInteger($bracket->health_employer) }}
                                </td>
                                <td class="text-end">
                                    {{ $formatInteger($bracket->pension_employer) }}
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('backend.insurance-brackets.edit', $bracket) }}"
                                            class="btn btn-outline-primary">編輯</a>
                                        <form action="{{ route('backend.insurance-brackets.destroy', $bracket) }}" method="POST"
                                            onsubmit="return confirm('確定要刪除級距 {{ $bracket->label }} 嗎？');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">刪除</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $brackets->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
@endsection

