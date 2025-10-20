@extends('layouts.app')

@section('title', '職位管理')

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h2 class="page-title fw-bold mb-1">職位管理</h2>
            <p class="text-muted small mb-0">維護各部門的職務清單與主管階層設定。</p>
        </div>
        <a href="{{ route('backend.positions.create') }}" class="btn btn-primary">新增職位</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('backend.positions.index') }}" class="row gy-2 gx-3 align-items-end">
                <div class="col-md-4">
                    <label for="department_id" class="form-label">部門篩選</label>
                    <select name="department_id" id="department_id" class="form-select">
                        <option value="">全部部門</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" @selected(($filters['department_id'] ?? null) == $department->id)>
                                {{ $department->name }} @if ($department->company)（{{ $department->company->name }}）@endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="search" class="form-label">關鍵字</label>
                    <input type="text" name="search" id="search" value="{{ $filters['search'] ?? '' }}" class="form-control"
                        placeholder="輸入職位名稱或薪資等級">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-dark flex-grow-1">套用篩選</button>
                    @if ($filters)
                        <a href="{{ route('backend.positions.index') }}" class="btn btn-outline-secondary">重設</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th scope="col">職務名稱</th>
                        <th scope="col">部門</th>
                        <th scope="col">公司</th>
                        <th scope="col">職等</th>
                        <th scope="col">薪資等級</th>
                        <th scope="col">主管職</th>
                        <th scope="col" class="text-end">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($positions as $position)
                        <tr>
                            <td class="fw-semibold text-body">{{ $position->title }}</td>
                            <td class="text-muted small">{{ $position->department?->name ?? '—' }}</td>
                            <td class="text-muted small">{{ $position->department?->company?->name ?? '—' }}</td>
                            <td class="text-muted small">
                                @if ($position->level)
                                    {{ $position->level->name }}
                                    <span class="text-uppercase">({{ $position->level->code }})</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-muted small">{{ $position->grade ?? '—' }}</td>
                            <td>
                                @if ($position->is_managerial)
                                    <span class="badge bg-success">是</span>
                                @else
                                    <span class="badge bg-secondary">否</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('backend.positions.edit', $position) }}" class="btn btn-outline-primary">編輯</a>
                                    <form action="{{ route('backend.positions.destroy', $position) }}" method="POST"
                                        onsubmit="return confirm('確定要刪除職位 {{ $position->title }} 嗎？');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">刪除</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">目前尚未建立任何職位。</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $positions->links('pagination::bootstrap-5') }}
    </div>
@endsection
