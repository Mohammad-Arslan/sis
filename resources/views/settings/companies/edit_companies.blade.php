<form class="row g-3 needs-validation" novalidate
    action="{{ route('companies.update', $company->id) }}" method="post">
    @csrf
    @method('PUT')
    <div class="col-md-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if ($errors->has('company_name')) is-invalid @endif"
                id="companyName" name="company_name" placeholder="Please enter company name"
                value="{{ $company->company_name }}" required>
            <label for="countryName" class="form-label">Company name</label>
            <div class="invalid-tooltip">
                @if ($errors->has('company_name'))
                    {{ $errors->first('company_name') }}
                @else
                    Company name is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-label-group in-border">
            <textarea class="form-control" name="description" id="companyDescription"
                placeholder="Enter class description here...">{{ $company->description }}</textarea>
            <label for="companyDescription" class="form-label">Description</label>
        </div>
    </div>
    <div class="col-12 text-end">
        <button class="btn btn-primary" type="submit">Save Changes</button>
        <a href="{{ route('companies.index') }}"
            class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
    </div>
</form>