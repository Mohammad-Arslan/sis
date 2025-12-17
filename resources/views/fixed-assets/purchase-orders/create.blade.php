@extends('layouts.master')

@section('content')
<x-breadcrumb>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('fixed-assets.purchase-orders.index') }}">Purchase Orders</a></li>
    <li class="breadcrumb-item active">Create Purchase Order</li>
</x-breadcrumb>

<div class="row">
    <div class="col-lg-12">
        @include('components.flash_message')
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Create Purchase Order</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('fixed-assets.purchase-orders.store') }}" method="POST" id="purchase-order-form">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="po_number" class="form-label">PO Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('po_number') is-invalid @enderror" id="po_number" name="po_number" value="{{ old('po_number', $poNumber) }}" required readonly>
                                @error('po_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="purchase_request_id" class="form-label">Based on Purchase Request</label>
                                <select class="form-select @error('purchase_request_id') is-invalid @enderror" id="purchase_request_id" name="purchase_request_id">
                                    <option value="">Select Purchase Request (Optional)</option>
                                    @foreach($purchaseRequests as $pr)
                                        <option value="{{ $pr->id }}" {{ old('purchase_request_id') == $pr->id ? 'selected' : '' }}>
                                            PR-{{ $pr->id }} - {{ $pr->supplier->name ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('purchase_request_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
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
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="expected_delivery_date" class="form-label">Expected Delivery Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('expected_delivery_date') is-invalid @enderror" id="expected_delivery_date" name="expected_delivery_date" value="{{ old('expected_delivery_date') }}" required min="{{ date('Y-m-d') }}">
                                @error('expected_delivery_date')
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
                            <div class="mb-3">
                                <label for="payment_terms" class="form-label">Payment Terms</label>
                                <input type="text" class="form-control @error('payment_terms') is-invalid @enderror" id="payment_terms" name="payment_terms" value="{{ old('payment_terms') }}" placeholder="e.g., Net 30, COD">
                                @error('payment_terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="shipping_terms" class="form-label">Shipping Terms</label>
                                <input type="text" class="form-control @error('shipping_terms') is-invalid @enderror" id="shipping_terms" name="shipping_terms" value="{{ old('shipping_terms') }}" placeholder="e.g., FOB, CIF">
                                @error('shipping_terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="shipping_method" class="form-label">Shipping Method</label>
                                <input type="text" class="form-control @error('shipping_method') is-invalid @enderror" id="shipping_method" name="shipping_method" value="{{ old('shipping_method') }}" placeholder="e.g., Ground, Express">
                                @error('shipping_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="delivery_address" class="form-label">Delivery Address</label>
                                <textarea class="form-control @error('delivery_address') is-invalid @enderror" id="delivery_address" name="delivery_address" rows="3">{{ old('delivery_address') }}</textarea>
                                @error('delivery_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="billing_address" class="form-label">Billing Address</label>
                                <textarea class="form-control @error('billing_address') is-invalid @enderror" id="billing_address" name="billing_address" rows="3">{{ old('billing_address') }}</textarea>
                                @error('billing_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="discount_amount" class="form-label">Discount Amount</label>
                                <input type="number" class="form-control" id="discount_amount" name="discount_amount" min="0" step="0.01" value="{{ old('discount_amount', '0.00') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="tax_amount" class="form-label">Tax Amount</label>
                                <input type="number" class="form-control" id="tax_amount" name="tax_amount" min="0" step="0.01" value="{{ old('tax_amount', '0.00') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="shipping_cost" class="form-label">Shipping Cost</label>
                                <input type="number" class="form-control" id="shipping_cost" name="shipping_cost" min="0" step="0.01" value="{{ old('shipping_cost', '0.00') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12 text-end">
                            <h5>Total: <span id="grand-total">0.00</span></h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-end">
                            <a href="{{ route('fixed-assets.purchase-orders.index') }}" class="btn btn-secondary">Cancel</a>
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

        // Handle purchase request selection
        const purchaseRequestSelect = document.getElementById('purchase_request_id');
        purchaseRequestSelect.addEventListener('change', function() {
            const purchaseRequestId = this.value;
            if (purchaseRequestId) {
                fetch(`{{ route('fixed-assets.get-purchase-request-details') }}?purchase_request_id=${purchaseRequestId}`)
                    .then(response => response.json())
                    .then(data => {
                        const pr = data.purchaseRequest;
                        
                        // Populate supplier
                        const supplierSelect = document.getElementById('supplier_id');
                        if (pr.supplier_id) {
                            supplierSelect.value = pr.supplier_id;
                        }
                        
                        // Populate branch
                        if (pr.branch_id) {
                            branchSelect.value = pr.branch_id;
                        }
                        
                        // Populate department and then get users with callback to set user
                        if (pr.department_id) {
                            departmentSelect.value = pr.department_id;
                            
                            // Get users for this branch and department, then set the user
                            if (pr.user_id) {
                                getUsers(() => {
                                    userSelect.value = pr.user_id;
                                });
                            } else {
                                getUsers();
                            }
                        }
                        
                        // Populate items
                        if (pr.items) {
                            const itemsTable = document.getElementById('items-table');
                            const tbody = itemsTable.querySelector('tbody');
                            tbody.innerHTML = '';
                            
                            const items = typeof pr.items === 'string' ? JSON.parse(pr.items) : pr.items;
                            
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
                    .catch(error => console.error('Error fetching purchase request details:', error));
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
            const subtotal = itemTotals.reduce((sum, total) => sum + total, 0);
            
            const discountAmount = parseFloat(document.getElementById('discount_amount').value) || 0;
            const taxAmount = parseFloat(document.getElementById('tax_amount').value) || 0;
            const shippingCost = parseFloat(document.getElementById('shipping_cost').value) || 0;
            
            const grandTotal = subtotal - discountAmount + taxAmount + shippingCost;
            document.getElementById('grand-total').textContent = grandTotal.toFixed(2);
        }
        
        // Add event listeners to the additional cost fields
        document.getElementById('discount_amount').addEventListener('input', updateGrandTotal);
        document.getElementById('tax_amount').addEventListener('input', updateGrandTotal);
        document.getElementById('shipping_cost').addEventListener('input', updateGrandTotal);

        // Form validation
        document.getElementById('purchase-order-form').addEventListener('submit', function(e) {
            const itemRows = itemsTable.querySelectorAll('tbody tr');
            if (itemRows.length === 0) {
                e.preventDefault();
                Swal.fire({
                    title: 'Error!',
                    text: 'Please add at least one item to the purchase order.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
</script>
@endpush
