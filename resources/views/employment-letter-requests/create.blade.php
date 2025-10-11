@extends('layouts.master')

@section('title', 'Create Employment Letter Request')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Create Employment Letter Request</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('employment-letter-requests.index') }}">Employment Letter Requests</a></li>
                        <li class="breadcrumb-item active">Create Request</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Request Employment Letter</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('employment-letter-requests.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="request_type" class="form-label">Request Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('request_type') is-invalid @enderror" 
                                            id="request_type" name="request_type" required>
                                        <option value="">Select Request Type</option>
                                        <option value="employment_letter" {{ old('request_type') == 'employment_letter' ? 'selected' : '' }}>
                                            Employment Letter
                                        </option>
                                        <option value="experience_letter" {{ old('request_type') == 'experience_letter' ? 'selected' : '' }}>
                                            Experience Letter
                                        </option>
                                    </select>
                                    @error('request_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="purpose" class="form-label">Purpose <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('purpose') is-invalid @enderror" 
                                      id="purpose" name="purpose" rows="3" 
                                      placeholder="Please describe the purpose of this letter..." 
                                      required>{{ old('purpose') }}</textarea>
                            <div class="form-text">Maximum 500 characters</div>
                            @error('purpose')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="additional_notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control @error('additional_notes') is-invalid @enderror" 
                                      id="additional_notes" name="additional_notes" rows="3" 
                                      placeholder="Any additional information you'd like to include...">{{ old('additional_notes') }}</textarea>
                            <div class="form-text">Optional - Maximum 1000 characters</div>
                            @error('additional_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('employment-letter-requests.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Back to List
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i> Submit Request
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
