@php
    $position ??= null;
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

<div class="form-check form-switch mt-4">
    <input class="form-check-input" type="checkbox" role="switch" id="is_managerial" name="is_managerial" value="1"
        @checked(old('is_managerial', $position?->is_managerial))>
    <label class="form-check-label" for="is_managerial">此職務為主管職</label>
</div>
