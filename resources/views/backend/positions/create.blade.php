@extends('layouts.app')

@section('title', '新增職位')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title h3 mb-2">新增職位</h2>
                    <p class="card-text text-muted">設定部門內的職務名稱、階層與是否為主管職。</p>
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
                    <form action="{{ route('backend.positions.store') }}" method="POST">
                        @csrf
                        @include('backend.positions._form')

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('backend.positions.index') }}" class="btn btn-outline-secondary">取消</a>
                            <button type="submit" class="btn btn-primary">建立職位</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
