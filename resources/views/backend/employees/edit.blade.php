@extends('layouts.app')

@section('title', '編輯員工')

@section('content')
    @include('backend.employees._form', [
        'employee' => $employee,
        'companies' => $companies,
        'departments' => $departments,
        'positions' => $positions,
        'statuses' => $statuses,
        'leaveTypes' => $leaveTypes,
        'leaveSummaries' => $leaveSummaries,
        'formAction' => route('backend.employees.update', $employee),
        'formMethod' => 'PUT',
        'submitLabel' => '儲存變更',
        'pageTitle' => '編輯員工',
        'pageDescription' => '更新員工基本資訊，調整後將影響人力資源相關流程。',
        'currentYear' => $currentYear,
    ])
@endsection
