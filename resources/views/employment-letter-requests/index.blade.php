@extends('layouts.master')

@section('title', 'Employment Letter Requests')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Employment Letter Requests</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Employment Letter Requests</li>
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
                        <h4 class="card-title mb-0">My Employment Letter Requests</h4>
                        <a href="{{ route('employment-letter-requests.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> New Request
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($requests->count() > 0)
                        <div class="table-responsive">
                            <table id="employment-letter-requests-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Request Type</th>
                                        <th>Purpose</th>
                                        <th>Status</th>
                                        <th>Requested Date</th>
                                        <th>Approved Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $request)
                                        <tr>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ ucfirst(str_replace('_', ' ', $request->request_type)) }}
                                                </span>
                                            </td>
                                            <td>{{ strlen($request->purpose) > 50 ? substr($request->purpose, 0, 50) . '...' : $request->purpose }}</td>
                                            <td>{!! $request->status_badge !!}</td>
                                            <td>{{ $request->created_at->format('M d, Y') }}</td>
                                            <td>
                                                @if($request->approved_at)
                                                    {{ $request->approved_at->format('M d, Y') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('employment-letter-requests.show', $request) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    @if($request->status === 'pending')
                                                        <a href="{{ route('employment-letter-requests.edit', $request) }}" 
                                                           class="btn btn-sm btn-outline-warning"
                                                           title="Edit Request">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        
                                                        <a href="{{ route('employment-letter-requests.destroy', $request) }}" 
                                                           class="btn btn-sm btn-outline-danger delete-record"
                                                           data-table="employment-letter-requests-table"
                                                           data-isajax="false"
                                                           title="Delete Request">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    @endif
                                                    
                                                    @if($request->status === 'approved' && $request->letter_file_path)
                                                        <a href="{{ route('employment-letter-requests.download', $request) }}" 
                                                           class="btn btn-sm btn-outline-success"
                                                           title="Download Letter">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            {{ $requests->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No employment letter requests found</h5>
                            <p class="text-muted">You haven't submitted any employment letter requests yet.</p>
                            <a href="{{ route('employment-letter-requests.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create Your First Request
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Override the default delete success behavior for our table
    $(document).on('click', '.delete-record[data-table="employment-letter-requests-table"]', function(e) {
        e.preventDefault();
        
        var url = $(this).attr('href');
        var this_var = $(this);
        
        Swal.fire({
            html: '<div class="mt-3">' +
                '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                '<div class="pt-2 mx-5 mt-4 fs-15">' +
                '<h4>Are you sure?</h4>' +
                '<p class="mx-4 mb-0 text-muted">Are you Sure You want to Delete this Record ?</p>' +
                '</div>' +
                '</div>',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-primary w-xs me-2 mb-1',
            confirmButtonText: 'Yes, Delete It!',
            cancelButtonClass: 'btn btn-danger w-xs mb-1',
            buttonsStyling: false,
            showCloseButton: true
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: "DELETE",
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    cache: false,
                    success: function(data) {
                        // Remove the table row
                        this_var.closest('tr').remove();
                        
                        // Check if table is empty and show message
                        if ($('#employment-letter-requests-table tbody tr').length === 0) {
                            $('#employment-letter-requests-table').closest('.table-responsive').html(
                                '<div class="text-center py-5">' +
                                '<i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>' +
                                '<h5 class="text-muted">No employment letter requests found</h5>' +
                                '<p class="text-muted">You haven\'t made any employment letter requests yet.</p>' +
                                '<a href="{{ route("employment-letter-requests.create") }}" class="btn btn-primary">' +
                                '<i class="fas fa-plus"></i> Make Your First Request</a>' +
                                '</div>'
                            );
                        }
                        
                        // Show success message
                        Swal.fire({
                            html: '<div class="mt-3">' +
                                '<lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#0ab39c,secondary:#405189" style="width:120px;height:120px"></lord-icon>' +
                                '<div class="mt-4 pt-2 fs-15">' +
                                '<h4>Success !</h4>' +
                                '<p class="text-muted mx-4 mb-0">' + (data.message || 'Record has been successfully deleted.') + '</p>' +
                                '</div></div>',
                            showCancelButton: !0,
                            showConfirmButton: !1,
                            cancelButtonClass: "btn btn-primary w-xs mb-1",
                            cancelButtonText: "Okay",
                            buttonsStyling: !1,
                            showCloseButton: !0
                        });
                    },
                    error: function(xhr) {
                        var message = 'An error occurred while deleting the record.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            html: '<div class="mt-3">' +
                                '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                                '<div class="mt-4 pt-2 fs-15">' +
                                '<h4>Error !</h4>' +
                                '<p class="text-muted mx-4 mb-0">' + message + '</p>' +
                                '</div></div>',
                            showCancelButton: !0,
                            showConfirmButton: !1,
                            cancelButtonClass: "btn btn-primary w-xs mb-1",
                            cancelButtonText: "Okay",
                            buttonsStyling: !1,
                            showCloseButton: !0
                        });
                    }
                });
            }
        });
    });
});
</script>
@endsection
