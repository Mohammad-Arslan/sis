@extends('layouts.master')
@section('content')
    @include('components.flash_message')

    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ url('') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admission-query.index') }}">Admission Inquiries</a></li>
        <li class="breadcrumb-item">Admission Inquiry Follow Up</li>
    </x-breadcrumb>
    <div class="row">
        @if (isset($admissionFollowUp))
            @include('admission_queries.edit_followup')
        @else
            @permission('add-admission-follow-up')
                @include('admission_queries.add_followup')
            @endpermission
        @endif
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Follow Up's</h4>

                </div><!-- end card header -->

                <div class="card-body">
                    @if(isset($data[0]))
                    <div class="row col-md-12">
                        <table id="admission-queries-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <tr>
                            <th>Inquiry Number</th><td>{{isset($data[0]['admission_query']['inquiry_number']) ? $data[0]['admission_query']['inquiry_number'] : '-' }}</td>
                            <th>Inquiry Via</th><td>{{isset($data[0]['admission_query']['inquiry_type']['type']) ? $data[0]['admission_query']['inquiry_type']['type'] : '-'}}</td>
                            <th>Source</th><td>{{isset($data[0]['admission_query']['source']['source_name']) ? $data[0]['admission_query']['source']['source_name'] : '-'}}</td>

                        </tr>
                        <tr>
                            <th>Application Date</th><td>{{ isset($data[0]['admission_query']['created_at']) ? \Carbon\Carbon::parse($data[0]['admission_query']['created_at'])->format('Y-m-d'): '-'}}</td>
                            <th>Student Name</th><td>{{isset($data[0]['admission_query']['student_name']) ? $data[0]['admission_query']['student_name'] : '-'}}</td>
                            <th>Student Age</th><td>{{isset($data[0]['admission_query']['student_age']) ? $data[0]['admission_query']['student_age'] : '-'}}</td>
                        </tr>
                        <tr>
                            <th>Parent Name</th><td>{{isset($data[0]['admission_query']['parent_name']) ? $data[0]['admission_query']['parent_name'] :'-'}}</td>
                            <th>Parent Email</th><td>{{isset($data[0]['admission_query']['parent_email']) ? $data[0]['admission_query']['parent_email'] : '-'}}</td>
                            <th>Parent Contact</th><td>{{$data[0]['admission_query']['parent_contact']}}</td>
                        </tr>
                        <tr>
                            <th>City</th><td>{{isset($data[0]['admission_query']['city']['city_name']) ? $data[0]['admission_query']['city']['city_name'] :'-'}}</td>
                            <th>Town</th><td>{{isset($data[0]['admission_query']['town']['town_name']) ? $data[0]['admission_query']['town']['town_name'] :'-'}}</td>
                            <th>Branch</th><td>{{isset($data[0]['admission_query']['branch']['br_name']) ? $data[0]['admission_query']['town']['town_name'] : '-'}}</td>
                        </tr>
                        <tr>
                            <th>Class</th><td>{{isset($data[0]['admission_query']['com_class']['class_name']) ? $data[0]['admission_query']['com_class']['class_name'] : '-'}}</td>
                            <th>Academic Year</th><td>{{isset($data[0]['admission_query']['academic_year']['title']) ? $data[0]['admission_query']['academic_year']['title'] : '-'}}</td>
                        </tr>
                    </table>
                    </div>
                    <hr>
                    @endif
                    <table id="admission-followup-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Lead Type</th>
                                <th>Next Follow Up</th>
                                <th>Remarks</th>
                                <th>Follow Up By</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $row)
                            <tr>
                                <td>{{$row->followup_type->follow_up_type}}</td>
                                <td>{{$row->next_follow_up_date}}</td>
                                <td>{{$row->remarks}}</td>
                                <td>{{$row->user->name}}</td>
                                <td>{{$row->created_at}}</td>
                                <td>
                                    @permission('edit-admission-follow-up')
                                    <a href="{{ route('admissionFollowUp.edit', [$row->id,'admission_query_id'=>request('admission_query_id')]) }}" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
                                        <i class="mdi mdi-lead-pencil"></i></a>
                                    @endpermission
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Lead Type</th>
                                <th>Next Follow Up</th>
                                <th>Remarks</th>
                                <th>Follow Up By</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('header_scripts')
    <style type="text/css">

    </style>
@endpush
@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#admission-followup-table').DataTable({
                paging: false,
                responsive: true,
                info: true,
                /*processing: true,
                serverSide: true,
                "ajax": "items_processing.php",*/
            });

            $(document).on('change', '.filter', function() {
                $('#admission-followup-table').DataTable().ajax.reload(null, false);
            });
        });
    </script>
@endpush
