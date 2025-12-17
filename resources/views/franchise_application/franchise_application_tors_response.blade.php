@extends('layouts.master')
@section('content')
    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('franchise-applications.index') }}">Franchise Applications</a></li>
        <li class="breadcrumb-item active">{{ isset($franchiseApplicationTors) ? 'Edit' : 'Create' }} Terms of Reference
        </li>
    </x-breadcrumb>

    @include('components.flash_message')
    @if(auth()->user()->hasPermission('add-franchise-application-tor') || (isset($franchiseApplicationTors) && auth()->user()->hasPermission('edit-franchise-application-tor')))
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Terms of Reference (TOR's)</h4>

                @if ($franchise_application['franchise_application_qa'] && auth()->user()->hasPermission('download-pdf-franchise-app-qa'))
                    <a href="{{ route('franchise-application-qa.create_pdf', $franchise_application['franchise_application_qa']['id']) }}"
                        class="btn btn-sm btn-primary pull-right">
                        Download QA Application PDF
                    </a>
                @endif
            </div><!-- end card header -->

            <div class="card-body">
                <div class="live-preview">
                        <form class="row mt-3 g-2 needs-validation"
                            action="{{ isset($franchiseApplicationTors) ? route('franchise-application-tors.update', $franchiseApplicationTors->id) : route('franchise-application-tors.store') }}"
                            method="POST" novalidate>
                            @csrf

                            @if (isset($franchiseApplicationTors))
                                @method('PATCH')
                            @endif

                            <input type="hidden" name="franchise_application_id" value="{{ $franchise_application['id'] }}">
                            <input type="hidden" name="agreement_type" value="{{ isset($franchiseApplicationTors)  ? $franchiseApplicationTors['agreement_type'] : $franchise_application['agreement_type']  }}">
                            <input type="hidden" name="class_group_id"
                                value="{{ !empty($franchiseApplicationTors['class_group_id']) ? $franchiseApplicationTors['class_group_id'] : (isset($franchise_application['franchise_application_qa']) ? $franchise_application['franchise_application_qa']['class_group']['id'] : '') }}">
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="form-label-group in-border">
                                        <input type="text" class="form-control" name="school_type" placeholder="School Type"
                                            value="{{ !empty($franchiseApplicationTors['class_group_id']) ? $franchiseApplicationTors['class_group']['name'] : (isset($franchise_application['franchise_application_qa']) ? $franchise_application['franchise_application_qa']['class_group']['name'] : '') }}"
                                            disabled>
                                        <label for="school_type" class="form-label">School Type</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-label-group in-border">
                                        <select class="load-select form-select" id="agreement_type_disabled" name="agreement_type_disabled" disabled>
                                            <option value="">Select</option>
                                            @if(isset($franchiseApplicationTors))
                                                <option value="MOU"
                                                    {{ $franchiseApplicationTors['agreement_type'] == 'MOU' ? 'selected' : '' }}>
                                                    MOU</option>
                                                <option value="FA"
                                                    {{ $franchiseApplicationTors['agreement_type'] == 'FA' ? 'selected' : ''  }}>
                                                    FA</option>
                                            @else
                                                <option value="MOU"
                                                @if($franchise_application['agreement_type'] == 'MOU') {{'selected'}} @else {{''}} @endif>
                                                MOU</option>
                                                <option value="FA"
                                                @if($franchise_application['agreement_type'] == 'FA') {{'selected'}} @else {{''}} @endif>
                                                FA </option>
                                            @endif
                                        </select>
                                        <label class="form-label">Agreement Type</label>
                                        <div class="invalid-tooltip">
                                            Agreement Type is required!
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="form-label-group in-border">
                                        <input type="number" class="form-control" name="total_franchise_fee"
                                            id="total_franchise_fee"
                                            value="{{ isset($franchiseApplicationTors) ? $franchiseApplicationTors['total_franchise_fee'] : '' }}"
                                            placeholder="Total Franchise Fee" required onchange="calculateReceivedAmount()">
                                        <label for="total_franchise_fee" class="form-label">Total Franchise Fee</label>
                                        <div class="invalid-tooltip">
                                            Total Franchise Fee is required!
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-label-group in-border">
                                        <input type="number" step="0.01" class="form-control" name="royalty_rate" id="royalty_rate"
                                            value="{{ isset($franchiseApplicationTors) ? $franchiseApplicationTors['royalty_rate'] : '' }}"
                                            placeholder="Royalty Rate" required>
                                        <label for="royalty_rate" class="form-label">Royalty Rate</label>
                                        <div class="invalid-tooltip">
                                            Royalty Rate is required! Please enter number OR float values.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="form-label-group in-border">
                                        <input type="number" class="form-control" name="payment_on_mou"
                                            value="{{ isset($franchiseApplicationTors) ? $franchiseApplicationTors['payment_on_mou'] : '' }}"
                                            id="payment_on_mou" placeholder="Payment On MOU" @if($franchise_application['agreement_type'] == 'MOU') {{ 'required' }} @endif>
                                        <label class="form-label">Payment On MOU (PKR)</label>
                                        <div class="invalid-tooltip">
                                            Payment On MOU is required!
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-label-group in-border">
                                        <select class="load-select form-select" id="mou_payment_mode" name="mou_payment_mode">
                                            <option value="">Select</option>
                                            <option value="cash"
                                                {{ isset($franchiseApplicationTors) && $franchiseApplicationTors['mou_payment_mode'] == 'cash' ? 'selected' : '' }}>
                                                Cash</option>
                                            <option value="draft"
                                                {{ isset($franchiseApplicationTors) && $franchiseApplicationTors['mou_payment_mode'] == 'draft' ? 'selected' : '' }}>
                                                Draft</option>
                                            <option value="cheque"
                                                {{ isset($franchiseApplicationTors) && $franchiseApplicationTors['mou_payment_mode'] == 'cheque' ? 'selected' : '' }}>
                                                Cheque</option>
                                        </select>
                                        <label class="form-label">MOU Payment Mode</label>
                                        {{-- <div class="invalid-tooltip">
                                            MOU Payment Mode is required!
                                        </div> --}}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-label-group in-border">
                                        <input type="number" class="form-control" name="payment_on_agreement"
                                            value="{{ isset($franchiseApplicationTors) ? $franchiseApplicationTors['payment_on_agreement'] : '' }}"
                                            id="payment_on_agreement" placeholder="Payment On Agreement" >
                                        <label class="form-label">Payment On Agreement (PKR)</label>
                                        <div class="invalid-tooltip">
                                            Payment On Agreement is required!
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="form-label-group in-border">
                                        <select class="load-select form-select" id="agreement_payment_mode"
                                            name="agreement_payment_mode" >
                                            <option value="">Select</option>
                                            <option value="cash"
                                                {{ isset($franchiseApplicationTors) && $franchiseApplicationTors['agreement_payment_mode'] == 'cash' ? 'selected' : '' }}>
                                                Cash</option>
                                            <option value="draft"
                                                {{ isset($franchiseApplicationTors) && $franchiseApplicationTors['agreement_payment_mode'] == 'draft' ? 'selected' : '' }}>
                                                Draft</option>
                                            <option value="cheque"
                                                {{ isset($franchiseApplicationTors) && $franchiseApplicationTors['agreement_payment_mode'] == 'cheque' ? 'selected' : '' }}>
                                                Cheque</option>
                                        </select>
                                        <label class="form-label">Agreement Payment Mode</label>
                                        <div class="invalid-tooltip">
                                            Agreement Payment Mode is required!
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-label-group in-border">
                                        <input type="number" class="form-control" name="token_money"
                                            value="{{ isset($franchiseApplicationTors) ? $franchiseApplicationTors['token_money'] : '0' }}"
                                            id="token_money" placeholder="Token Money" onchange="calculateReceivedAmount()">
                                        <label class="form-label">Token Money (PKR)</label>
                                        <div class="invalid-tooltip">
                                            Token Money is required!
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-label-group in-border">
                                        <select class="load-select form-select" id="token_money_mode" name="token_money_mode"
                                            >
                                            <option value="">Select</option>
                                            <option value="cash"
                                                {{ isset($franchiseApplicationTors) && $franchiseApplicationTors['token_money_mode'] == 'cash' ? 'selected' : '' }}>
                                                Cash</option>
                                            <option value="draft"
                                                {{ isset($franchiseApplicationTors) && $franchiseApplicationTors['token_money_mode'] == 'draft' ? 'selected' : '' }}>
                                                Draft</option>
                                            <option value="cheque"
                                                {{ isset($franchiseApplicationTors) && $franchiseApplicationTors['token_money_mode'] == 'cheque' ? 'selected' : '' }}>
                                                Cheque</option>
                                        </select>
                                        <label class="form-label">Token Money Mode</label>
                                        <div class="invalid-tooltip">
                                            Token Money Mode is required!
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="input-group form-label-group in-border">
                                        <input type="text" class="form-control" name="renovation_period" id="renovation_period" data-provider="flatpickr" data-date-format="Y-m-d" data-deafult-date="" value="{{old('renovation_period') ? old('renovation_period') : (isset($franchiseApplicationTors['renovation_period']) ? $franchiseApplicationTors['renovation_period'] : '')}}" >
                                        <div class="input-group-text bg-primary border-primary text-white">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                        <label class="form-label">Renovation Period</label>
                                        {{-- <div class="invalid-tooltip">
                                            @if ($errors->has('renovation_period'))
                                                {{ $errors->first('renovation_period') }}
                                            @else
                                                Renovation Period is required!
                                            @endif
                                        </div> --}}
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3 mt-5">
                                <div class="col-md-4">
                                    <label class="form-label">Building Type: &nbsp;&nbsp;&nbsp;</label>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" class="form-check-input" name="building_type" id="building_type_new"
                                            value="new"
                                            {{ isset($franchiseApplicationTors) && $franchiseApplicationTors['building_type'] == 'new' ? 'checked' : '' }}>
                                        <label>New</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" class="form-check-input" name="building_type"
                                            id="building_type_renovate" value="renovate"
                                            {{ isset($franchiseApplicationTors) && $franchiseApplicationTors['building_type'] == 'renovate' ? 'checked' : '' }}>
                                        <label>Renovate</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group form-label-group in-border">
                                        <input type="text" class="form-control " name="new_renovate_date_from" id="new_renovate_date_from" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" value="{{old('new_renovate_date_from') ? old('new_renovate_date_from') : (isset($franchiseApplicationTors['new_renovate_date_from']) ? $franchiseApplicationTors['new_renovate_date_from'] : '')}}" >
                                        <div class="input-group-text bg-primary border-primary text-white">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                        <label class="form-label">New/Renovate Date From</label>
                                        {{-- <div class="invalid-tooltip">
                                            @if ($errors->has('new_renovate_date_from'))
                                                {{ $errors->first('new_renovate_date_from') }}
                                            @else
                                                New/Renovate Date From is required!
                                            @endif
                                        </div> --}}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group form-label-group in-border">
                                        <input type="text" class="form-control " name="new_renovate_date_to" id="new_renovate_date_to" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" value="{{old('new_renovate_date_to') ? old('new_renovate_date_to') : (isset($franchiseApplicationTors['new_renovate_date_to']) ? $franchiseApplicationTors['new_renovate_date_to'] : '')}}" >
                                        <div class="input-group-text bg-primary border-primary text-white">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                        <label class="form-label">New/Renovate Date To</label>
                                        {{-- <div class="invalid-tooltip">
                                            @if ($errors->has('new_renovate_date_to'))
                                                {{ $errors->first('new_renovate_date_to') }}
                                            @else
                                                New/Renovate Date To is required!
                                            @endif
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">

                                <div class="col-md-4">
                                    <div class="form-label-group in-border">
                                        <input type="number" class="form-control" name="amount_received"
                                            value="{{ isset($franchiseApplicationTors) ? $franchiseApplicationTors['amount_received'] : '' }}"
                                            id="amount_received" placeholder="Amount Received" required>
                                        <label for="amount_received" class="form-label">Amount Received (PKR)</label>
                                        <div class="invalid-tooltip">
                                            Amount Received is required!
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="input-group form-label-group in-border">
                                        <input type="text" class="form-control {{$errors->has('agreement_date') ? 'is-invalid' : ''}}" name="agreement_date" id="agreement_date" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" value="{{old('agreement_date') ? old('agreement_date') : (isset($franchiseApplicationTors['agreement_date']) ? $franchiseApplicationTors['agreement_date'] : '')}}" required>
                                        <label class="form-label">Agreement Date</label>
                                        <div class="input-group-text bg-primary border-primary text-white">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                        <div class="invalid-tooltip">
                                            @if ($errors->has('agreement_date'))
                                                {{ $errors->first('agreement_date') }}
                                            @else
                                                Agreement Date is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group form-label-group in-border">
                                        <input type="text" class="form-control {{$errors->has('operational_date') ? 'is-invalid' : ''}}" name="operational_date" id="operational_date" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" value="{{old('operational_date') ? old('operational_date') : (isset($franchiseApplicationTors['operational_date']) ? $franchiseApplicationTors['operational_date'] : '')}}" required>
                                        <label class="form-label">Operational Date</label>
                                        <div class="input-group-text bg-primary border-primary text-white">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                        <div class="invalid-tooltip">
                                            @if ($errors->has('operational_date'))
                                                {{ $errors->first('operational_date') }}
                                            @else
                                                Operational Date is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group form-label-group in-border">
                                        <input type="text" class="form-control {{$errors->has('actual_operational_date') ? 'is-invalid' : ''}}" name="actual_operational_date" id="actual_operational_date" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" value="{{old('actual_operational_date') ? old('actual_operational_date') : (isset($franchiseApplicationTors['actual_operational_date']) ? $franchiseApplicationTors['actual_operational_date'] : '')}}" required>
                                        <label class="form-label">Actual Operational Date</label>
                                        <div class="input-group-text bg-primary border-primary text-white">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                        <div class="invalid-tooltip">
                                            @if ($errors->has('actual_operational_date'))
                                                {{ $errors->first('actual_operational_date') }}
                                            @else
                                                Actual Operational Date is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group form-label-group in-border">
                                        <input type="text" class="form-control" name="renewal_date" id="renewal_date" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" value="{{old('renewal_date') ? old('renewal_date') : (isset($franchiseApplicationTors['renewal_date']) ? $franchiseApplicationTors['renewal_date'] : '')}}">
                                        <label class="form-label">Renewal Date</label>
                                        <div class="input-group-text bg-primary border-primary text-white">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                        {{-- <div class="invalid-tooltip">
                                            @if ($errors->has('renewal_date'))
                                                {{ $errors->first('renewal_date') }}
                                            @else
                                                Renewal Date is required!
                                            @endif
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="form-label-group in-border">
                                        <input type="text" class="form-control" name="bank_name"
                                            value="{{ isset($franchiseApplicationTors) ? $franchiseApplicationTors['bank_name'] : '' }}"
                                            id="bank_name" placeholder="Bank Name">
                                        <label for="bank_name" class="form-label">Bank Name</label>
                                        <div class="invalid-tooltip">
                                            Bank Name is required!
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-label-group in-border">
                                        <input type="text" class="form-control" name="bank_account"
                                            value="{{ isset($franchiseApplicationTors) ? $franchiseApplicationTors['bank_account'] : '' }}"
                                            id="bank_account" placeholder="Bank Account">
                                        <label for="bank_account" class="form-label">Bank Account</label>
                                        <div class="invalid-tooltip">
                                            Bank Account is required!
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group form-label-group in-border">
                                        <input type="text" class="form-control {{$errors->has('bank_acc_opening_date') ? 'is-invalid' : ''}}" name="bank_acc_opening_date" id="bank_acc_opening_date" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" value="{{old('bank_acc_opening_date') ? old('bank_acc_opening_date') : (isset($franchiseApplicationTors['bank_acc_opening_date']) ? $franchiseApplicationTors['bank_acc_opening_date'] : '')}}" >
                                        <div class="input-group-text bg-primary border-primary text-white">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                        <label class="form-label">Opening Date</label>
                                        <div class="invalid-tooltip">
                                            Opening Date is required!
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3 mt-5">
                                <div class="col-md-4">
                                    <div class="form-label-group in-border">
                                        <select class="load-select form-select" id="review_by" name="review_by" required>
                                            <option value="">Select</option>
                                            @foreach ($review_by as $user)
                                                <option value="{{ $user['user_id'] }}"
                                                    {{ isset($franchiseApplicationTors) && $franchiseApplicationTors->review_by == $user['user_id'] ? 'selected' : '' }}>
                                                    {{ $user['user']['name'] }}</option>
                                            @endforeach
                                        </select>
                                        <label class="form-label">Review By</label>
                                        <div class="invalid-tooltip">
                                            Review By is required!
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group form-label-group in-border">
                                        <input type="text" class="form-control " name="review_date" id="review_date" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" value="{{old('review_date') ? old('review_date') : (isset($franchiseApplicationTors['review_date']) ? $franchiseApplicationTors['review_date'] : '')}}" >
                                        <label class="form-label">Review Date</label>
                                        <div class="input-group-text bg-primary border-primary text-white">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                        {{-- <div class="invalid-tooltip">
                                            @if ($errors->has('review_date'))
                                                {{ $errors->first('review_date') }}
                                            @else
                                                Review Date is required!
                                            @endif
                                        </div> --}}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-label-group in-border">
                                        <select class="load-select form-select" id="forward_to" name="forward_to" required>
                                            <option value="">Select</option>
                                            @foreach ($forward_to as $user)
                                                <option value="{{ $user['user_id'] }}"
                                                    {{ isset($franchiseApplicationTors) && $franchiseApplicationTors->forward_to == $user['user_id'] ? 'selected' : '' }}>
                                                    {{ $user['user']['name'] }}</option>
                                            @endforeach
                                        </select>
                                        <label class="form-label">Forward To</label>
                                        <div class="invalid-tooltip">
                                            Forward To is required!
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="form-label-group in-border">
                                        <select class="load-select form-select" id="dd_status" name="status" required>
                                            <option value="">Select</option>
                                            <option value="pending"
                                                {{ isset($franchiseApplicationTors) && $franchiseApplicationTors['status'] == 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="approved"
                                                {{ isset($franchiseApplicationTors) && $franchiseApplicationTors['status'] == 'approved' ? 'selected' : '' }}>
                                                Approved</option>
                                            <option value="not_approved"
                                                {{ isset($franchiseApplicationTors) && $franchiseApplicationTors['status'] == 'not_approved' ? 'selected' : '' }}>
                                                Not Approved</option>
                                        </select>
                                        <label class="form-label">Status</label>
                                        <div class="invalid-tooltip">
                                            Status is required!
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="input-group form-label-group in-border">
                                        <input type="text" class="form-control" name="approval_date" id="approval_date" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" value="{{old('approval_date') ? old('approval_date') : (isset($franchiseApplicationTors['approval_date']) ? $franchiseApplicationTors['approval_date'] : '')}}" >
                                        <label class="form-label">Approval Date</label>
                                        <div class="input-group-text bg-primary border-primary text-white">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                        {{-- <div class="invalid-tooltip">
                                            @if ($errors->has('approval_date'))
                                                {{ $errors->first('approval_date') }}
                                            @else
                                                Approval Date is required!
                                            @endif
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-label-group in-border">
                                    <textarea class="form-control" name="remarks" id="remarks"
                                        placeholder="Write Here...">{{ old('remarks') ? old('remarks') : (isset($franchiseApplicationTors['remarks']) ? $franchiseApplicationTors['remarks'] : '') }}</textarea>
                                    <label class="form-label">Remarks</label>
                                </div>
                            </div>
                            @if(auth()->user()->hasPermission('add-franchise-application-tor') || (isset($franchiseApplicationTors) && auth()->user()->hasPermission('update-franchise-application-tor')))
                                <div class="col-12 text-end">
                                    <button class="btn btn-primary" type="submit">Save Changes</button>
                                    <a href="{{ route('franchise-application-tors.create',$franchise_application->id) }}" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                                </div>
                            @endif
                        </form>
                    </div>
            </div>
        </div>
    @endif

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Term of Reference List</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <table id="tors-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                    style="width:100%">
                    <thead>
                        <tr>
                            <th>Review By</th>
                            <th>Review Date</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            <th>Forward To</th>
                            <th>Recommended By</th>
                            <th>Approval Date</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Review By</th>
                            <th>Review Date</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            <th>Forward To</th>
                            <th>Recommended By</th>
                            <th>Approval Date</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>


            </div>
        </div>
    </div>
@endsection
@push('header_scripts')
    <style type="text/css">

    </style>
@endpush
@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#tors-data-table').DataTable({
                processing: true,
                searching: false,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                ordering: true,
                pageLength: 10,
                scrollX: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
                    searchPlaceholder: "Search..."
                },
                ajax: {
                    url: "{{ route('franchise-application-tors.index') }}",
                    data: function(d) {
                        d.franchise_application_id = {{ $franchise_application['id'] }};
                    },
                    dataType: "json",
                    method: 'GET'
                },
                columns: [{
                        data: 'review_by',
                        name: 'review_by'
                    },
                    {
                        data: 'review_date',
                        name: 'review_date',
                        width: "15%"
                    },
                    {
                        data: 'remarks',
                        name: 'remarks'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'forwarded_to',
                        name: 'forwarded_to'
                    },
                    {
                        data: 'recommended_by',
                        name: 'recommended_by'
                    },
                    {
                        data: 'approval_date',
                        name: 'approval_date',
                        width: "15%"
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        width: "15%"
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: "5%",
                        sClass: 'text-center'
                    },
                ],
            });
        });

        function calculateReceivedAmount()
        {

            var token_money = 0;
            var total_franchise_fee = 0 ;
            var amount = 0;
            token_money = parseInt($('#token_money').val());
            total_franchise_fee = parseInt($('#total_franchise_fee').val());
            if(token_money >= 0)
            {
                //
            }
            else
            {
                token_money = 0;
            }
            if(total_franchise_fee >= 0)
            {
                //
            }
            else
            {
                total_franchise_fee = 0;
            }
            amount = total_franchise_fee + token_money;
            $('#amount_received').val('');
            $('#amount_received').val(amount);
        }
    </script>
@endpush
