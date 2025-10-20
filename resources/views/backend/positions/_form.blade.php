@php
    $position ??= null;
    $insuranceOptions ??= [];
    $insuranceSummary ??= null;
    $insuranceScheduleAvailable ??= true;
    $referenceSalaryInput = old('reference_salary', $position?->reference_salary);
    $selectedInsuranceGrade = old('insurance_grade', $position?->insurance_grade);
    $formatMoney = fn (?int $value) => is_null($value) ? '—' : number_format($value);
@endphp

<div class="row g-4">
    <div class="col-md-6">
        <label for="department_id" class="form-label">所屬部門</label>
        <select name="department_id" id="department_id" class="form-select" required>
            <option value="">請選擇部門</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}" @selected(old('department_id', $position?->department_id) == $department->id)>
                    {{ $department->name }} @if ($department->company)（{{ $department->company->name }}）@endif
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label for="title" class="form-label">職務名稱</label>
        <input type="text" name="title" id="title" value="{{ old('title', $position?->title) }}" class="form-control" required>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-md-6">
        <label for="position_level_id" class="form-label">職等</label>
        <select name="position_level_id" id="position_level_id" class="form-select">
            <option value="">未指定</option>
            @foreach ($levels as $level)
                <option value="{{ $level->id }}" @selected(old('position_level_id', $position?->position_level_id) == $level->id)>
                    {{ $level->name }}（{{ $level->code }}）
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label for="grade" class="form-label">薪資等級</label>
        <input type="text" name="grade" id="grade" value="{{ old('grade', $position?->grade) }}" class="form-control">
        <div class="form-text">可輸入公司內部職務等級或薪資帶編碼。</div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-md-6">
        <label for="reference_salary" class="form-label">參考薪資</label>
        <div class="input-group">
            <input type="number" name="reference_salary" id="reference_salary" value="{{ $referenceSalaryInput }}"
                min="0" step="500" class="form-control" placeholder="例如：36000">
            <span class="input-group-text">元</span>
        </div>
        <div class="form-text">此職務常見的基本薪資，可用於估算勞保與健保成本。</div>
    </div>
    <div class="col-md-6">
        <label for="insurance_grade" class="form-label">勞／健保投保級距</label>
        <select name="insurance_grade" id="insurance_grade" class="form-select"
            @disabled(!$insuranceScheduleAvailable || empty($insuranceOptions))>
            <option value="">依參考薪資自動判定</option>
            @foreach ($insuranceOptions as $option)
                <option value="{{ $option['grade'] }}" @selected((string) $selectedInsuranceGrade === (string) $option['grade'])>
                    {{ $option['label'] }}
                </option>
            @endforeach
        </select>
        <div class="form-text">
            若未指定，系統會依參考薪資推算相符的勞／健保級距。
        </div>
    </div>
</div>

<div class="form-check form-switch mt-4">
    <input class="form-check-input" type="checkbox" role="switch" id="is_managerial" name="is_managerial" value="1"
        @checked(old('is_managerial', $position?->is_managerial))>
    <label class="form-check-label" for="is_managerial">此職務為主管職</label>
</div>

@if ($insuranceSummary)
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small d-block">參考薪資</span>
                    <span class="fw-semibold">{{ number_format($insuranceSummary['base_salary']) }} 元</span>
                </div>
                <div class="text-end">
                    <span class="text-muted small d-block">投保級距</span>
                    <span class="fw-semibold">{{ $insuranceSummary['grade_label'] ?? '未設定' }}</span>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0 align-middle">
                    <thead class="table-light small text-muted text-uppercase">
                        <tr>
                            <th scope="col" class="text-nowrap">項目</th>
                            <th scope="col" class="text-end text-nowrap">員工負擔</th>
                            <th scope="col" class="text-end text-nowrap">雇主負擔</th>
                            <th scope="col" class="text-end text-nowrap">合計</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-nowrap">勞保（本國籍）</td>
                            <td class="text-end text-nowrap">{{ $formatMoney($insuranceSummary['labor_local']['employee']) }} 元</td>
                            <td class="text-end text-nowrap">{{ $formatMoney($insuranceSummary['labor_local']['employer']) }} 元</td>
                            <td class="text-end text-nowrap">{{ $formatMoney($insuranceSummary['labor_local']['total']) }} 元</td>
                        </tr>
                        <tr>
                            <td class="text-nowrap">健保</td>
                            <td class="text-end text-nowrap">{{ $formatMoney($insuranceSummary['health']['employee']) }} 元</td>
                            <td class="text-end text-nowrap">{{ $formatMoney($insuranceSummary['health']['employer']) }} 元</td>
                            <td class="text-end text-nowrap">{{ $formatMoney($insuranceSummary['health']['total']) }} 元</td>
                        </tr>
                        <tr>
                            <td class="text-nowrap">勞退提繳（6%）</td>
                            <td class="text-end text-nowrap">—</td>
                            <td class="text-end text-nowrap">{{ $formatMoney($insuranceSummary['pension']['employer']) }} 元</td>
                            <td class="text-end text-nowrap">{{ $formatMoney($insuranceSummary['pension']['employer']) }} 元</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white text-muted small">
                若此職務由外籍員工擔任，建議採用員工 {{ $formatMoney($insuranceSummary['labor_foreign']['employee'] ?? null) }} 元、
                雇主 {{ $formatMoney($insuranceSummary['labor_foreign']['employer'] ?? null) }} 元的勞保負擔。
            </div>
        </div>
    </div>
@elseif (
    ! $insuranceScheduleAvailable
    && (
        ($referenceSalaryInput !== null && $referenceSalaryInput !== '')
        || ($selectedInsuranceGrade !== null && $selectedInsuranceGrade !== '')
    )
)
    <div class="alert alert-warning mt-4">
        無法載入勞健保級距資料，請確認 <code>storage/salary_table.json</code> 是否存在。
    </div>
@endif
