@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Sibling Report</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('sibling-report') }}" class="btn btn-info btn-label btn-sm">
                        <i class="ri-refresh-line label-icon align-middle fs-16 me-2"></i> Reload
                    </a>
                    <button class="btn btn-success btn-label btn-sm" id="exportPdfBtn">
                        <i class="ri-file-pdf-line label-icon align-middle fs-16 me-2"></i> Export PDF
                    </button>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="branch_id" name="branch_id">
                                    <option value="">All Branches</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->br_name }}</option>
                                    @endforeach
                                </select>
                                <label for="branch_id" class="form-label">Branch</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <input id="searchInput" type="text" placeholder="Search by name, CNIC, mobile, student ID..." class="form-control">
                                <label for="searchInput" class="form-label">Search</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table id="sibling-report-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Family ID</th>
                                    <th>Parent Name</th>
                                    <th>Parent CNIC</th>
                                    <th>Parent Mobile</th>
                                    <th>Relation</th>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Class</th>
                                    <th>Section</th>
                                    <th>Branch</th>
                                    <th>Concession</th>
                                    <th>Concession %</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Family ID</th>
                                    <th>Parent Name</th>
                                    <th>Parent CNIC</th>
                                    <th>Parent Mobile</th>
                                    <th>Relation</th>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Class</th>
                                    <th>Section</th>
                                    <th>Branch</th>
                                    <th>Concession</th>
                                    <th>Concession %</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('header_scripts')
    <style>
        .badge {
            border-radius: 20px;
            padding: 0.5em 1em;
            font-weight: 500;
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            cursor: default;
        }
        
        .badge-success {
            background: linear-gradient(135deg, #28a745, #20c997) !important;
            color: white !important;
        }
        
        .badge-warning {
            background: linear-gradient(135deg, #ffc107, #fd7e14) !important;
            color: white !important;
        }
        
        .badge-danger {
            background: linear-gradient(135deg, #dc3545, #e83e8c) !important;
            color: white !important;
        }
        
        .badge.text-white {
            color: white !important;
        }
        
        /* Prevent text selection on badges */
        .badge * {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }
    </style>
@endpush

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#sibling-report-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: true,
                ordering: true,
                searching: false, // Disable built-in search
                pageLength: 25,
                scrollX: true,
                language: {
                    processing: "<div class='text-center'><div class='spinner-border text-primary' role='status'><span class='visually-hidden'>Loading...</span></div></div>",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "No entries found",
                    infoFiltered: "(filtered from _MAX_ total entries)",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                ajax: {
                    url: "{{ route('sibling-report') }}",
                    data: function(d) {
                        d.branch_id = $('#branch_id').val();
                        d.search_text = $('#searchInput').val();
                    }
                },
                columns: [
                    {
                        data: 'family_id',
                        name: 'family_id',
                        title: 'Family ID'
                    },
                    {
                        data: 'parent_name',
                        name: 'parent_name',
                        title: 'Parent Name'
                    },
                    {
                        data: 'parent_cnic',
                        name: 'parent_cnic',
                        title: 'Parent CNIC'
                    },
                    {
                        data: 'parent_mobile',
                        name: 'parent_mobile',
                        title: 'Parent Mobile'
                    },
                    {
                        data: 'relation',
                        name: 'relation',
                        title: 'Relation'
                    },
                    {
                        data: 'student_id',
                        name: 'student_id',
                        title: 'Student ID'
                    },
                    {
                        data: 'student_name',
                        name: 'student_name',
                        title: 'Student Name'
                    },
                    {
                        data: 'class_name',
                        name: 'class_name',
                        title: 'Class'
                    },
                    {
                        data: 'section_name',
                        name: 'section_name',
                        title: 'Section'
                    },
                    {
                        data: 'branch_name',
                        name: 'branch_name',
                        title: 'Branch'
                    },
                    {
                        data: 'concession',
                        name: 'concession',
                        title: 'Concession',
                        render: function(data, type, row) {
                            if (data === 'NO' || data === null || data === '') {
                                return '<span class="badge badge-danger" style="user-select: none; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; cursor: default;">No</span>';
                            } else {
                                return '<span class="badge badge-success" style="user-select: none; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; cursor: default;">' + data + '</span>';
                            }
                        }
                    },
                    {
                        data: 'concession_percentage',
                        name: 'concession_percentage',
                        title: 'Concession %',
                        render: function(data, type, row) {
                            if (data === '0' || data === null || data === '') {
                                return '<span class="badge badge-warning" style="user-select: none; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; cursor: default;">0%</span>';
                            } else {
                                return '<span class="badge badge-success" style="user-select: none; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; cursor: default;">' + data + '%</span>';
                            }
                        }
                    }
                ],
                order: [[0, 'asc']],
                dom: '<"row"<"col-sm-12 col-md-6"l>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                initComplete: function() {
                    // Add custom styling after table initialization
                    $('.dataTables_wrapper').addClass('mt-3');
                    
                    // Ensure badges are not selectable
                    $('.badge').css({
                        'user-select': 'none',
                        '-webkit-user-select': 'none',
                        '-moz-user-select': 'none',
                        '-ms-user-select': 'none',
                        'cursor': 'default'
                    });
                },
                drawCallback: function() {
                    // Reapply badge styling after each draw
                    $('.badge').css({
                        'user-select': 'none',
                        '-webkit-user-select': 'none',
                        '-moz-user-select': 'none',
                        '-ms-user-select': 'none',
                        'cursor': 'default'
                    });
                }
            });

            // Handle branch filter change
            $(document).on('change', '#branch_id', function() {
                table.ajax.reload();
            });

            // Handle search input
            $(document).on('keyup', '#searchInput', function() {
                clearTimeout(this.delay);
                this.delay = setTimeout(function() {
                    table.ajax.reload();
                }.bind(this), 500);
            });

            // Export PDF functionality
            $('#exportPdfBtn').on('click', function() {
                var branchId = $('#branch_id').val();
                var searchText = $('#searchInput').val();
                
                // Create export URL with current filters
                var exportUrl = "{{ route('sibling-report-export') }}";
                var params = new URLSearchParams();
                
                if (branchId) {
                    params.append('branch_id', branchId);
                }
                if (searchText) {
                    params.append('search_text', searchText);
                }
                
                if (params.toString()) {
                    exportUrl += '?' + params.toString();
                }
                
                // Show loading state
                var $btn = $(this);
                var originalText = $btn.html();
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Generating PDF...');
                
                // Use window.location for direct download
                window.location.href = exportUrl;
                
                // Restore button after a delay
                setTimeout(function() {
                    $btn.prop('disabled', false).html(originalText);
                }, 3000);
            });
        });
    </script>
@endpush