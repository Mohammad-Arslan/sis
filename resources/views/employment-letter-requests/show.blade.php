@extends('layouts.master')

@section('title', 'View Employment Letter Request')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Employment Letter Request Details</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('employment-letter-requests.index') }}">Employment Letter Requests</a></li>
                        <li class="breadcrumb-item active">View Request</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Request Details</h4>
                        <div>
                            @if($employmentLetterRequest->status === 'approved' && $employmentLetterRequest->letter_file_path)
                                <a href="{{ route('employment-letter-requests.download', $employmentLetterRequest) }}" 
                                   class="btn btn-success">
                                    <i class="fas fa-download"></i> Download Letter
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Request Type:</th>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ ucfirst(str_replace('_', ' ', $employmentLetterRequest->request_type)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>{!! $employmentLetterRequest->status_badge !!}</td>
                                </tr>
                                <tr>
                                    <th>Requested Date:</th>
                                    <td>{{ $employmentLetterRequest->created_at->format('M d, Y h:i A') }}</td>
                                </tr>
                                @if($employmentLetterRequest->approved_at)
                                    <tr>
                                        <th>Approved Date:</th>
                                        <td>{{ $employmentLetterRequest->approved_at->format('M d, Y h:i A') }}</td>
                                    </tr>
                                @endif
                                @if($employmentLetterRequest->approvedBy)
                                    <tr>
                                        <th>Approved By:</th>
                                        <td>{{ $employmentLetterRequest->approvedBy->name }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Employee:</th>
                                    <td>{{ $employmentLetterRequest->employee->user->first_name }} {{ $employmentLetterRequest->employee->user->last_name }}</td>
                                </tr>
                                <tr>
                                    <th>Employee ID:</th>
                                    <td>{{ $employmentLetterRequest->employee->employee_id }}</td>
                                </tr>
                                <tr>
                                    <th>Designation:</th>
                                    <td>{{ $employmentLetterRequest->employee->designation->designation_name ?: 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <h5>Purpose</h5>
                            <p class="text-muted">{{ $employmentLetterRequest->purpose }}</p>
                        </div>
                    </div>

                    @if($employmentLetterRequest->additional_notes)
                        <div class="row">
                            <div class="col-12">
                                <h5>Additional Notes</h5>
                                <p class="text-muted">{{ $employmentLetterRequest->additional_notes }}</p>
                            </div>
                        </div>
                    @endif

                    @if($employmentLetterRequest->rejection_reason)
                        <div class="row">
                            <div class="col-12">
                                <h5>Rejection Reason</h5>
                                <div class="alert alert-danger">
                                    {{ $employmentLetterRequest->rejection_reason }}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($employmentLetterRequest->status === 'approved' && $employmentLetterRequest->letter_content)
                        <div class="row">
                            <div class="col-12">
                                <h5>Generated Letter Preview</h5>
                                <div class="border p-3" style="background-color: #f8f9fa;">
                                    {!! $employmentLetterRequest->letter_content !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('employment-letter-requests.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to List
                                </a>
                                
                                @if($employmentLetterRequest->status === 'pending' && $employmentLetterRequest->employee_id === Auth::user()->employee->id)
                                    <div>
                                        <a href="{{ route('employment-letter-requests.edit', $employmentLetterRequest) }}" 
                                           class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Edit Request
                                        </a>
                                        
                                        <a href="{{ route('employment-letter-requests.destroy', $employmentLetterRequest) }}" 
                                           class="btn btn-danger delete-record"
                                           data-table="employment-letter-requests">
                                            <i class="fas fa-trash"></i> Delete Request
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
