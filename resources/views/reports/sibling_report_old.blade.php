@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="">
            <!-- <img st src="./ucs-white.png" width="15%" height="25%" alt=""> </div> -->
            <div style="margin-top: 90px;">
                <img style="margin-bottom: 60px;" src='{{ asset('icons/ucslogo.png') }}' width="15%" height="25%"  alt="">

                <h3 style="color: rgb(60, 87, 243);margin-bottom: 60px;float: ;position: relative;top: 94px;">Sibling Report</h3>

            </div>


        </div>

        <div>
            <div class="col-md-2 col-sm-12">
                <div class="form-label-group in-border">
                    <select class="form-select" id="branchId" name="branch_id" aria-label="Branch select" required>
                        <option value="">Please select a branch</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">
                                {{ $branch->br_name }}</option>
                        @endforeach
                    </select>
                    {{-- <label for="branch" class="form-label"> <span class="text-danger">*</span></label> --}}
                    <div class="invalid-tooltip">
                        @if ($errors->has('branch_id'))
                            {{ $errors->first('branch_id') }}
                        @else
                            Branch is required!
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="table-responsive">
                <div style="overflow-x:auto;">
                    {{-- <h1 style="font-size:1.5vw; font-weight: 700;">Parent Information</h1> --}}
                    <div class="parent_info">


                        {{-- <table>

                            <tr>
                                <th>Relation</th>
                                <th>Parent Name</th>
                                <th>NIC Number</th>
                                <th>Branch name</th>
                                <th>family_no</th>
                                <th>Student name</th>
                                <th>Passport Number</th>
                                <th>SS. Number</th>
                                <th>Mobile</th>
                            </tr>
                            @foreach ($data as $row)

                                <tr>
                                    <td>{{ $row->relation_name }}</td>
                                    <td>{{ $row->Name }}</td>
                                    <td>{{ $row->CNIC }}</td>
                                    <td>{{ $row->Branch_Name }}</td>
                                    <td>{{ $row->family_no }}</td>
                                    <td>{{ $row->Student_name }}</td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ $row->mobile }}</td>
                                </tr>
                            @endforeach

                        </table> --}}

                        @foreach ($data as $row)
                            <h1 style="font-size:1.5vw; font-weight: 700;" class="mt-3">Parent Information</h1>
                            <table>

                                <tr>
                                    <th>Relation</th>
                                    <th>Parent Name</th>
                                    <th>NIC Number</th>
                                    {{-- <th>Branch name</th> --}}
                                    {{-- <th>family_no</th> --}}
                                    {{-- <th>Student name</th> --}}
                                    <th>Passport Number</th>
                                    <th>SS. Number</th>
                                    <th>Mobile</th>
                                </tr>

                                <tr>
                                    <td>{{ $row->relation_name }}</td>
                                    <td>{{ $row->Name }}</td>
                                    <td>{{ $row->CNIC }}</td>
                                    {{-- <td>{{ $row->Branch_Name }}</td>
                                    <td>{{ $row->family_no }}</td>
                                    <td>{{ $row->Student_name }}</td> --}}
                                    <td></td>
                                    <td></td>
                                    <td>{{ $row->mobile }}</td>
                                </tr>

                            </table>
                            <h1 class="mt-3" style="font-size:1.5vw; font-weight: 700;">Sibling Children Information</h1>

                            <table>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Section</th>
                                    <th>Class</th>
                                    <th>Branch</th>
                                    <th>Concession</th>
                                    <th>Concession Percentage</th>
                                </tr>
                                {{-- @foreach ($data as $row) --}}
                                <tr>
                                    <td>{{ $row->STUDENT_ID }}</td>
                                    <td>{{ $row->Student_name }}</td>
                                    <td>{{ $row->section_name }}</td>
                                    <td>{{ $row->class_name }}</td>
                                    <td>{{ $row->Branch_Name }}</td>
                                    <td>{{ $row->Concsession }}</td>
                                    {{-- <td></td> --}}
                                    <td>{{ $row->concession_percentage }}</td>
                                </tr>
                                {{-- @endforeach --}}
                            </table>

                        @endforeach
                        <br>
                        <form action="">
                    </div>
                    <br>
                    <button style="margin-left: 86%" class="btn btn-primary" id="printPageButton"
                    onclick="window.print()">Generate
                    Report</button>
                    <br>
                </div>
            </div>
        </div>
        <br>
        {{-- <div class="container">
            <div class="table-responsive">
                <div style="overflow-x:auto;">
                    <h1 style="font-size:1.5vw; font-weight: 700">Sibling Children Information</h1>
                    <div class="child_info">

                        <table>
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Section</th>
                                <th>Class</th>
                                <th>Branch</th>
                                <th>Concession</th>
                                <th>Concession Percentage</th>
                            </tr>
                            @foreach ($data as $row)
                                <tr>
                                    <td>{{ $row->STUDENT_ID }}</td>
                                    <td>{{ $row->Student_name }}</td>
                                    <td>{{ $row->section_name }}</td>
                                    <td>{{ $row->class_name }}</td>
                                    <td>{{ $row->Branch_Name }}</td>
                                    <td>{{ $row->Concsession }}</td>
                                    <td></td>
                                </tr>
                                <td>{{ $row->concession_percentage }}</td>
                            @endforeach
                        </table>

                    </div>
                    <br>
                    <form action="">
                        <button style="margin-left: 86%" class="btn btn-primary" id="printPageButton"
                            onclick="window.print()">Generate
                            Report</button>
                    </form>
                    <br>
                </div>
            </div>
        </div> --}}
    </div>
@endsection

@push('header_scripts')
    <style>
        table {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
            border: 1px solid #ddd;
        }

        th,
        td {
            text-align: left;
            padding: 8px;
        }

        img {
            float: left;
            margin-top: 20px;
        }

        tr:nth-child(odd) {
            background-color: #bdbbbb
        }

        h3 {
            letter-spacing: 3px;
            font-weight: 900;
        }

        @media print {
            #printPageButton {
                display: none;
            }
        }
    </style>

@endpush

@push('footer_scripts')
<script>
    // $(document).ready(function() {
        $(document).on('change', '#branchId', function() {
            var Branch = $(this).val();
            console.log('working');
            $.ajax({
                url: "{{ route('sibling-report') }}",
                type: "GET",
                data: {
                    branch_id: Branch,
                },
                success: function(result) {
                    console.log(result);
                    if (result.length !== 0) {
                        $('.parent_info').html(`
                        ${result.map((item) => `
                            <h1 style="font-size:1.5vw; font-weight: 700;" class="mt-3">Parent Information</h1>
                            <table>
                                <tr>
                                    <th>Relation</th>
                                    <th>Parent Name</th>
                                    <th>NIC Number</th>
                                    <th>Passport Number</th>
                                    <th>SS. Number</th>
                                    <th>Mobile</th>
                                </tr>
                                <tr>
                                    <td>${ item.relation_name }</td>
                                    <td>${ item.Name }</td>
                                    <td>${ item.CNIC }</td>
                                    <td></td>
                                    <td></td>
                                    <td>${ item.mobile }</td>
                                </tr>
                            </table>
                            <h1 style="font-size:1.5vw; font-weight: 700;" class="mt-3">Sibling Children Information</h1>
                            <table>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Section</th>
                                    <th>Class</th>
                                    <th>Branch</th>
                                    <th>Concession</th>
                                    <th>Concession Percentage</th>
                                </tr>
                                <tr>
                                    <td>${ item.STUDENT_ID }</td>
                                    <td>${ item.Student_name }</td>
                                    <td>${ item.section_name }</td>
                                    <td>${ item.class_name }</td>
                                    <td>${ item.Branch_Name }</td>
                                    <td>${ item.Concsession }</td>
                                    <td>${ item.concession_percentage }</td>
                                </tr>




                            </table>`)}`)






                        // $('.child_info').html(`<table>
                        //             <tr>
                        //                 <th>Student ID</th>
                        //                 <th>Name</th>
                        //                 <th>Section</th>
                        //                 <th>Class</th>
                        //                 <th>Branch</th>
                        //                 <th>Concession</th>
                        //                 <th>Concession Percentage</th>
                        //             </tr>
                        //             ${result.map((item) => `<tr>
                        //                                 <td>${ item.STUDENT_ID }</td>
                        //                                     <td>${ item.Student_name }</td>
                        //                                     <td>${ item.section_name }</td>
                        //                                     <td>${ item.class_name }</td>
                        //                                     <td>${ item.Branch_Name }</td>
                        //                                     <td>${ item.Concsession }</td>
                        //                                     <td>${ item.concession_percentage }</td></tr>`)}
                        //             </table>`)

                    } else {
                        $('.parent_info').html(`<table>
                                    <tr>
                                        <th>Relation</th>
                                        <th>Parent Name</th>
                                        <th>NIC Number</th>

                                        <th>Passport Number</th>
                                        <th>SS. Number</th>
                                        <th>Mobile</th>
                                    </tr>
                                    <tr><td colspan="7" class="text-center">No data found</td></tr>
                            </table>`)
                        $('.child_info').html(
                            `<table>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Section</th>
                                        <th>Class</th>
                                        <th>Branch</th>
                                        <th>Concession</th>
                                        <th>Concession Percentage</th>
                                    </tr>
                                    <tr><td colspan="7" class="text-center">No data found</td></tr>
                            </table>`
                        )

                    }
                }
            });
        // });
    });
</script>
@endpush
