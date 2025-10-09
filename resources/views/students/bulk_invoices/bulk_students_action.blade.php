<div class="text-muted">
    <div class="form-check">
        <input class="form-check-input student_checkbox" type="checkbox" id="student_{{ $row['id'] }}"
            value="{{ $row['id'] }}" {{--{{ isset($check_disable) ? 'checked disabled' : 'disabled'}}--}} style="font-size: 16px">
    </div>
</div>
