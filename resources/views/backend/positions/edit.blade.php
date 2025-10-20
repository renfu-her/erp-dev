@extends('layouts.app')

@section('title', '編輯職位')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title h3 mb-2">編輯職位</h2>
                    <p class="card-text text-muted">調整職務資訊與階層設定。</p>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <p class="mb-2 fw-semibold">請修正以下錯誤：</p>
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('backend.positions.update', $position) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @include('backend.positions._form', ['position' => $position])

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('backend.positions.index') }}" class="btn btn-outline-secondary">取消</a>
                            <button type="submit" class="btn btn-primary">儲存變更</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
