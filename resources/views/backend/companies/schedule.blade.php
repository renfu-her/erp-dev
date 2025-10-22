@extends('layouts.app')

@section('title', '工作時間設定')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title h3 mb-2">工作時間設定 - {{ $company->name }}</h2>
                    <p class="card-text text-muted">設定公司的工作時間，包括上班時間、下班時間和休息時間。系統會自動計算總工作時數。</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ $workSchedule ? route('backend.companies.schedule.update', [$company, $workSchedule]) : route('backend.companies.schedule.store', $company) }}" method="POST" class="row g-3" id="workScheduleForm">
                        @csrf
                        @if($workSchedule)
                            @method('PUT')
                        @endif

                        <div class="col-md-6">
                            <label for="effective_from" class="form-label">生效日期</label>
                            <input type="date" name="effective_from" id="effective_from" 
                                   value="{{ old('effective_from', $workSchedule?->effective_from?->format('Y-m-d') ?? '2025-01-01') }}" 
                                   class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="effective_until" class="form-label">失效日期</label>
                            <input type="date" name="effective_until" id="effective_until" 
                                   value="{{ old('effective_until', $workSchedule?->effective_until?->format('Y-m-d') ?? '2025-12-31') }}" 
                                   class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="work_start_time" class="form-label">上班時間</label>
                            <input type="time" name="work_start_time" id="work_start_time" 
                                   value="{{ old('work_start_time', $workSchedule?->work_start_time?->format('H:i') ?? '09:00') }}" 
                                   class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="work_end_time" class="form-label">下班時間</label>
                            <input type="time" name="work_end_time" id="work_end_time" 
                                   value="{{ old('work_end_time', $workSchedule?->work_end_time?->format('H:i') ?? '18:00') }}" 
                                   class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="lunch_start_time" class="form-label">休息開始時間</label>
                            <input type="time" name="lunch_start_time" id="lunch_start_time" 
                                   value="{{ old('lunch_start_time', $workSchedule?->lunch_start_time?->format('H:i') ?? '12:00') }}" 
                                   class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="lunch_end_time" class="form-label">休息結束時間</label>
                            <input type="time" name="lunch_end_time" id="lunch_end_time" 
                                   value="{{ old('lunch_end_time', $workSchedule?->lunch_end_time?->format('H:i') ?? '13:00') }}" 
                                   class="form-control" required>
                        </div>

                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">總工作時數：</h6>
                                <p class="mb-0">
                                    <span id="workingHoursDisplay" class="badge bg-primary fs-6">計算中...</span>
                                </p>
                                <small class="text-muted">此數值為上班時間減去休息時間的總工作時數</small>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="alert alert-warning">
                                <h6 class="alert-heading">注意事項：</h6>
                                <ul class="mb-0">
                                    <li>星期六、星期日為例假日，不需要上班</li>
                                    <li>國定假日為非工作日，請參考 <a href="{{ route('backend.holidays.index') }}" target="_blank">2025年國定假日一覽</a></li>
                                    <li>系統會自動計算總工作時數，確保設定正確</li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end gap-2">
                            <a href="{{ route('backend.companies.edit', $company) }}" class="btn btn-outline-secondary">取消</a>
                            <button type="submit" class="btn btn-primary">
                                {{ $workSchedule ? '更新工作時間' : '建立工作時間' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function calculateWorkingHours() {
                const workStart = $('#work_start_time').val();
                const workEnd = $('#work_end_time').val();
                const lunchStart = $('#lunch_start_time').val();
                const lunchEnd = $('#lunch_end_time').val();

                if (workStart && workEnd && lunchStart && lunchEnd) {
                    const start = new Date('2000-01-01 ' + workStart);
                    const end = new Date('2000-01-01 ' + workEnd);
                    const lunchStartTime = new Date('2000-01-01 ' + lunchStart);
                    const lunchEndTime = new Date('2000-01-01 ' + lunchEnd);

                    const totalHours = (end - start) / (1000 * 60 * 60);
                    const lunchHours = (lunchEndTime - lunchStartTime) / (1000 * 60 * 60);
                    const workingHours = totalHours - lunchHours;

                    $('#workingHoursDisplay').text(workingHours.toFixed(1) + ' 小時');
                    
                    if (workingHours < 0) {
                        $('#workingHoursDisplay').removeClass('bg-primary').addClass('bg-danger');
                    } else {
                        $('#workingHoursDisplay').removeClass('bg-danger').addClass('bg-primary');
                    }
                }
            }

            // Calculate on input change
            $('#work_start_time, #work_end_time, #lunch_start_time, #lunch_end_time').on('input', calculateWorkingHours);
            
            // Initial calculation
            calculateWorkingHours();
        });
    </script>
@endsection
