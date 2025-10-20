@extends('layouts.app')

@section('title', '建立公司')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title h3 mb-2">建立公司</h2>
                    <p class="card-text text-muted">填寫公司基本資料，建立後即可配置部門與員工。</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('backend.companies.store') }}" method="POST" class="row g-3">
                        @csrf

                        <div class="col-md-6">
                            <label for="name" class="form-label">公司名稱</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="code" class="form-label">公司代碼</label>
                            <input type="text" name="code" id="code" value="{{ old('code') }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="tax_id" class="form-label">統一編號 / 稅籍</label>
                            <input type="text" name="tax_id" id="tax_id" value="{{ old('tax_id') }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">狀態</label>
                            <select name="status" id="status" class="form-select">
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>inactive</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="metadata" class="form-label">備註 / 其他資訊 (JSON)</label>
                            <textarea name="metadata" id="metadata" rows="3" class="form-control" placeholder='{"timezone": "Asia/Taipei"}'>{{ old('metadata') }}</textarea>
                            <div class="form-text">如無需求可留白，系統將忽略此欄位。</div>
                        </div>

                        <div class="col-12 d-flex justify-content-end gap-2">
                            <a href="{{ route('backend.companies.index') }}" class="btn btn-outline-secondary">取消</a>
                            <button type="submit" class="btn btn-primary">建立公司</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
