<div class="card h-100">
    <div class="card-header">
        <h3 class="card-title h5 mb-0">{{ $title }}</h3>
    </div>
    <div class="card-body">
        @if ($logs->isNotEmpty())
            <ul class="list-group list-group-flush">
                @foreach ($logs as $log)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">{{ $log->recorded_at->format('Y-m-d H:i') }}</div>
                            <div class="text-muted small">{{ $log->type === 'check_in' ? '上班' : '下班' }} · {{ ucfirst($log->source) }}</div>
                        </div>
                        <span class="badge {{ $log->type === 'check_in' ? 'bg-success' : 'bg-secondary' }}">
                            {{ $log->type === 'check_in' ? 'IN' : 'OUT' }}
                        </span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted small mb-0">尚未有打卡紀錄。</p>
        @endif
    </div>
</div>
