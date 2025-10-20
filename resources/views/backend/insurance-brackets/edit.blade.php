@extends('layouts.app')

@section('title', '編輯勞健保級距')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="card-title h3 mb-2">編輯勞健保級距</h2>
                    <p class="card-text text-muted mb-0">更新投保薪資與對應保費設定。</p>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <p class="fw-semibold mb-2">請修正以下錯誤：</p>
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('backend.insurance-brackets.update', $bracket) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('backend.insurance-brackets._form')

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('backend.insurance-brackets.index') }}" class="btn btn-outline-secondary">取消</a>
                            <button type="submit" class="btn btn-primary">更新級距</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

