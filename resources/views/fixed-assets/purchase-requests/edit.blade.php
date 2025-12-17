@extends('layouts.master')

@section('content')
<x-breadcrumb>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('fixed-assets.purchase-requests.index') }}">Purchase Requests</a></li>
    <li class="breadcrumb-item active">Edit Purchase Request</li>
</x-breadcrumb>

<div class="row">
    <div class="col-lg-12">
        @include('components.flash_message')
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Edit Purchase Request</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('fixed-assets.purchase-requests.update', $purchaseRequest->id) }}" method="POST" id="purchase-request-form">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                                <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id" required>
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id', $purchaseRequest->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }} ({{ $supplier->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="required_date" class="form-label">Required Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('required_date') is-invalid @enderror" id="required_date" name="required_date" value="{{ old('required_date', $purchaseRequest->required_date ? date('Y-m-d', strtotime($purchaseRequest->required_date)) : '') }}" required min="{{ date('Y-m-d') }}">
                                @error('required_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="branch_id" class="form-label">Branch <span class="text-danger">*</span></label>
                                <select class="form-select @error('branch_id') is-invalid @enderror" id="branch_id" name="branch_id" required>
                                    <option value="">Select Branch</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id', $purchaseRequest->branch_id) == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->br_name }} ({{ $branch->branch_code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('branch_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
                                <select class="form-select @error('department_id') is-invalid @enderror" id="department_id" name="department_id" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id', $purchaseRequest->department_id) == $department->id ? 'selected' : '' }}>
                                            {{ $department->department_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="user_id" class="form-label">User <span class="text-danger">*</span></label>
                                <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                    <option value="">Select User</option>
                                    <!-- Users will be loaded via JavaScript -->
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="budget_code" class="form-label">Budget Code</label>
                                <input type="text" class="form-control @error('budget_code') is-invalid @enderror" id="budget_code" name="budget_code" value="{{ old('budget_code', $purchaseRequest->budget_code) }}">
                                @error('budget_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="justification" class="form-label">Justification <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('justification') is-invalid @enderror" id="justification" name="justification" rows="3" required>{{ old('justification', $purchaseRequest->justification) }}</textarea>
                                @error('justification')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="remarks" class="form-label">Remarks</label>
                                <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" name="remarks" rows="3">{{ old('remarks', $purchaseRequest->remarks) }}</textarea>
                                @error('remarks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h5>Items</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="items-table">
                                    <thead>
                                        <tr>
                                            <th>Name <span class="text-danger">*</span></th>
                                            <th>Description</th>
                                            <th>Category <span class="text-danger">*</span></th>
                                            <th>Quantity <span class="text-danger">*</span></th>
                                            <th>Unit Price <span class="text-danger">*</span></th>
                                            <th>Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $items = json_decode($purchaseRequest->items, true);
                                        @endphp
                                        @if(is_array($items) && count($items) > 0)
                                            @foreach($items as $index => $item)
                                                <tr>
                                                    <td>
                                                        <input type="text" class="form-control item-name" name="items[{{ $index }}][name]" value="{{ $item['name'] ?? '' }}" required>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="items[{{ $index }}][description]" value="{{ $item['description'] ?? '' }}">
                                                    </td>
                                                    <td>
                                                        <select class="form-select" name="items[{{ $index }}][category_id]" required>
                                                            <option value="">Select Category</option>
                                                            @foreach($categories as $category)
                                                                <option value="{{ $category->id }}" {{ ($item['category_id'] ?? '') == $category->id ? 'selected' : '' }}>
                                                                    {{ $category->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control item-quantity" name="items[{{ $index }}][quantity]" min="1" value="{{ $item['quantity'] ?? 1 }}" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control item-price" name="items[{{ $index }}][unit_price]" min="0" step="0.01" value="{{ $item['unit_price'] ?? 0.00 }}" required>
                                                    </td>
                                                    <td>
                                                        <span class="item-total">{{ number_format(($item['quantity'] ?? 1) * ($item['unit_price'] ?? 0), 2) }}</span>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm remove-item" {{ count($items) <= 1 ? 'disabled' : '' }}>
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td>
                                                    <input type="text" class="form-control item-name" name="items[0][name]" required>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="items[0][description]">
                                                </td>
                                                <td>
                                                    <select class="form-select" name="items[0][category_id]" required>
                                                        <option value="">Select Category</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control item-quantity" name="items[0][quantity]" min="1" value="1" required>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control item-price" name="items[0][unit_price]" min="0" step="0.01" value="0.00" required>
                                                </td>
                                                <td>
                                                    <span class="item-total">0.00</span>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm remove-item" disabled>
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="7">
                                                <button type="button" class="btn btn-success btn-sm" id="add-item">
                                                    <i class="ri-add-line"></i> Add Item
                                                </button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-end">
                            <a href="{{ route('fixed-assets.purchase-requests.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('footer_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get users based on branch and department
        const branchSelect = document.getElementById('branch_id');
        const departmentSelect = document.getElementById('department_id');
        const userSelect = document.getElementById('user_id');
        const currentUserId = {{ $purchaseRequest->user_id ?? 'null' }};

        function getUsers() {
            const branchId = branchSelect.value;
            const departmentId = departmentSelect.value;

            if (branchId && departmentId) {
                fetch(`{{ route('fixed-assets.get-branch-users') }}?branch_id=${branchId}&department_id=${departmentId}`)
                    .then(response => response.json())
                    .then(data => {
                        userSelect.innerHTML = '<option value="">Select User</option>';
                        data.users.forEach(user => {
                            const option = document.createElement('option');
                            option.value = user.id;
                            option.textContent = user.name;
                            if (user.id === currentUserId) {
                                option.selected = true;
                            }
                            userSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching users:', error));
            }
        }

        branchSelect.addEventListener('change', getUsers);
        departmentSelect.addEventListener('change', getUsers);

        // Load users on page load
        getUsers();

        // Handle item rows
        let itemCount = {{ is_array($items) ? count($items) : 1 }};
        const itemsTable = document.getElementById('items-table');
        const addItemButton = document.getElementById('add-item');

        addItemButton.addEventListener('click', function() {
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>
                    <input type="text" class="form-control item-name" name="items[${itemCount}][name]" required>
                </td>
                <td>
                    <input type="text" class="form-control" name="items[${itemCount}][description]">
                </td>
                <td>
                    <select class="form-select" name="items[${itemCount}][category_id]" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control item-quantity" name="items[${itemCount}][quantity]" min="1" value="1" required>
                </td>
                <td>
                    <input type="number" class="form-control item-price" name="items[${itemCount}][unit_price]" min="0" step="0.01" value="0.00" required>
                </td>
                <td>
                    <span class="item-total">0.00</span>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-item">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </td>
            `;
            itemsTable.querySelector('tbody').appendChild(newRow);
            itemCount++;

            // Enable all remove buttons if we have more than one row
            if (itemsTable.querySelectorAll('tbody tr').length > 1) {
                document.querySelectorAll('.remove-item').forEach(button => {
                    button.disabled = false;
                });
            }

            // Add event listeners to new row
            addItemEventListeners(newRow);
        });

        // Function to add event listeners to item rows
        function addItemEventListeners(row) {
            const quantityInput = row.querySelector('.item-quantity');
            const priceInput = row.querySelector('.item-price');
            const totalSpan = row.querySelector('.item-total');
            const removeButton = row.querySelector('.remove-item');

            function updateTotal() {
                const quantity = parseFloat(quantityInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const total = quantity * price;
                totalSpan.textContent = total.toFixed(2);
            }

            quantityInput.addEventListener('input', updateTotal);
            priceInput.addEventListener('input', updateTotal);

            removeButton.addEventListener('click', function() {
                row.remove();
                
                // Disable the remove button if only one row remains
                if (itemsTable.querySelectorAll('tbody tr').length <= 1) {
                    document.querySelector('.remove-item').disabled = true;
                }
            });

            updateTotal();
        }

        // Add event listeners to the existing rows
        document.querySelectorAll('#items-table tbody tr').forEach(row => {
            addItemEventListeners(row);
        });

        // Form validation
        document.getElementById('purchase-request-form').addEventListener('submit', function(e) {
            const itemRows = itemsTable.querySelectorAll('tbody tr');
            if (itemRows.length === 0) {
                e.preventDefault();
                Swal.fire({
                    title: 'Error!',
                    text: 'Please add at least one item to the purchase request.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
</script>
@endpush
