@extends('layouts.app')

@section('title', '薪資模組總覽')

@section('content')
    <section class="row g-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">
                    <div>
                        <h2 class="card-title h3 mb-2">薪資與獎酬模組建置概況</h2>
                        <p class="card-text text-muted mb-0">下方列出已建立的薪資期間、薪資項目與近期計薪批次，可作為後續開發的起點。</p>
                    </div>
                    <div class="mt-3 mt-lg-0 btn-group">
                        <button class="btn btn-outline-secondary" type="button" disabled>新增薪資期間 (待開發)</button>
                        <button class="btn btn-outline-secondary" type="button" disabled>建立薪資批次 (待開發)</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card h-100 shadow-sm">
                <div class="card-header">
                    <h3 class="card-title h5 mb-0">最近薪資期間</h3>
                </div>
                <div class="card-body">
                    @if ($periods->isEmpty())
                        <p class="text-muted small mb-0">尚未建立薪資期間。完成 migrations 後，可透過 Seeder 或 Blade 表單新增。</p>
                    @else
                        <div class="row g-3">
                            @foreach ($periods as $period)
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
