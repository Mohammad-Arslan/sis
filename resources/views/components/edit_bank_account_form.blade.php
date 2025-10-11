<form class="row g-3 needs-validation" novalidate action="{{ route('bank-accounts.update', $bank_account->id) }}" method="POST">

    @method('PUT')
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('bank_name')) is-invalid @endif" id="bankName" name="bank_name" value="{{ $bank_account->bank_name }}" required>
            <label for="bankName" class="form-label">Bank Name</label>
            <div class="invalid-tooltip">
                @if($errors->has('bank_name'))
                {{ $errors->first('bank_name') }}
                @else
                Bank Name is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('branch_code')) is-invalid @endif" id="branchCode" name="branch_code" value="{{ $bank_account->branch_code }}">
            <label for="branchCode" class="form-label">Branch Code</label>
            <div class="invalid-tooltip">
                @if($errors->has('branch_code'))
                {{ $errors->first('branch_code') }}
                @else
                Branch Code is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('branch_address')) is-invalid @endif" id="branchAddress" name="branch_address" value="{{ $bank_account->branch_address }}">
            <label for="branchAddress" class="form-label">Branch Address</label>
            <div class="invalid-tooltip">
                @if($errors->has('branch_address'))
                {{ $errors->first('branch_address') }}
                @else
                Branch Address is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('account_title')) is-invalid @endif" id="accountTitle" name="account_title" value="{{ $bank_account->account_title }}" required>
            <label for="accountTitle" class="form-label">Account Title</label>
            <div class="invalid-tooltip">
                @if($errors->has('account_title'))
                {{ $errors->first('account_title') }}
                @else
                Account Title is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('account_no')) is-invalid @endif" id="accountNo" name="account_no" value="{{ $bank_account->account_no }}" required>
            <label for="accountNo" class="form-label">Account no</label>
            <div class="invalid-tooltip">
                @if($errors->has('account_no'))
                {{ $errors->first('account_no') }}
                @else
                Account no is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('IBAN')) is-invalid @endif" id="iban" name="IBAN" value="{{ $bank_account->IBAN }}">
            <label for="iban" class="form-label">IBAN</label>
            <div class="invalid-tooltip">
                @if($errors->has('IBAN'))
                {{ $errors->first('IBAN') }}
                @else
                IBAN is required!
                @endif
            </div>
        </div>
    </div>

    @if (isset($company))
    <input type="hidden" name="company_id" value="{{ $company->id }}" />
    @elseif (isset($branch_associate))
    <input type="hidden" name="branch_id" value="{{ $branch->id }}" />
    @elseif (isset($network_associate))
    <input type="hidden" name="nwa_id" value="{{ $network_associate->id }}" />
    @endif


    @csrf
    <div class="col-12 text-end">
        <button class="btn btn-primary" type="submit">Save Changes</button>
        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
    </div>
</form>
