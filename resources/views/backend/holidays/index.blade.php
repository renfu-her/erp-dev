@extends('layouts.app')

@section('title', '國定假日設定')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="card-title h3 mb-2">{{ $year }}年國定假日設定</h2>
                            <p class="card-text text-muted">以下為{{ $year }}年台灣國定假日設定，這些日期為非工作日。每週六、星期日也為例假日。</p>
                        </div>
                        <div class="d-flex gap-2">
                            <form method="GET" class="d-flex gap-2">
                                <select name="year" class="form-select" onchange="this.form.submit()">
                                    @foreach($availableYears as $availableYear)
                                        <option value="{{ $availableYear }}" {{ $year == $availableYear ? 'selected' : '' }}>
                                            {{ $availableYear }}年
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                            <a href="{{ route('backend.holidays.create', ['year' => $year]) }}" class="btn btn-primary">新增假日</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>節日名稱</th>
                                    <th>日期</th>
                                    <th>星期</th>
                                    <th>類型</th>
                                    <th>備註</th>
                                    <th class="text-end">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($holidays as $holiday)
                                    <tr>
                                        <td>
                                            <strong>{{ $holiday->name }}</strong>
                                        </td>
                                        <td>
                                            {{ $holiday->date->format('Y-m-d') }}
                                        </td>
                                        <td>
                                            @php
                                                $dayNames = ['日', '一', '二', '三', '四', '五', '六'];
                                                $dayOfWeek = $holiday->date->dayOfWeek;
                                            @endphp
                                            星期{{ $dayNames[$dayOfWeek] }}
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $holiday->type === 'national' ? '國定假日' : '自訂假日' }}</span>
                                        </td>
                                        <td>
                                            @if($holiday->name === '元旦')
                                                新年假期
                                            @elseif(str_contains($holiday->name, '春節'))
                                                農曆新年假期
                                            @elseif(str_contains($holiday->name, '228'))
                                                和平紀念日假期
                                            @elseif(str_contains($holiday->name, '清明'))
                                                清明節假期
                                            @elseif($holiday->name === '勞動節')
                                                勞工節
                                            @elseif(str_contains($holiday->name, '端午'))
                                                端午節假期
                                            @elseif(str_contains($holiday->name, '中秋'))
                                                中秋節假期
                                            @elseif(str_contains($holiday->name, '國慶'))
                                                國慶日假期
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group-separated">
                                                <a href="{{ route('backend.holidays.edit', $holiday) }}" class="btn btn-outline-primary btn-sm">編輯</a>
                                                <form action="{{ route('backend.holidays.destroy', $holiday) }}" method="POST" onsubmit="return confirm('確認刪除此假日？')" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">刪除</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">假日統計</h6>
                                    <p class="mb-1"><strong>總假日天數：</strong> {{ $holidays->count() }} 天</p>
                                    <p class="mb-1"><strong>國定假日：</strong> {{ $holidays->where('type', 'national')->count() }} 天</p>
                                    <p class="mb-0"><strong>涵蓋月份：</strong> 
                                        @php
                                            $months = $holidays->pluck('date')->map(function($date) {
                                                return $date->format('m');
                                            })->unique()->sort()->values();
                                        @endphp
                                        {{ $months->implode('月, ') }}月
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">假日規則設定</h6>
                                    <ul class="mb-0">
                                        <li><strong>每週例假日：</strong>星期六、星期日</li>
                                        <li><strong>國定假日：</strong>以下列表中的所有日期</li>
                                        <li><strong>適用範圍：</strong>所有公司統一適用</li>
                                        <li><strong>系統功能：</strong>請假、考勤、薪資計算會自動排除這些日期</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">返回</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
