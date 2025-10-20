@extends('layouts.app')

@section('title', '員工管理')

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h2 class="page-title fw-bold mb-1">員工管理</h2>
            <p class="text-muted small mb-0">瀏覽、搜尋並維護 ERP 的員工資料，並可直接進行阻擋 / 解除。</p>
        </div>
        <a href="{{ route('backend.employees.create') }}" class="btn btn-primary">新增員工</a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('backend.employees.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-sm-6 col-lg-3">
                    <label for="filter_company" class="form-label small text-uppercase text-muted">公司</label>
                    <select name="company_id" id="filter_company" class="form-select">
                        <option value="">全部</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}" {{ ($filters['company_id'] ?? '') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <label for="filter_department" class="form-label small text-uppercase text-muted">部門</label>
                    <select name="department_id" id="filter_department" class="form-select">
                        <option value="">全部</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" {{ ($filters['department_id'] ?? '') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <label for="filter_status" class="form-label small text-uppercase text-muted">狀態</label>
                    <select name="status" id="filter_status" class="form-select">
                        <option value="">全部</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" {{ ($filters['status'] ?? '') === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <label for="filter_search" class="form-label small text-uppercase text-muted">搜尋</label>
                    <div class="input-group">
                        <input type="search" name="search" id="filter_search" value="{{ $filters['search'] ?? '' }}" class="form-control" placeholder="輸入姓名或員工編號">
                        <button class="btn btn-outline-secondary" type="submit">篩選</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th scope="col">員工資訊</th>
                        <th scope="col">公司 / 部門</th>
                        <th scope="col">職位</th>
                        <th scope="col">等級 / 身份</th>
                        <th scope="col">狀態</th>
                        <th scope="col" class="text-end">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        <tr>
                            <td>
                                <div class="fw-semibold text-body">{{ $employee->last_name }}{{ $employee->first_name }}</div>
                                <div class="text-muted small">{{ $employee->employee_no }}</div>
                                <div class="text-muted small">到職日：{{ $employee->hired_at?->format('Y-m-d') ?? '—' }}</div>
                            </td>
                            <td>
                                <div class="text-body">{{ $employee->company?->name ?? '—' }}</div>
                                <div class="text-muted small">{{ $employee->department?->name ?? '—' }}</div>
                            </td>
                            <td class="text-muted">{{ $employee->position?->title ?? '—' }}</td>
                            <td>
                                @php
                                    $badge = [
                                        'active' => 'bg-success',
                                        'inactive' => 'bg-secondary',
                                        'onboarding' => 'bg-info',
                                        'blocked' => 'bg-danger',
                                        'terminated' => 'bg-warning text-dark',
                                    ][$employee->status] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $badge }}">{{ ucfirst($employee->status) }}</span>
                                @if ($employee->blocked_reason)
                                    <div class="text-danger small mt-1">原因：{{ $employee->blocked_reason }}</div>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('backend.employees.edit', $employee) }}" class="btn btn-outline-primary">編輯</a>
                                    <form action="{{ route('backend.employees.destroy', $employee) }}" method="POST" onsubmit="return confirm('確認刪除員工 {{ $employee->employee_no }} ？');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">刪除</button>
                                    </form>
                                    @if ($employee->status === 'blocked')
                                        <form action="{{ route('backend.employees.unblock', $employee) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success">解除阻擋</button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#blockModal" data-employee-id="{{ $employee->id }}" data-employee-code="{{ $employee->employee_no }}">
                                            阻擋
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">查無員工資料，請調整篩選或新增員工。</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $employees->links('pagination::bootstrap-5') }}
    </div>

    <div class="modal fade" id="blockModal" tabindex="-1" aria-labelledby="blockModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="blockModalLabel">阻擋員工</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="blockForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="small text-muted">將阻擋員工 <span id="blockEmployeeCode" class="fw-semibold text-body"></span>，請填寫原因：</p>
                        <div class="mb-3">
                            <label for="blockReason" class="form-label">原因</label>
                            <textarea name="reason" id="blockReason" rows="3" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-danger">確認阻擋</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const blockModalEl = document.getElementById('blockModal');
        const blockForm = document.getElementById('blockForm');
        const blockEmployeeCode = document.getElementById('blockEmployeeCode');

        if (blockModalEl) {
            blockModalEl.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                const employeeId = button.getAttribute('data-employee-id');
                const employeeCode = button.getAttribute('data-employee-code');
                blockEmployeeCode.textContent = employeeCode;
                blockForm.action = `/backend/employees/${employeeId}/block`;
                blockForm.reset();
            });
        }
    </script>
@endpush
