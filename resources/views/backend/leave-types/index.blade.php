@extends('layouts.app')

@section('title', '假別管理')

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h2 class="page-title fw-bold mb-1">假別管理</h2>
            <p class="text-muted small mb-0">建立與維護假別設定，決定請假流程與年度額度。</p>
        </div>
        <a href="{{ route('backend.leave-types.create') }}" class="btn btn-primary">新增假別</a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th scope="col">名稱</th>
                        <th scope="col">代碼</th>
                        <th scope="col">預設額度</th>
                        <th scope="col">需要審核</th>
                        <th scope="col">影響出勤</th>
                        <th scope="col" class="text-end">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leaveTypes as $leaveType)
                        <tr>
                            <td class="fw-semibold text-body">{{ $leaveType->name }}</td>
                            <td class="text-muted small">{{ $leaveType->code }}</td>
                            <td class="text-muted">{{ $leaveType->default_quota ?? '—' }}</td>
                            <td>
                                <span class="badge {{ $leaveType->requires_approval ? 'bg-success' : 'bg-secondary' }}">{{ $leaveType->requires_approval ? 'Yes' : 'No' }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $leaveType->affects_attendance ? 'bg-info text-dark' : 'bg-secondary' }}">{{ $leaveType->affects_attendance ? 'Yes' : 'No' }}</span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group-separated">
                                    <a href="{{ route('backend.leave-types.edit', $leaveType) }}" class="btn btn-outline-primary btn-sm">編輯</a>
                                    <form action="{{ route('backend.leave-types.destroy', $leaveType) }}" method="POST" onsubmit="return confirm('確認刪除假別 {{ $leaveType->name }}？');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">刪除</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">目前尚無假別設定。</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $leaveTypes->links('pagination::bootstrap-5') }}
    </div>
@endsection
