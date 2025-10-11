@extends('layouts.master')
@section('content')
@push('header_scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .section-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 24px;
        margin-bottom: 24px;
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #111827;
        margin-bottom: 16px;
    }

    .asset-list {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 16px;
    }

    .asset-item {
        border-bottom: 1px solid #e5e7eb;
        padding: 16px 0;
    }

    .asset-item:last-child {
        border-bottom: none;
    }

    .remove-asset {
        color: #dc2626;
        cursor: pointer;
    }

    .remove-asset:hover {
        color: #b91c1c;
    }
</style>
@endpush

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-2">Edit Transfer Request</h1>
                    <p class="text-muted">Update transfer request details.</p>
                </div>
                <a href="{{ route('fixed-assets.transfer-requests.show', $transferRequest->id) }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Details
                </a>
            </div>

            <form id="transferRequestForm" action="{{ route('fixed-assets.transfer-requests.update', $transferRequest->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="section-card">
                    <h5 class="section-title">Basic Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $transferRequest->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="reason" class="form-label">Reason for Transfer <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" value="{{ old('reason', $transferRequest->reason) }}" required>
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $transferRequest->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Source and Destination -->
                <div class="section-card">
                    <h5 class="section-title">Source and Destination</h5>
                    <div class="row">
                        <!-- Source -->
                        <div class="col-md-6">
                            <h6 class="mb-3">Source</h6>
                            <div class="mb-3">
                                <label for="source_branch_id" class="form-label">Branch <span class="text-danger">*</span></label>
                                <select class="form-select @error('source_branch_id') is-invalid @enderror" id="source_branch_id" name="source_branch_id" required>
                                    <option value="">Select Branch</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('source_branch_id', $transferRequest->source_branch_id) == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->br_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('source_branch_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="source_department_id" class="form-label">Department</label>
                                <select class="form-select @error('source_department_id') is-invalid @enderror" id="source_department_id" name="source_department_id">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('source_department_id', $transferRequest->source_department_id) == $department->id ? 'selected' : '' }}>
                                            {{ $department->department_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('source_department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Destination -->
                        <div class="col-md-6">
                            <h6 class="mb-3">Destination</h6>
                            <div class="mb-3">
                                <label for="destination_branch_id" class="form-label">Branch <span class="text-danger">*</span></label>
                                <select class="form-select @error('destination_branch_id') is-invalid @enderror" id="destination_branch_id" name="destination_branch_id" required>
                                    <option value="">Select Branch</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('destination_branch_id', $transferRequest->destination_branch_id) == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->br_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('destination_branch_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="destination_department_id" class="form-label">Department</label>
                                <select class="form-select @error('destination_department_id') is-invalid @enderror" id="destination_department_id" name="destination_department_id">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('destination_department_id', $transferRequest->destination_department_id) == $department->id ? 'selected' : '' }}>
                                            {{ $department->department_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('destination_department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="assigned_to_user_id" class="form-label">Assign To User</label>
                                <select class="form-select @error('assigned_to_user_id') is-invalid @enderror" id="assigned_to_user_id" name="assigned_to_user_id" disabled>
                                    <option value="">First select destination branch and department</option>
                                </select>
                                @error('assigned_to_user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assets -->
                <div class="section-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="section-title mb-0">Assets</h5>
                        <button type="button" class="btn btn-outline-primary" id="addAsset">
                            <i class="fas fa-plus me-2"></i>Add Asset
                        </button>
                    </div>
                    
                    <div id="assetsList" class="asset-list">
                        <!-- Asset items will be added here -->
                        @if(count($transferRequest->transferItems) == 0)
                            <div class="text-center text-muted py-4" id="noAssetsMessage">
                                No assets added yet. Click "Add Asset" to start adding assets.
                            </div>
                        @endif
                        @foreach($transferRequest->transferItems as $index => $item)
                            <div class="asset-item" data-index="{{ $index }}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Asset <span class="text-danger">*</span></label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <select class="form-select asset-select" name="assets[{{ $index }}][asset_id]" required>
                                                        <option value="">Select Asset</option>
                                                        @foreach($assets as $asset)
                                                            @php
                                                                $assignedUserName = 'Not Assigned';
                                                                if ($asset->assignedTo) {
                                                                    if (!empty($asset->assignedTo->preferred_name)) {
                                                                        $assignedUserName = $asset->assignedTo->preferred_name;
                                                                    } else {
                                                                        $nameParts = array_filter([
                                                                            $asset->assignedTo->first_name,
                                                                            $asset->assignedTo->middle_name,
                                                                            $asset->assignedTo->last_name
                                                                        ]);
                                                                        $assignedUserName = !empty($nameParts) ? implode(' ', $nameParts) : 'Unknown User';
                                                                    }
                                                                }
                                                            @endphp
                                                            <option value="{{ $asset->id }}" 
                                                                {{ $item->asset_id == $asset->id ? 'selected' : '' }}
                                                                data-condition="{{ $asset->condition }}"
                                                                data-assigned-user="{{ $asset->assigned_to_user_id }}"
                                                                data-assigned-user-name="{{ $assignedUserName }}"
                                                            >
                                                                {{ $asset->name }} ({{ $asset->asset_tag }}) - {{ $asset->status }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    @php
                                                        $currentAssignedUserName = 'Not Assigned';
                                                        if ($item->asset->assignedTo) {
                                                            if (!empty($item->asset->assignedTo->preferred_name)) {
                                                                $currentAssignedUserName = $item->asset->assignedTo->preferred_name;
                                                            } else {
                                                                $nameParts = array_filter([
                                                                    $item->asset->assignedTo->first_name,
                                                                    $item->asset->assignedTo->middle_name,
                                                                    $item->asset->assignedTo->last_name
                                                                ]);
                                                                $currentAssignedUserName = !empty($nameParts) ? implode(' ', $nameParts) : 'Unknown User';
                                                            }
                                                        }
                                                    @endphp
                                                    <input type="text" class="form-control current-user" readonly placeholder="Current Assigned User" 
                                                        value="{{ $currentAssignedUserName }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="assets[{{ $index }}][quantity]" value="{{ $item->quantity }}" min="1" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">Condition</label>
                                            <input type="text" class="form-control" name="assets[{{ $index }}][condition]" value="{{ ucfirst($item->condition) }}" readonly>
                                            <input type="hidden" name="assets[{{ $index }}][condition]" value="{{ $item->condition }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Notes</label>
                                            <input type="text" class="form-control" name="assets[{{ $index }}][notes]" value="{{ $item->notes }}" placeholder="Optional notes">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="mb-3">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" class="btn btn-link text-danger remove-asset" title="Remove Asset">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @error('assets')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end mt-4 mb-2">
                    <a href="{{ route('fixed-assets.transfer-requests.show', $transferRequest->id) }}" class="btn btn-outline-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Transfer Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Asset Template (Hidden) -->
<template id="assetTemplate">
    <div class="asset-item" data-index="{index}">
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Asset <span class="text-danger">*</span></label>
                    <div class="row">
                        <div class="col-md-6">
                            <select class="form-select asset-select" name="assets[{index}][asset_id]" required disabled>
                                <option value="">First select source branch and department</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control current-user" readonly placeholder="Current Assigned User">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="mb-3">
                    <label class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="assets[{index}][quantity]" value="1" min="1" required>
                </div>
            </div>
            <div class="col-md-2">
                <div class="mb-3">
                    <label class="form-label">Condition</label>
                    <input type="text" class="form-control" name="assets[{index}][condition]" readonly>
                    <input type="hidden" name="assets[{index}][condition]">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <input type="text" class="form-control" name="assets[{index}][notes]" placeholder="Optional notes">
                </div>
            </div>
            <div class="col-md-1">
                <div class="mb-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-link text-danger remove-asset" title="Remove Asset">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

@push('footer_scripts')
<script>
$(document).ready(function() {
    let assetIndex = {{ count($transferRequest->transferItems) }};

    // Function to load assets based on branch and department
    function loadAssets(branchId, departmentId, assetSelect) {
        if (!branchId) {
            assetSelect.html('<option value="">Select source branch first</option>');
            assetSelect.prop('disabled', true);
            return;
        }

        assetSelect.html('<option value="">Loading assets...</option>');
        assetSelect.prop('disabled', true);

        $.ajax({
            url: '{{ route("fixed-assets.get-branch-assets") }}',
            method: 'GET',
            data: {
                branch_id: branchId,
                department_id: departmentId
            },
            success: function(response) {
                let options = '<option value="">Select Asset</option>';
                const selectedAssetIds = getSelectedAssetIds();
                
                response.assets.forEach(function(asset) {
                    const isSelected = selectedAssetIds.includes(asset.id.toString());
                    const disabledAttr = isSelected ? 'disabled' : '';
                    const selectedText = isSelected ? ' (Already Selected)' : '';
                    
                    options += `<option value="${asset.id}" 
                        data-condition="${asset.condition}"
                        data-assigned-user="${asset.assigned_to_user_id || ''}"
                        data-assigned-user-name="${asset.assigned_user_name}"
                        ${disabledAttr}
                    >${asset.name} (${asset.asset_tag}) - ${asset.status}${selectedText}</option>`;
                });
                assetSelect.html(options);
                assetSelect.prop('disabled', false);
            },
            error: function() {
                assetSelect.html('<option value="">Error loading assets</option>');
                assetSelect.prop('disabled', true);
            }
        });
    }

    // Function to get all currently selected asset IDs
    function getSelectedAssetIds() {
        const selectedIds = [];
        $('.asset-select').each(function() {
            const selectedValue = $(this).val();
            if (selectedValue && selectedValue !== '') {
                selectedIds.push(selectedValue);
            }
        });
        return selectedIds;
    }

    // Function to update all asset selects to disable already selected assets
    function updateAssetSelects() {
        const selectedAssetIds = getSelectedAssetIds();
        
        $('.asset-select').each(function() {
            const currentSelect = $(this);
            const currentValue = currentSelect.val();
            
            // Update options to disable already selected assets
            currentSelect.find('option').each(function() {
                const optionValue = $(this).val();
                if (optionValue && optionValue !== '') {
                    if (selectedAssetIds.includes(optionValue) && optionValue !== currentValue) {
                        $(this).prop('disabled', true);
                        $(this).text($(this).text() + ' (Already Selected)');
                    } else {
                        $(this).prop('disabled', false);
                        $(this).text($(this).text().replace(' (Already Selected)', ''));
                    }
                }
            });
        });
    }

    // Function to load users based on branch and department
    function loadUsers(branchId, departmentId) {
        const userSelect = $('#assigned_to_user_id');
        
        if (!branchId) {
            userSelect.html('<option value="">Select destination branch first</option>');
            userSelect.prop('disabled', true);
            return;
        }

        userSelect.html('<option value="">Loading users...</option>');
        userSelect.prop('disabled', true);

        $.ajax({
            url: '{{ route("fixed-assets.get-branch-users") }}',
            method: 'GET',
            data: {
                branch_id: branchId,
                department_id: departmentId
            },
            success: function(response) {
                let options = '<option value="">Select User</option>';
                response.users.forEach(function(user) {
                    options += `<option value="${user.id}" ${user.id == {{ $transferRequest->assigned_to_user_id ?? 'null' }} ? 'selected' : ''}>${user.name}</option>`;
                });
                userSelect.html(options);
                userSelect.prop('disabled', false);
            },
            error: function() {
                userSelect.html('<option value="">Error loading users</option>');
                userSelect.prop('disabled', true);
            }
        });
    }

    // Handle source branch/department change
    $('#source_branch_id, #source_department_id').on('change', function() {
        const branchId = $('#source_branch_id').val();
        const departmentId = $('#source_department_id').val();
        
        // Update all asset selects with existing assets data
        $('.asset-select').each(function() {
            const $assetSelect = $(this);
            let options = '<option value="">Select Asset</option>';
            const selectedAssetIds = getSelectedAssetIds();
            
            // Use the existing assets data from the page
            const assetsData = [
                @foreach($assets as $asset)
                    @php
                        $assignedUserName = 'Not Assigned';
                        if ($asset->assignedTo) {
                            if (!empty($asset->assignedTo->preferred_name)) {
                                $assignedUserName = $asset->assignedTo->preferred_name;
                            } else {
                                $nameParts = array_filter([
                                    $asset->assignedTo->first_name,
                                    $asset->assignedTo->middle_name,
                                    $asset->assignedTo->last_name
                                ]);
                                $assignedUserName = !empty($nameParts) ? implode(' ', $nameParts) : 'Unknown User';
                            }
                        }
                    @endphp
                    {
                        id: '{{ $asset->id }}',
                        name: '{{ $asset->name }}',
                        asset_tag: '{{ $asset->asset_tag }}',
                        status: '{{ $asset->status }}',
                        condition: '{{ $asset->condition }}',
                        assigned_user_id: '{{ $asset->assigned_to_user_id }}',
                        assigned_user_name: '{{ $assignedUserName }}'
                    },
                @endforeach
            ];
            
            assetsData.forEach(function(asset) {
                const isSelected = selectedAssetIds.includes(asset.id);
                const disabledAttr = isSelected ? 'disabled' : '';
                const selectedText = isSelected ? ' (Already Selected)' : '';
                
                options += `<option value="${asset.id}" 
                    data-condition="${asset.condition}"
                    data-assigned-user="${asset.assigned_user_id}"
                    data-assigned-user-name="${asset.assigned_user_name}"
                    ${disabledAttr}
                >${asset.name} (${asset.asset_tag}) - ${asset.status}${selectedText}</option>`;
            });
            
            $assetSelect.html(options);
            $assetSelect.prop('disabled', false);
        });
        
        // Update asset selects to disable already selected assets
        updateAssetSelects();
    });

    // Handle destination branch/department change
    $('#destination_branch_id, #destination_department_id').on('change', function() {
        const branchId = $('#destination_branch_id').val();
        const departmentId = $('#destination_department_id').val();
        loadUsers(branchId, departmentId);
    });

    // Handle asset selection change
    $(document).on('change', '.asset-select', function() {
        const selectedOption = $(this).find('option:selected');
        const condition = selectedOption.data('condition');
        const assignedUserName = selectedOption.data('assigned-user-name');
        const $assetItem = $(this).closest('.asset-item');
        
        if (condition) {
            $assetItem.find('input[type="text"][name$="[condition]"]').val(condition);
            $assetItem.find('input[type="hidden"][name$="[condition]"]').val(condition);
        }
        
        // Update current user display
        $assetItem.find('.current-user').val(assignedUserName);
        
        // Update all asset selects to disable already selected assets
        updateAssetSelects();
    });

    // Initialize users dropdown if destination branch/department are selected
    if ($('#destination_branch_id').val()) {
        loadUsers($('#destination_branch_id').val(), $('#destination_department_id').val());
    }

    // Initialize asset selects to disable already selected assets
    updateAssetSelects();

    // Add asset
    $('#addAsset').on('click', function() {
        console.log('Add Asset button clicked');
        const template = $('#assetTemplate').html();
        console.log('Template found:', template ? 'Yes' : 'No');
        
        const newAsset = template.replace(/{index}/g, assetIndex++);
        const $newAsset = $(newAsset);
        $('#assetsList').append($newAsset);

        // Hide the no assets message if it exists
        $('#noAssetsMessage').hide();

        // Load assets for the new select using existing assets data
        const $assetSelect = $newAsset.find('.asset-select');
        console.log('Asset select found:', $assetSelect.length > 0 ? 'Yes' : 'No');
        
        let options = '<option value="">Select Asset</option>';
        const selectedAssetIds = getSelectedAssetIds();
        console.log('Selected asset IDs:', selectedAssetIds);
        
        // Use the existing assets data from the page
        const assetsData = [
            @foreach($assets as $asset)
                @php
                    $assignedUserName = 'Not Assigned';
                    if ($asset->assignedTo) {
                        if (!empty($asset->assignedTo->preferred_name)) {
                            $assignedUserName = $asset->assignedTo->preferred_name;
                        } else {
                            $nameParts = array_filter([
                                $asset->assignedTo->first_name,
                                $asset->assignedTo->middle_name,
                                $asset->assignedTo->last_name
                            ]);
                            $assignedUserName = !empty($nameParts) ? implode(' ', $nameParts) : 'Unknown User';
                        }
                    }
                @endphp
                {
                    id: '{{ $asset->id }}',
                    name: '{{ $asset->name }}',
                    asset_tag: '{{ $asset->asset_tag }}',
                    status: '{{ $asset->status }}',
                    condition: '{{ $asset->condition }}',
                    assigned_user_id: '{{ $asset->assigned_to_user_id }}',
                    assigned_user_name: '{{ $assignedUserName }}'
                },
            @endforeach
        ];
        
        assetsData.forEach(function(asset) {
            const isSelected = selectedAssetIds.includes(asset.id);
            const disabledAttr = isSelected ? 'disabled' : '';
            const selectedText = isSelected ? ' (Already Selected)' : '';
            
            options += `<option value="${asset.id}" 
                data-condition="${asset.condition}"
                data-assigned-user="${asset.assigned_user_id}"
                data-assigned-user-name="${asset.assigned_user_name}"
                ${disabledAttr}
            >${asset.name} (${asset.asset_tag}) - ${asset.status}${selectedText}</option>`;
        });
        
        $assetSelect.html(options);
        $assetSelect.prop('disabled', false);
        console.log('Asset select populated with', options.split('<option').length - 1, 'options');
        
        // Update asset selects to disable already selected assets
        updateAssetSelects();
    });

    // Remove asset
    $(document).on('click', '.remove-asset', function() {
        $(this).closest('.asset-item').remove();
        if ($('.asset-item').length === 0) {
            $('#noAssetsMessage').show();
        }
        
        // Update all asset selects to re-enable removed assets
        updateAssetSelects();
    });

    // Form validation
    $('#transferRequestForm').on('submit', function(e) {
        if ($('.asset-item').length === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Please add at least one asset to transfer.',
                confirmButtonText: 'OK'
            });
            return false;
        }

        const sourceBranch = $('#source_branch_id').val();
        const destinationBranch = $('#destination_branch_id').val();
        if (sourceBranch === destinationBranch) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Source and destination branches cannot be the same.',
                confirmButtonText: 'OK'
            });
            return false;
        }
    });
});
</script>
@endpush

@endsection
