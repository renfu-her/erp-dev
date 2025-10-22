@extends('layouts.app')

@section('title', '部門管理')

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h2 class="page-title fw-bold mb-1">部門管理</h2>
            <p class="text-muted small mb-0">檢視與維護各公司部門結構，並設定階層與部門主管。</p>
        </div>
        <a href="{{ route('backend.departments.create') }}" class="btn btn-primary">新增部門</a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th scope="col">部門名稱</th>
                        <th scope="col">公司</th>
                        <th scope="col">上層部門</th>
                        <th scope="col">部門主管</th>
                        <th scope="col" class="text-end">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($departments as $department)
                        <tr>
                            <td class="fw-semibold text-body">{{ $department->name }}</td>
                            <td class="text-muted small">{{ $department->company?->name ?? '—' }}</td>
                            <td class="text-muted small">{{ $department->parent?->name ?? '—' }}</td>
                            <td class="text-muted small">{{ $department->lead?->last_name }}{{ $department->lead?->first_name ?? '—' }}</td>
                            <td class="text-end">
                                <div class="btn-group-separated">
                                    <a href="{{ route('backend.departments.edit', $department) }}" class="btn btn-outline-primary btn-sm">編輯</a>
                                    <form action="{{ route('backend.departments.destroy', $department) }}" method="POST" onsubmit="return confirm('確認刪除部門 {{ $department->name }}？');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">刪除</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">目前尚未建立部門資料。</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $departments->links('pagination::bootstrap-5') }}
    </div>
@endsection
