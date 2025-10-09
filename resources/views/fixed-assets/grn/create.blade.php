@extends('layouts.master')

@section('content')
<x-breadcrumb>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('fixed-assets.grn.index') }}">Goods Received Notes</a></li>
    <li class="breadcrumb-item active">Create GRN</li>
</x-breadcrumb>

<div class="row">
    <div class="col-lg-12">
        @include('components.flash_message')
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Create Goods Received Note</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('fixed-assets.grn.store') }}" method="POST" id="grn-form">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="grn_number" class="form-label">GRN Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('grn_number') is-invalid @enderror" id="grn_number" name="grn_number" value="{{ old('grn_number', $grnNumber) }}" required readonly>
                                @error('grn_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="purchase_order_id" class="form-label">Based on Purchase Order</label>
                                <select class="form-select @error('purchase_order_id') is-invalid @enderror" id="purchase_order_id" name="purchase_order_id">
                                    <option value="">Select Purchase Order (Optional)</option>
                                    @foreach($purchaseOrders as $po)
                                        <option value="{{ $po->id }}" {{ old('purchase_order_id') == $po->id ? 'selected' : '' }}>
                                            {{ $po->po_number }} - {{ $po->supplier->name ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('purchase_order_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="received_date" class="form-label">Received Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('received_date') is-invalid @enderror" id="received_date" name="received_date" value="{{ old('received_date', date('Y-m-d')) }}" required>
                                @error('received_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                                <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id" required>
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }} ({{ $supplier->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
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
                                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
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
                                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
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
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="delivery_note_number" class="form-label">Delivery Note Number</label>
                                        <input type="text" class="form-control @error('delivery_note_number') is-invalid @enderror" id="delivery_note_number" name="delivery_note_number" value="{{ old('delivery_note_number') }}">
                                        @error('delivery_note_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="invoice_number" class="form-label">Invoice Number</label>
                                        <input type="text" class="form-control @error('invoice_number') is-invalid @enderror" id="invoice_number" name="invoice_number" value="{{ old('invoice_number') }}">
                                        @error('invoice_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="remarks" class="form-label">Remarks</label>
                                <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" name="remarks" rows="3">{{ old('remarks') }}</textarea>
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
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="7">
                                                <button type="button" class="btn btn-success btn-sm" id="add-item">
                                                    <i class="ri-add-line"></i> Add Item
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-end"><strong>Grand Total:</strong></td>
                                            <td colspan="2"><span id="grand-total">0.00</span></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-end">
                            <a href="{{ route('fixed-assets.grn.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit</button>
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

        function getUsers(callback = null) {
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
                            userSelect.appendChild(option);
                        });
                        
                        // Execute callback if provided (for setting user after loading)
                        if (callback && typeof callback === 'function') {
                            callback();
                        }
                    })
                    .catch(error => console.error('Error fetching users:', error));
            }
        }

        branchSelect.addEventListener('change', getUsers);
        departmentSelect.addEventListener('change', getUsers);

        // Load users on page load if branch and department are already selected
        if (branchSelect.value && departmentSelect.value) {
            getUsers();
        }

        // Handle purchase order selection
        const purchaseOrderSelect = document.getElementById('purchase_order_id');
        purchaseOrderSelect.addEventListener('change', function() {
            const purchaseOrderId = this.value;
            if (purchaseOrderId) {
                fetch(`{{ route('fixed-assets.get-purchase-order-details') }}?purchase_order_id=${purchaseOrderId}`)
                    .then(response => response.json())
                    .then(data => {
                        const po = data.purchaseOrder;
                        
                        // Populate supplier
                        const supplierSelect = document.getElementById('supplier_id');
                        if (po.supplier_id) {
                            supplierSelect.value = po.supplier_id;
                        }
                        
                        // Populate branch
                        if (po.branch_id) {
                            branchSelect.value = po.branch_id;
                        }
                        
                        // Populate department and then get users with callback to set user
                        if (po.department_id) {
                            departmentSelect.value = po.department_id;
                            
                            // Get users for this branch and department, then set the user
                            if (po.user_id) {
                                getUsers(() => {
                                    userSelect.value = po.user_id;
                                });
                            } else {
                                getUsers();
                            }
                        }
                        
                        // Populate items
                        if (po.items) {
                            const itemsTable = document.getElementById('items-table');
                            const tbody = itemsTable.querySelector('tbody');
                            tbody.innerHTML = '';
                            
                            const items = typeof po.items === 'string' ? JSON.parse(po.items) : po.items;
                            
                            items.forEach((item, index) => {
                                const newRow = document.createElement('tr');
                                newRow.innerHTML = `
                                    <td>
                                        <input type="text" class="form-control item-name" name="items[${index}][name]" value="${item.name || ''}" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="items[${index}][description]" value="${item.description || ''}">
                                    </td>
                                    <td>
                                        <select class="form-select" name="items[${index}][category_id]" required>
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">${item.category_id == {{ $category->id }} ? 'selected' : ''}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control item-quantity" name="items[${index}][quantity]" min="1" value="${item.quantity || 1}" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control item-price" name="items[${index}][unit_price]" min="0" step="0.01" value="${item.unit_price || 0.00}" required>
                                    </td>
                                    <td>
                                        <span class="item-total">${(item.quantity * item.unit_price).toFixed(2)}</span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-item" ${items.length <= 1 ? 'disabled' : ''}>
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                `;
                                tbody.appendChild(newRow);
                                
                                // Set category
                                if (item.category_id) {
                                    const categorySelect = newRow.querySelector('select[name^="items["][name$="][category_id]"]');
                                    categorySelect.value = item.category_id;
                                }
                                
                                // Add event listeners
                                addItemEventListeners(newRow);
                            });
                            
                            // Update item count
                            itemCount = items.length;
                            
                            // Update grand total
                            updateGrandTotal();
                        }
                    })
                    .catch(error => console.error('Error fetching purchase order details:', error));
            }
        });

        // Handle item rows
        let itemCount = 1;
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
            
            // Update grand total
            updateGrandTotal();
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
                
                // Update grand total
                updateGrandTotal();
            }

            quantityInput.addEventListener('input', updateTotal);
            priceInput.addEventListener('input', updateTotal);

            removeButton.addEventListener('click', function() {
                row.remove();
                
                // Disable the remove button if only one row remains
                if (itemsTable.querySelectorAll('tbody tr').length <= 1) {
                    document.querySelector('.remove-item').disabled = true;
                }
                
                // Update grand total
                updateGrandTotal();
            });

            updateTotal();
        }

        // Add event listeners to the initial row
        document.querySelectorAll('#items-table tbody tr').forEach(row => {
            addItemEventListeners(row);
        });
        
        // Function to calculate and update the grand total
        function updateGrandTotal() {
            const itemTotals = Array.from(document.querySelectorAll('.item-total')).map(el => parseFloat(el.textContent) || 0);
            const grandTotal = itemTotals.reduce((sum, total) => sum + total, 0);
            document.getElementById('grand-total').textContent = grandTotal.toFixed(2);
        }

        // Form validation
        document.getElementById('grn-form').addEventListener('submit', function(e) {
            const itemRows = itemsTable.querySelectorAll('tbody tr');
            if (itemRows.length === 0) {
                e.preventDefault();
                Swal.fire({
                    title: 'Error!',
                    text: 'Please add at least one item to the goods received note.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
</script>
@endpush
