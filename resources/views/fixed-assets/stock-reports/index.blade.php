@extends('layouts.master')
@section('title', 'Asset Stock Report')

@php
function getStatusColorClass($status) {
    switch(strtolower($status)) {
        case 'active': return 'success';
        case 'inactive': return 'warning';
        case 'maintenance': return 'info';
        case 'retired': return 'secondary';
        case 'lost': return 'danger';
        case 'stolen': return 'danger';
        default: return 'primary';
    }
}
@endphp

@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Asset Stock Report</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Fixed Assets</a></li>
                            <li class="breadcrumb-item active">Stock Report</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-xl-4 col-md-6">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Total Assets</p>
                                <h4 class="mb-2">{{ $totalAssets }}</h4>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-primary rounded-3">
                                    <i class="ri-shopping-cart-2-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Total Value</p>
                                <h4 class="mb-2">{{ number_format($totalValue, 2) }}</h4>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-success rounded-3">
                                    <i class="ri-money-dollar-circle-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-12">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Asset Status Counts</h5>
                        <div class="row">
                            @foreach($statusCounts as $status => $count)
                                <div class="col-6 col-xl-4 mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-xs me-2">
                                                <span class="avatar-title rounded-circle bg-{{ getStatusColorClass($status) }} font-size-10">
                                                    {{ substr($status, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="text-muted mb-0 small">{{ $status }}</p>
                                            <h5 class="mb-0">{{ $count }}</h5>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Summary Cards -->

        <!-- Filters -->
        <div class="row mt-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Filter Assets</h4>
                        
                        <form action="{{ route('fixed-assets.stock-reports.index') }}" method="GET">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select class="form-select" id="category_id" name="category_id">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-3">
                                    <label for="branch_id" class="form-label">Branch</label>
                                    <select class="form-select" id="branch_id" name="branch_id">
                                        <option value="">All Branches</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->br_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <label for="supplier_id" class="form-label">Supplier</label>
                                    <select class="form-select" id="supplier_id" name="supplier_id">
                                        <option value="">All Suppliers</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="asset_status" name="status">
                                        <option value="">All Statuses</option>
                                        @foreach($statusOptions as $value => $label)
                                            <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <label for="condition" class="form-label">Condition</label>
                                    <select class="form-select" id="condition" name="condition">
                                        <option value="">All Conditions</option>
                                        @foreach($conditionOptions as $value => $label)
                                            <option value="{{ $value }}" {{ request('condition') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-12 text-end mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-filter-2-line me-1"></i> Apply Filters
                                    </button>
                                    <a href="{{ route('fixed-assets.stock-reports.index') }}" class="btn btn-secondary">
                                        <i class="ri-refresh-line me-1"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 1 -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Assets by Category</h4>
                        <div>
                            <canvas id="categoryChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Assets by Branch</h4>
                        <div>
                            <canvas id="branchChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 2 -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Assets by Status</h4>
                        <div>
                            <canvas id="statusChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Assets by Condition</h4>
                        <div>
                            <canvas id="conditionChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 3 -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Asset Value by Category</h4>
                        <div>
                            <canvas id="valueCategoryChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Asset Value by Branch</h4>
                        <div>
                            <canvas id="valueBranchChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Asset Listing -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Asset Inventory</h4>
                        
                        <div class="table-responsive">
                            <table class="table table-centered table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Asset Tag</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Branch</th>
                                        <th>Supplier</th>
                                        <th>Status</th>
                                        <th>Condition</th>
                                        <th>Purchase Date</th>
                                        <th>Purchase Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($assets as $asset)
                                    <tr>
                                        <td>{{ $asset->asset_tag }}</td>
                                        <td>{{ $asset->name }}</td>
                                        <td>{{ $asset->category->name ?? 'N/A' }}</td>
                                        <td>{{ $asset->currentBranch->br_name ?? 'N/A' }}</td>
                                        <td>{{ $asset->supplier->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge {{ $asset->getStatusBadgeClassAttribute() }}">
                                                {{ ucfirst($asset->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $asset->getConditionBadgeClassAttribute() }}">
                                                {{ ucfirst($asset->condition) }}
                                            </span>
                                        </td>
                                        <td>{{ $asset->purchase_date ? date('d M Y', strtotime($asset->purchase_date)) : 'N/A' }}</td>
                                        <td>{{ number_format($asset->purchase_price, 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No assets found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted small">
                                Showing {{ $assets->firstItem() ?? 0 }} to {{ $assets->lastItem() ?? 0 }} of {{ $assets->total() }} assets
                            </div>
                            <div>
                                {{ $assets->withQueryString()->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer_scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart color palette
        const colors = [
            'rgba(75, 192, 192, 0.7)',
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 206, 86, 0.7)',
            'rgba(255, 99, 132, 0.7)',
            'rgba(153, 102, 255, 0.7)',
            'rgba(255, 159, 64, 0.7)',
            'rgba(199, 199, 199, 0.7)',
            'rgba(83, 102, 255, 0.7)',
            'rgba(40, 159, 64, 0.7)',
            'rgba(210, 199, 199, 0.7)',
        ];
        
        // Chart.js global defaults
        Chart.defaults.font.family = "'Poppins', sans-serif";
        Chart.defaults.color = '#555';
        
        // Category Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartData['categoryLabels']) !!},
                datasets: [{
                    label: 'Number of Assets',
                    data: {!! json_encode($chartData['categoryData']) !!},
                    backgroundColor: colors,
                    borderColor: colors.map(color => color.replace('0.7', '1')),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Assets: ${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
        
        // Branch Chart
        const branchCtx = document.getElementById('branchChart').getContext('2d');
        new Chart(branchCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartData['branchLabels']) !!},
                datasets: [{
                    label: 'Number of Assets',
                    data: {!! json_encode($chartData['branchData']) !!},
                    backgroundColor: colors,
                    borderColor: colors.map(color => color.replace('0.7', '1')),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
        
        // Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($chartData['statusLabels']) !!},
                datasets: [{
                    data: {!! json_encode($chartData['statusData']) !!},
                    backgroundColor: colors,
                    borderColor: colors.map(color => color.replace('0.7', '1')),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
        
        // Condition Chart
        const conditionCtx = document.getElementById('conditionChart').getContext('2d');
        new Chart(conditionCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($chartData['conditionLabels']) !!},
                datasets: [{
                    data: {!! json_encode($chartData['conditionData']) !!},
                    backgroundColor: colors,
                    borderColor: colors.map(color => color.replace('0.7', '1')),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
        
        // Value by Category Chart
        const valueCategoryCtx = document.getElementById('valueCategoryChart').getContext('2d');
        new Chart(valueCategoryCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartData['valueCategoryLabels']) !!},
                datasets: [{
                    label: 'Total Value',
                    data: {!! json_encode($chartData['valueCategoryData']) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Value: ${new Intl.NumberFormat('en-US', {
                                    style: 'currency',
                                    currency: 'PKR'
                                }).format(context.raw)}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Value by Branch Chart
        const valueBranchCtx = document.getElementById('valueBranchChart').getContext('2d');
        new Chart(valueBranchCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartData['valueBranchLabels']) !!},
                datasets: [{
                    label: 'Total Value',
                    data: {!! json_encode($chartData['valueBranchData']) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Value: ${new Intl.NumberFormat('en-US', {
                                    style: 'currency',
                                    currency: 'PKR'
                                }).format(context.raw)}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endpush