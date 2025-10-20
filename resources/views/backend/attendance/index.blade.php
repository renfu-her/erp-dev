@extends('layouts.app')

@section('title', '出勤管理')

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h2 class="page-title fw-bold mb-1">出勤紀錄管理</h2>
            <p class="text-muted small mb-0">查詢員工打卡紀錄並建立補登。支援依日期與員工篩選，右側可檢視最近兩週摘要。</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#attendanceModal">新增補登紀錄</button>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('backend.attendance.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-sm-6 col-lg-4">
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
                <div class="col-sm-6 col-lg-4">
                    <label for="filter_from" class="form-label">起始日期</label>
                    <input type="date" name="date_from" id="filter_from" value="{{ $filters['date_from'] ?? '' }}" class="form-control">
                </div>
                <div class="col-sm-6 col-lg-4">
                    <label for="filter_to" class="form-label">結束日期</label>
                    <div class="input-group">
                        <input type="date" name="date_to" id="filter_to" value="{{ $filters['date_to'] ?? '' }}" class="form-control">
                        <button type="submit" class="btn btn-outline-secondary">篩選</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="table-responsive">
                    <table class="table table-striped mb-0 align-middle">
                        <thead class="table-light text-muted small text-uppercase">
                            <tr>
                                <th scope="col">時間</th>
                                <th scope="col">員工</th>
                                <th scope="col">類型</th>
                                <th scope="col">來源</th>
                                <th scope="col">備註</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                <tr>
                                    <td class="fw-semibold">{{ $log->recorded_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <div class="text-body">{{ $log->employee->last_name }}{{ $log->employee->first_name }}</div>
                                        <div class="text-muted small">{{ $log->employee->employee_no }}</div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $log->type === 'check_in' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $log->type === 'check_in' ? '上班' : '下班' }}
                                        </span>
                                    </td>
                                    <td class="text-muted small">{{ ucfirst($log->source) }}</td>
                                    <td class="text-muted small">{{ $log->remarks ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">查無出勤紀錄。</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-3">
                {{ $logs->links('pagination::bootstrap-5') }}
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header">
                    <h3 class="card-title h5 mb-0">近兩週摘要</h3>
                </div>
                <div class="card-body">
                    @if ($summaries->isEmpty())
                        <p class="text-muted small mb-0">選擇指定員工後顯示摘要資訊。</p>
                    @else
                        <ul class="list-unstyled mb-0 small">
                            @foreach ($summaries as $summary)
                                <li class="mb-3 border rounded p-3">
                                    <div class="d-flex justify-content-between text-muted">
                                        <span>{{ $summary->work_date->format('m/d (D)') }}</span>
                                        <span>工時：{{ $summary->worked_hours }} 小時</span>
                                    </div>
                                    <div class="text-muted mt-1">
                                        遲到 {{ $summary->late_minutes }} 分・早退 {{ $summary->early_leave_minutes }} 分・加班 {{ $summary->overtime_hours }} 小時
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attendanceModalLabel">新增補登紀錄</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('backend.attendance.store') }}" method="POST">
                    @csrf
                    <div class="modal-body row g-3">
                        <div class="col-12">
                            <label for="modal_employee" class="form-label">員工</label>
                            <select name="employee_id" id="modal_employee" class="form-select" required>
                                <option value="" disabled selected>請選擇</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->last_name }}{{ $employee->first_name }} ({{ $employee->employee_no }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_recorded_at" class="form-label">打卡時間</label>
                            <input type="datetime-local" name="recorded_at" id="modal_recorded_at" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_type" class="form-label">類型</label>
                            <select name="type" id="modal_type" class="form-select">
                                <option value="check_in">上班</option>
                                <option value="check_out">下班</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="modal_remarks" class="form-label">備註</label>
                            <textarea name="remarks" id="modal_remarks" rows="2" class="form-control" placeholder="例如：補登 - 忘記打卡"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-primary">儲存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
