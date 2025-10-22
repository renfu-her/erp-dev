@extends('layouts.app')

@section('title', '新增國定假日')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title h3 mb-2">新增國定假日</h2>
                    <p class="card-text text-muted">建立新的國定假日或自訂假日，這些日期將被系統識別為非工作日。</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('backend.holidays.store') }}" method="POST" class="row g-3">
                        @csrf

                        <div class="col-md-6">
                            <label for="name" class="form-label">節日名稱</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="date" class="form-label">日期</label>
                            <input type="date" name="date" id="date" value="{{ old('date') }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="type" class="form-label">類型</label>
                            <select name="type" id="type" class="form-select" required>
                                <option value="national" {{ old('type') === 'national' ? 'selected' : '' }}>國定假日</option>
                                <option value="custom" {{ old('type') === 'custom' ? 'selected' : '' }}>自訂假日</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="year" class="form-label">年度</label>
                            <input type="number" name="year" id="year" value="{{ old('year', request('year', date('Y'))) }}" 
                                   class="form-control" min="2020" max="2030" required>
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" name="is_working_day" id="is_working_day" value="1" 
                                       class="form-check-input" {{ old('is_working_day') ? 'checked' : '' }}>
                                <label for="is_working_day" class="form-check-label">
                                    此日期為工作日（通常不勾選，除非是補班日）
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">假日規則說明</h6>
                                <ul class="mb-0">
                                    <li><strong>國定假日：</strong>政府規定的法定假日，所有公司統一適用</li>
                                    <li><strong>自訂假日：</strong>公司自訂的特殊假日，可依需求設定</li>
                                    <li><strong>工作日選項：</strong>勾選後此日期將被視為工作日（如補班日）</li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end gap-2">
                            <a href="{{ route('backend.holidays.index', ['year' => request('year', date('Y'))]) }}" class="btn btn-outline-secondary">取消</a>
                            <button type="submit" class="btn btn-primary">建立假日</button>
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
