@extends('layouts.app')

@section('title', '請假審核')

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h2 class="page-title fw-bold mb-1">請假審核</h2>
            <p class="text-muted small mb-0">檢視員工假單並進行核准或退回，可依假別、員工與狀態篩選。</p>
        </div>
        <a href="{{ route('backend.leave-types.index') }}" class="btn btn-outline-secondary">管理假別設定</a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('backend.leave-requests.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="filter_status" class="form-label">狀態</label>
                    <select name="status" id="filter_status" class="form-select">
                        <option value="">全部</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" {{ ($filters['status'] ?? '') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filter_leave_type" class="form-label">假別</label>
                    <select name="leave_type_id" id="filter_leave_type" class="form-select">
                        <option value="">全部</option>
                        @foreach ($leaveTypes as $leaveType)
                            <option value="{{ $leaveType->id }}" {{ ($filters['leave_type_id'] ?? '') == $leaveType->id ? 'selected' : '' }}>{{ $leaveType->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filter_employee" class="form-label">員工</label>
                    <select name="employee_id" id="filter_employee" class="form-select">
                        <option value="">全部</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" {{ ($filters['employee_id'] ?? '') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->last_name }}{{ $employee->first_name }} ({{ $employee->employee_no }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th scope="col">員工</th>
                        <th scope="col">假別</th>
                        <th scope="col">期間</th>
                        <th scope="col">天數</th>
                        <th scope="col">狀態</th>
                        <th scope="col">原因</th>
                        <th scope="col" class="text-end">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leaveRequests as $leaveRequest)
                        <tr>
                            <td>
                                <div class="fw-semibold text-body">{{ $leaveRequest->employee->last_name }}{{ $leaveRequest->employee->first_name }}</div>
                                <div class="text-muted small">{{ $leaveRequest->employee->employee_no }}</div>
                            </td>
                            <td class="text-muted">{{ $leaveRequest->leaveType->name }}</td>
                            <td class="text-muted small">{{ $leaveRequest->start_date->format('Y-m-d') }} ~ {{ $leaveRequest->end_date->format('Y-m-d') }}</td>
                            <td>{{ $leaveRequest->days }}</td>
                            <td>
                                @php
                                    $badge = [
                                        'pending' => 'bg-warning text-dark',
                                        'approved' => 'bg-success',
                                        'rejected' => 'bg-danger',
                                        'cancelled' => 'bg-secondary',
                                    ][$leaveRequest->status] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $badge }}">{{ ucfirst($leaveRequest->status) }}</span>
                                @if ($leaveRequest->approved_at)
                                    <div class="text-muted small mt-1">{{ $leaveRequest->approved_at->format('Y-m-d H:i') }}</div>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $leaveRequest->reason ?? '—' }}</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#leaveModal" data-leave-id="{{ $leaveRequest->id }}" data-employee-code="{{ $leaveRequest->employee->employee_no }}" data-leave-name="{{ $leaveRequest->leaveType->name }}">
                                    審核
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">目前沒有假單。</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $leaveRequests->links('pagination::bootstrap-5') }}
    </div>

    <div class="modal fade" id="leaveModal" tabindex="-1" aria-labelledby="leaveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="leaveModalLabel">審核假單</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="leaveProcessForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="leaveStatus" class="form-label">狀態</label>
                            <select name="status" id="leaveStatus" class="form-select" required>
                                <option value="approved">核准</option>
                                <option value="rejected">退回</option>
                                <option value="cancelled">取消</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="leaveNote" class="form-label">備註</label>
                            <textarea name="note" id="leaveNote" rows="3" class="form-control" placeholder="可輸入審核意見"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-primary">送出</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const leaveModal = document.getElementById('leaveModal');
        const leaveForm = document.getElementById('leaveProcessForm');
        const leaveModalLabel = document.getElementById('leaveModalLabel');

        if (leaveModal) {
            leaveModal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                const leaveId = button.getAttribute('data-leave-id');
                const employeeCode = button.getAttribute('data-employee-code');
                const leaveName = button.getAttribute('data-leave-name');
                leaveModalLabel.textContent = `審核 ${employeeCode} 的 ${leaveName}`;
                leaveForm.action = `/backend/leave-requests/${leaveId}`;
                leaveForm.reset();
            });
        }
    </script>
@endpush
