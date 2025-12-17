<table id="fee-structure-table" class="table mb-0 align-middle table-bordered table-striped table-nowrap"
    style="width:100%">
    <thead>
        <tr>
            <th>Proposed School Name</th>
            <th>Region</th>
            <th>City</th>
            <th>Academic Year</th>
            <th>School Type</th>
            <th>Proposed Campus Area</th>
            <th>Final Tution Fee Charges</th>
            <th>Tution Fee Charges (Rounded )</th>
            <th>Fee Option Details</th>
            <th>Approval Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($fee_structure_records as $record)
            {{-- {{ dd($record->fee_structure_details)}} --}}
            {{-- @dd($fee_structure_records) --}}
            <tr>
                <td>{{ $record->school_name }}</td>
                <td>{{ $record->state->state_name }}</td>
                <td>{{ $record->city->city_name }}</td>
                <td>{{ $record->academic_years->title }}</td>
                <td>{{ $record->class_group->name }}</td>
                <td>{{ $record->campus_area }}</td>
                <td>
                    @php
                        $approvedFeeDetail = $record->new_fee_structure_details->where('fee_status_by_dd', 'Approved')->first();
                    @endphp

                    @if ($approvedFeeDetail)
                        {{ $approvedFeeDetail->final_fee_charges }}
                    @else
                       Not Approved
                    @endif
                </td>

                <td>
                    @php
                        $approvedFeeDetail = $record->new_fee_structure_details->where('fee_status_by_dd', 'Approved')->first();
                    @endphp

                    @if ($approvedFeeDetail)
                        {{ $approvedFeeDetail->round_final_fee_charges ?? 'N/A' }}
                    @else
                        Not Approved
                    @endif
                </td>

                <td>
                    <a href="#" class="btn btn-sm btn-info" data-toggle="modal" id="view_detail" data-id="record_Id"
                        data-target="#viewModal{{ $record->id }}">
                        <i class="fas fa-eye"></i> View Details
                    </a>
                </td>
                <td>
                    @php
                        $approvedFeeDetail = $record->new_fee_structure_details->where('fee_status_by_dd', 'Approved')->first();
                    @endphp

                    @if ($approvedFeeDetail)
                        {{ $approvedFeeDetail->status_approval_date ?? 'N/A' }}
                    @else
                        Not Approved
                    @endif
                </td>

                <td>
                    @permission('fee-structure-edit-index')
                    <a href="{{ route('fee_structure.index_edit', $record->id) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit"></i>
                    </a>
                    @endpermission
                    @permission('delete-fee-structure')
                    <form action="{{ route('new-school-fee-structure.destroy', $record->id) }}" method="post" class="d-inline"
                        onsubmit="return confirm('Are you sure you want to delete this record?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @endpermission
                </td>
            </tr>


            <!-- View Modal -->
            <div class="modal fade" id="viewModal{{ $record->id }}" tabindex="1" role="dialog"
                aria-labelledby="viewModalLabel{{ $record->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewModalLabel{{ $record->id }}">Fee
                                Option Details
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <ul class="custom-list-group">
                                <li class="header">
                                    <span class="label">BSS School Name</span>
                                    <span class="label">BSS Fee Charges</span>
                                    <span class="label">Final Fee Charges</span>
                                    <span class="label">Final Fee (Rounded Value)</span>
                                    <span class="label">Status</span>
                                    <span class="label">Approval Date</span>
                                </li>

                                @foreach ($record->new_fee_structure_details as $recordFeeStructure)
                                    <li class="values">
                                        <span class="value">
                                            {{ $recordFeeStructure->nearest_bss_school ?? 'No Record' }}
                                        </span>
                                        <span class="value">
                                            {{ $recordFeeStructure->fee_charges ?? 'No Record' }}
                                        </span>
                                        <span class="value">

                                            {{ $recordFeeStructure->final_fee_charges ?? 'No Record' }}

                                        </span>
                                        <span class="value">

                                            {{ $recordFeeStructure->round_final_fee_charges ?? 'No Record' }}

                                        </span>
                                        <span class="value">

                                            {{ $recordFeeStructure->fee_status_by_dd ?? 'No Record' }}

                                        </span>
                                        <span class="value">

                                            {{ $recordFeeStructure->status_approval_date  ?? 'No Record' }}

                                        </span>
                                    </li>
                                @endforeach

                            </ul>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </tbody>
</table>
