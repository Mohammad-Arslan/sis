<div class="text-muted">
  <div class="form-check">
      <!--<input class="form-check-input student_withdrawal_checkbox" type="checkbox" id="invoice_{{ $row['id'] }}"
          value="{{ $row['id'] }}" style="font-size: 16px">-->

          <select class="filter form-select" id="auto-withdrawal-status-change" name="status" data-student-id="{{$row['id']}}">
              <option value="on_roll" {{ $row['status'] == 'on_roll' ? 'selected' : '' }}>On Roll</option>
              <option value="registered" {{ $row['status'] == 'registered' ? 'selected' : '' }}>Registered</option>
              <option value="processing" {{ $row['status'] == 'processing' ? 'selected' : '' }}>Processing</option>
              <option value="left" {{ $row['status'] == 'left' ? 'selected' : '' }}>Left</option>
          </select>

  </div>
</div>
