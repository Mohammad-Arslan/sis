<form id="deductionTypeForm" class="mb-2">
    <div class="form-errors mb-2"></div>
    @csrf
    @if($deductionType)
        @method('PATCH')
        <input type="hidden" name="id" value="{{ $deductionType->id }}">
    @endif
    <div class="form-group mb-3">
        <label for="name">Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="name" id="name" value="{{ $deductionType->name ?? '' }}" placeholder="e.g. Late Comming" required autofocus aria-label="Name">
    </div>
    <div class="form-group mb-3">
        <label for="description">Description</label>
        <div class="input-group">
            <input type="text" class="form-control" name="description" id="description" value="{{ $deductionType->description ?? '' }}" placeholder="e.g. Late Coming" aria-label="Description">
        </div>
    </div>
    <div class="form-group mb-3">
        <label for="deduction_type_status">Status <span class="text-danger">*</span></label>
        <select class="form-control" name="status" id="deduction_type_status" aria-label="Status">
            <option value="1" @if(($deductionType->status ?? 1) == 1) selected @endif>Active</option>
            <option value="0" @if(isset($deductionType) && $deductionType->status == 0) selected @endif>Inactive</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary btn-block w-100 py-2">Save</button>
</form> 