@extends('layouts.app')

@section('title', '公司管理')

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h2 class="page-title fw-bold mb-1">公司管理</h2>
            <p class="text-muted small mb-0">新增並維護 ERP 內的公司資料，後續部門與員工皆依附於公司。</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('backend.holidays.index', ['year' => date('Y')]) }}" class="btn btn-outline-info">國定假日管理</a>
            <a href="{{ route('backend.companies.create') }}" class="btn btn-primary">建立公司</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th scope="col">公司名稱</th>
                            <th scope="col">代碼</th>
                            <th scope="col">統編 / 稅籍</th>
                            <th scope="col">狀態</th>
                            <th scope="col" class="text-end">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($companies as $company)
                            <tr>
                                <td>
                                    <div class="fw-semibold text-body">{{ $company->name }}</div>
                                    <div class="text-muted small">建立於 {{ $company->created_at?->format('Y-m-d') }}</div>
                                </td>
                                <td class="text-muted">{{ $company->code }}</td>
                                <td class="text-muted">{{ $company->tax_id ?? '—' }}</td>
                                <td>
                                    <span class="badge {{ $company->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($company->status) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group-separated">
                                        <a href="{{ route('backend.companies.edit', $company) }}" class="btn btn-outline-primary btn-sm">編輯</a>
                                        <form action="{{ route('backend.companies.destroy', $company) }}" method="POST" onsubmit="return confirm('確認刪除此公司？此動作無法復原。')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">刪除</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">目前尚未建立公司資料。</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $companies->links() }}
    </div>
@endsection
