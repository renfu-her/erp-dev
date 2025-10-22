@extends('layouts.app')

@section('title', '編輯國定假日')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title h3 mb-2">編輯國定假日</h2>
                    <p class="card-text text-muted">修改假日的詳細資訊，這些變更將影響所有公司的假日設定。</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('backend.holidays.update', $holiday) }}" method="POST" class="row g-3">
                        @csrf
                        @method('PUT')

                        <div class="col-md-6">
                            <label for="name" class="form-label">節日名稱</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $holiday->name) }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="date" class="form-label">日期</label>
                            <input type="date" name="date" id="date" value="{{ old('date', $holiday->date->format('Y-m-d')) }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="type" class="form-label">類型</label>
                            <select name="type" id="type" class="form-select" required>
                                <option value="national" {{ old('type', $holiday->type) === 'national' ? 'selected' : '' }}>國定假日</option>
                                <option value="custom" {{ old('type', $holiday->type) === 'custom' ? 'selected' : '' }}>自訂假日</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="year" class="form-label">年度</label>
                            <input type="number" name="year" id="year" value="{{ old('year', $holiday->year) }}" 
                                   class="form-control" min="2020" max="2030" required>
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" name="is_working_day" id="is_working_day" value="1" 
                                       class="form-check-input" {{ old('is_working_day', $holiday->is_working_day) ? 'checked' : '' }}>
                                <label for="is_working_day" class="form-check-label">
                                    此日期為工作日（通常不勾選，除非是補班日）
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="alert alert-warning">
                                <h6 class="alert-heading">注意事項</h6>
                                <ul class="mb-0">
                                    <li>修改假日資訊將影響所有公司的假日設定</li>
                                    <li>建議在非營業時間進行修改，避免影響員工考勤</li>
                                    <li>如需刪除此假日，請使用「刪除」按鈕</li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-between">
                            <div>
                                <form action="{{ route('backend.holidays.destroy', $holiday) }}" method="POST" onsubmit="return confirm('確認刪除此假日？此動作無法復原。')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">刪除假日</button>
                                </form>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('backend.holidays.index', ['year' => $holiday->year]) }}" class="btn btn-outline-secondary">取消</a>
                                <button type="submit" class="btn btn-primary">更新假日</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // 當日期改變時，自動更新年度
            $('#date').on('change', function() {
                const date = new Date($(this).val());
                if (date) {
                    $('#year').val(date.getFullYear());
                }
            });

            // 當年度改變時，如果日期已設定，檢查年度是否一致
            $('#year').on('change', function() {
                const date = $('#date').val();
                if (date) {
                    const selectedYear = $(this).val();
                    const dateYear = new Date(date).getFullYear();
                    if (dateYear != selectedYear) {
                        alert('請注意：日期與年度不一致，請重新選擇日期。');
                    }
                }
            });
        });
    </script>
@endsection
