@extends('layouts.app')

@section('title', '薪資管理')

@section('content')
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <section class="row g-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">
                    <div>
                        <h2 class="card-title h3 mb-2">薪資與獎酬模組建置概況</h2>
                        <p class="card-text text-muted mb-0">下方列出已建立的薪資期間、薪資項目與近期計薪批次，可作為後續開發的起點。</p>
                    </div>
                    <div class="mt-3 mt-lg-0 btn-group">
                        <a class="btn btn-primary" href="#create-payroll-period">新增薪資期間</a>
                        <a class="btn btn-outline-primary" href="#create-payroll-run">建立薪資批次</a>
                    </div>
                </div>
            </div>
        </div>

        @php
            $periodErrors = $errors->getBag('createPayrollPeriod');
            $runErrors = $errors->getBag('createPayrollRun');
        @endphp

        <div class="col-lg-6" id="create-payroll-period">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <h3 class="card-title h5 mb-0">新增薪資期間</h3>
                </div>
                <div class="card-body">
                    @if ($periodErrors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 small">
                                @foreach ($periodErrors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('backend.payroll.periods.store') }}" class="row g-3">
                        @csrf
                        <div class="col-12">
                            <label for="period_name" class="form-label">期間名稱</label>
                            <input type="text" id="period_name" name="name" value="{{ old('name') }}" class="form-control"
                                placeholder="例如：2024 年 10 月薪資">
                        </div>
                        <div class="col-md-6">
                            <label for="period_start" class="form-label">起始日期</label>
                            <input type="date" id="period_start" name="period_start" value="{{ old('period_start') }}"
                                class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="period_end" class="form-label">結束日期</label>
                            <input type="date" id="period_end" name="period_end" value="{{ old('period_end') }}" class="form-control">
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary">儲存薪資期間</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6" id="create-payroll-run">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <h3 class="card-title h5 mb-0">建立薪資批次</h3>
                </div>
                <div class="card-body">
                    @if ($runErrors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 small">
                                @foreach ($runErrors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('backend.payroll.runs.store') }}" class="row g-3">
                        @csrf
                        <div class="col-12">
                            <label for="payroll_period_id" class="form-label">對應薪資期間</label>
                            <select name="payroll_period_id" id="payroll_period_id" class="form-select" @disabled($periodOptions->isEmpty()) required>
                                <option value="">請選擇薪資期間</option>
                                @foreach ($periodOptions as $period)
                                    <option value="{{ $period->id }}" @selected(old('payroll_period_id') == $period->id)>
                                        {{ $period->name }}（{{ $period->period_start->format('Y-m-d') }} ~ {{ $period->period_end->format('Y-m-d') }}）
                                    </option>
                                @endforeach
                            </select>
                            @if ($periodOptions->isEmpty())
                                <div class="form-text text-danger">請先建立薪資期間後再建立薪資批次。</div>
                            @endif
                        </div>
                        <div class="col-12">
                            <label for="company_id" class="form-label">適用公司</label>
                            <select name="company_id" id="company_id" class="form-select">
                                <option value="">全部公司</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}" @selected((string) old('company_id') === (string) $company->id)>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">保持空白代表以所有公司進行計薪。</div>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary" @disabled($periodOptions->isEmpty())>建立薪資批次</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">
                    <div>
                        <h3 class="card-title h5 mb-1">薪資與投保現況</h3>
                        <p class="text-muted small mb-0">顯示目前在職員工的契約薪資、職位投保級距與勞健保負擔參考值。</p>
                    </div>
                    @unless ($insuranceScheduleAvailable)
                        <span class="badge bg-warning text-dark mt-3 mt-lg-0">未載入投保級距表</span>
                    @endunless
                </div>
                <div class="card-body">
                    @php
                        $formatMoney = fn (?float $value) => is_null($value) ? '—' : number_format($value, 0);
                        $formatInteger = fn (?int $value) => is_null($value) ? '—' : number_format($value);
                    @endphp

                    @if ($payrollEmployees->isEmpty())
                        <p class="text-muted small mb-0">尚未有在職員工或尚未建立契約薪資。</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-muted small text-uppercase">
                                    <tr>
                                        <th scope="col">員工</th>
                                        <th scope="col">公司／部門</th>
                                        <th scope="col">職位</th>
                                        <th scope="col" class="text-end">契約薪資</th>
                                        <th scope="col" class="text-end">投保級距</th>
                                        <th scope="col" class="text-end">勞保保費</th>
                                        <th scope="col" class="text-end">健保保費</th>
                                        <th scope="col" class="text-end">勞退提繳（6%）</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payrollEmployees as $item)
                                        @php
                                            $employee = $item['employee'];
                                            $company = $employee->company?->name;
                                            $department = $employee->department?->name;
                                            $position = $employee->position?->title;
                                            $summary = $item['insurance_summary'];
                                            $baseSalaryValue = is_null($item['base_salary']) ? null : (float) $item['base_salary'];
                                            $gradeLabel = $item['grade_label'];
                                            $gradeValue = $item['grade_value'];
                                            $laborTotal = data_get($summary, 'labor_local.total');
                                            $healthTotal = data_get($summary, 'health.total');
                                            $pensionEmployer = data_get($summary, 'pension.employer');
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $employee->employee_no ?? '—' }}</div>
                                                <div class="text-muted small">{{ $employee->last_name }}{{ $employee->first_name }}</div>
                                            </td>
                                            <td class="text-muted small">
                                                <div>{{ $company ?? '—' }}</div>
                                                <div>{{ $department ?? '—' }}</div>
                                            </td>
                                            <td class="text-muted small">{{ $position ?? '—' }}</td>
                                            <td class="text-end">
                                                @if (! is_null($baseSalaryValue))
                                                    {{ $formatMoney($baseSalaryValue) }} 元
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if ($gradeLabel)
                                                    {{ $gradeLabel }}
                                                @elseif ($gradeValue)
                                                    級距 {{ $formatInteger($gradeValue) }}
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if (! is_null($baseSalaryValue) && $summary)
                                                    {{ $formatInteger($laborTotal) }} 元
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if (! is_null($baseSalaryValue) && $summary)
                                                    {{ $formatInteger($healthTotal) }} 元
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if (! is_null($baseSalaryValue) && $summary)
                                                    {{ $formatInteger($pensionEmployer) }} 元
                                                @else
                                                    —
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card h-100 shadow-sm">
                <div class="card-header">
                    <h3 class="card-title h5 mb-0">最近薪資期間</h3>
                </div>
                <div class="card-body">
                    @if ($recentPeriods->isEmpty())
                        <p class="text-muted small mb-0">尚未建立薪資期間。完成 migrations 後，可透過 Seeder 或 Blade 表單新增。</p>
                    @else
                        <div class="row g-3">
                            @foreach ($recentPeriods as $period)
                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <div class="text-muted small text-uppercase">{{ $period->name }}</div>
                                        <div class="fw-semibold">{{ $period->period_start->format('Y-m-d') }} ~ {{ $period->period_end->format('Y-m-d') }}</div>
                                        <span class="badge bg-secondary mt-2">{{ ucfirst($period->status) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header">
                    <h3 class="card-title h5 mb-0">薪資項目</h3>
                </div>
                <div class="card-body">
                    @if ($components->isEmpty())
                        <p class="text-muted small mb-0">尚未建立薪資項目。可於後續開發中提供新增／排序介面。</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach ($components as $component)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $component->name }} ({{ strtoupper($component->code) }})</span>
                                    <span class="badge {{ $component->type === 'earning' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $component->type === 'earning' ? '加項' : '扣項' }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title h5 mb-0">近期薪資批次</h3>
                </div>
                <div class="card-body">
                    @if ($recentRuns->isEmpty())
                        <p class="text-muted small mb-0">尚未建立薪資批次，可於完成核心流程後補上計薪邏輯。</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped mb-0 align-middle">
                                <thead class="table-light text-muted small text-uppercase">
                                    <tr>
                                        <th scope="col">批次名稱</th>
                                        <th scope="col">公司</th>
                                        <th scope="col">狀態</th>
                                        <th scope="col">建立時間</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentRuns as $run)
                                        <tr>
                                            <td>{{ $run->period->name ?? '—' }}</td>
                                            <td>{{ $run->company->name ?? '所有公司' }}</td>
                                            <td class="text-muted small">{{ ucfirst($run->status) }}</td>
                                            <td class="text-muted small">{{ $run->created_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border border-dashed shadow-none">
                <div class="card-body">
                    <h3 class="card-title h5">下一步建議</h3>
                    <ol class="text-muted small ps-3 mb-0">
                        <li class="mb-1">建立 Blade 介面或 API 流程以新增薪資期間與薪資項目。</li>
                        <li class="mb-1">串聯出勤、請假資料計算加班費與扣薪邏輯。</li>
                        <li class="mb-1">完成薪資條產生器與轉帳檔 / PDF 匯出功能。</li>
                        <li>擴充績效、獎懲模組，連動薪資獎金發放。</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
@endsection
