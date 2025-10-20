@extends('layouts.app')

@section('title', '新增員工')

@section('content')
    @include('backend.employees._form', [
        'employee' => $employee,
        'companies' => $companies,
        'departments' => $departments,
        'positions' => $positions,
        'statuses' => $statuses,
        'leaveTypes' => $leaveTypes,
        'leaveSummaries' => $leaveSummaries,
        'formAction' => route('backend.employees.store'),
        'formMethod' => 'POST',
        'submitLabel' => '建立員工',
        'pageTitle' => '新增員工',
        'pageDescription' => '輸入員工基本資料，建立後即可透過其他模組進行進一步設定。',
    ])
@endsection
