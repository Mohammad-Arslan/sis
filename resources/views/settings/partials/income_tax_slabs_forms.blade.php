<form id="incomeTaxSlabForm" class="mb-2">
    <div class="form-errors mb-2"></div>
    @csrf
    @if($slab)
        @method('PATCH')
        <input type="hidden" name="id" value="{{ $slab->id }}">
    @endif
    <div class="form-group mb-3">
        <label for="fiscal_year">Fiscal Year <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="fiscal_year" id="fiscal_year" value="{{ $slab->fiscal_year ?? '' }}" placeholder="e.g. 2024-2025" required autofocus aria-label="Fiscal Year">
    </div>
    <div class="form-group mb-3">
        <label for="min_salary">Min Salary <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="number" step="0.01" min="0" class="form-control" name="min_salary" id="min_salary" value="{{ $slab->min_salary ?? '' }}" placeholder="e.g. 50,000" required aria-label="Min Salary">
            <span class="input-group-text">PKR</span>
        </div>
    </div>
    <div class="form-group mb-3">
        <label for="max_salary">Max Salary <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="number" step="0.01" min="0" class="form-control" name="max_salary" id="max_salary" value="{{ $slab->max_salary ?? '' }}" placeholder="e.g. 100,000" required aria-label="Max Salary">
            <span class="input-group-text">PKR</span>
        </div>
    </div>
    <div class="form-group mb-3">
        <label for="tax_percent">Tax Percent <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="number" step="0.01" min="0" max="100" class="form-control" name="tax_percent" id="tax_percent" value="{{ $slab->tax_percent ?? '' }}" placeholder="e.g. 10" required aria-label="Tax Percent">
            <span class="input-group-text">%</span>
        </div>
    </div>
    <div class="form-group mb-3">
        <label for="fixed_amount">Fixed Amount</label>
        <div class="input-group">
            <input type="number" step="0.01" min="0" class="form-control" name="fixed_amount" id="fixed_amount" value="{{ $slab->fixed_amount ?? '' }}" placeholder="e.g. 5000" aria-label="Fixed Amount">
            <span class="input-group-text">PKR</span>
        </div>
    </div>
    <div class="form-group mb-4">
        <label for="income_tax_slab_status">Status <span class="text-danger">*</span></label>
        <select class="form-control" name="status" id="income_tax_slab_status" aria-label="Status">
            <option value="1" @if(($slab->status ?? 1) == 1) selected @endif>Active</option>
            <option value="0" @if(isset($slab) && $slab->status == 0) selected @endif>Inactive</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary btn-block w-100 py-2">Save</button>
</form> 