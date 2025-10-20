@extends('layouts.app')

@section('title', '新增假別')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title h3 mb-2">新增假別</h2>
                    <p class="card-text text-muted">設定假別基本資訊及審核規則，可於員工請假時套用。</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('backend.leave-types.store') }}" method="POST" class="row g-3">
                        @csrf

                        <div class="col-md-6">
                            <label for="name" class="form-label">假別名稱</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="code" class="form-label">假別代碼</label>
                            <input type="text" name="code" id="code" value="{{ old('code') }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="default_quota" class="form-label">預設額度 (天)</label>
                            <input type="number" step="0.5" name="default_quota" id="default_quota" value="{{ old('default_quota') }}" class="form-control">
                        </div>

                        <div class="col-md-6 d-flex align-items-end gap-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="requires_approval" id="requires_approval" value="1" {{ old('requires_approval', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="requires_approval">需要審核</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="affects_attendance" id="affects_attendance" value="1" {{ old('affects_attendance', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="affects_attendance">影響出勤</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="rules" class="form-label">其他規則 (JSON)</label>
                            <textarea name="rules" id="rules" rows="3" class="form-control" placeholder='{"max_per_month": 5}'>{{ old('rules') }}</textarea>
                            <div class="form-text">不需設定可留白。</div>
                        </div>

                        <div class="col-12 d-flex justify-content-end gap-2">
                            <a href="{{ route('backend.leave-types.index') }}" class="btn btn-outline-secondary">取消</a>
                            <button type="submit" class="btn btn-primary">建立假別</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
