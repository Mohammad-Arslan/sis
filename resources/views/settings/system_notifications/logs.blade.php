@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Announcement Logs</h4>
                </div>
                <div class="card-body">
                    <table class="table table-nowrap ">
                        <thead>
                            <tr>
                                <th scope="col">Announcement Type</th>
                                <th scope="col">Status</th>
                                <th scope="col">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @if (isset($notifications)) --}}
                            @foreach ($notifications as $notification)
                                <tr>
                                    <td>{{ $notification->notification_type }}</td>
                                    <td>{{ strtoupper($notification->status) }}</td>
                                    <td>{{ $notification->created_at }}</td>
                                </tr>
                            @endforeach
                            {{-- @endif --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('header_scripts')
@endpush

@push('footer_scripts')
    <script type="text/javascript"></script>
@endpush
