@php
    $bracket ??= null;
@endphp

<div class="row g-4">
    <div class="col-md-6">
        <label for="label" class="form-label">級距名稱</label>
        <input type="text" name="label" id="label" class="form-control" value="{{ old('label', $bracket?->label) }}"
            placeholder="例如：月薪 30,300 元" required>
    </div>
    <div class="col-md-6">
        <label for="grade" class="form-label">投保薪資</label>
        <div class="input-group">
            <input type="number" name="grade" id="grade" class="form-control" value="{{ old('grade', $bracket?->grade) }}"
                min="0" step="100" required>
            <span class="input-group-text">元</span>
        </div>
        <div class="form-text">請輸入對應的投保薪資上限或標準。</div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-md-6">
        <label for="labor_employee_local" class="form-label">勞保保費（員工負擔）</label>
        <div class="input-group">
            <input type="number" name="labor_employee_local" id="labor_employee_local" class="form-control"
                value="{{ old('labor_employee_local', $bracket?->labor_employee_local) }}" min="0" step="1">
            <span class="input-group-text">元</span>
        </div>
    </div>
    <div class="col-md-6">
        <label for="labor_employer_local" class="form-label">勞保保費（雇主負擔）</label>
        <div class="input-group">
            <input type="number" name="labor_employer_local" id="labor_employer_local" class="form-control"
                value="{{ old('labor_employer_local', $bracket?->labor_employer_local) }}" min="0" step="1">
            <span class="input-group-text">元</span>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-md-6">
        <label for="health_employee" class="form-label">健保保費（員工負擔）</label>
        <div class="input-group">
            <input type="number" name="health_employee" id="health_employee" class="form-control"
                value="{{ old('health_employee', $bracket?->health_employee) }}" min="0" step="1">
            <span class="input-group-text">元</span>
        </div>
    </div>
    <div class="col-md-6">
        <label for="health_employer" class="form-label">健保保費（雇主負擔）</label>
        <div class="input-group">
            <input type="number" name="health_employer" id="health_employer" class="form-control"
                value="{{ old('health_employer', $bracket?->health_employer) }}" min="0" step="1">
            <span class="input-group-text">元</span>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-md-6">
        <label for="labor_employee_foreign" class="form-label">勞保保費（員工負擔／外籍）</label>
        <div class="input-group">
            <input type="number" name="labor_employee_foreign" id="labor_employee_foreign" class="form-control"
                value="{{ old('labor_employee_foreign', $bracket?->labor_employee_foreign) }}" min="0" step="1">
            <span class="input-group-text">元</span>
        </div>
        <div class="form-text">若無需填寫可留空。</div>
    </div>
    <div class="col-md-6">
        <label for="labor_employer_foreign" class="form-label">勞保保費（雇主負擔／外籍）</label>
        <div class="input-group">
            <input type="number" name="labor_employer_foreign" id="labor_employer_foreign" class="form-control"
                value="{{ old('labor_employer_foreign', $bracket?->labor_employer_foreign) }}" min="0" step="1">
            <span class="input-group-text">元</span>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-md-6">
        <label for="pension_employer" class="form-label">勞退提繳基準（雇主 6%）</label>
        <div class="input-group">
            <input type="number" name="pension_employer" id="pension_employer" class="form-control"
                value="{{ old('pension_employer', $bracket?->pension_employer) }}" min="0" step="1">
            <span class="input-group-text">元</span>
        </div>
    </div>
</div>

