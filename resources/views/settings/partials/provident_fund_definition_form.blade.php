<form id="definitionForm" class="mb-2">
    <div class="form-errors mb-2"></div>
    @csrf
    @if($definition)
        @method('PATCH')
        <input type="hidden" name="id" value="{{ $definition->id }}">
    @endif
    <div class="form-group mb-3">
        <label for="fiscal_year">Fiscal Year <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="fiscal_year" id="fiscal_year" value="{{ $definition->fiscal_year ?? '' }}" placeholder="e.g. 2024-2025" required autofocus aria-label="Fiscal Year">
    </div>
    <div class="form-group mb-3">
        <label for="employee_contribution_percent">Employee Contribution (%) <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="number" step="0.01" min="0" max="100" class="form-control" name="employee_contribution_percent" id="employee_contribution_percent" value="{{ $definition->employee_contribution_percent ?? '' }}" placeholder="e.g. 8.00" required aria-label="Employee Contribution Percent">
            <span class="input-group-text">%</span>
        </div>
    </div>
    <div class="form-group mb-3">
        <label for="employer_contribution_percent">Employer Contribution (%) <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="number" step="0.01" min="0" max="100" class="form-control" name="employer_contribution_percent" id="employer_contribution_percent" value="{{ $definition->employer_contribution_percent ?? '' }}" placeholder="e.g. 8.00" required aria-label="Employer Contribution Percent">
            <span class="input-group-text">%</span>
        </div>
    </div>
    <div class="form-group mb-4">
        <label for="provident_fund_status">Status <span class="text-danger">*</span></label>
        <select class="form-control" name="status" id="provident_fund_status" aria-label="Status">
            <option value="1" @if(($definition->status ?? 1) == 1) selected @endif>Active</option>
            <option value="0" @if(isset($definition) && $definition->status == 0) selected @endif>Inactive</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary btn-block w-100 py-2">Save</button>
</form> 