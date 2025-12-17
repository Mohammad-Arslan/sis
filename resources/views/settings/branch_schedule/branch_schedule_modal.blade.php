<div id="branchScheduleModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Branch Timings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mt-5">
                    <div class="col-xxl-12">
                        <div class="card mt-xxl-n5">

                            <div class="card-body p-4">
                                <div class="live-preview">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-nowrap align-middle mb-0">
                                                        <tr>
                                                            <th scope="col">Term</th>
                                                            <th scope="col">Mon</th>
                                                            <th scope="col">Tue</th>
                                                            <th scope="col">Wed</th>
                                                            <th scope="col">Thurs</th>
                                                            <th scope="col">Fri</th>
                                                        </tr>
                                                        {{-- <tr>
                                                            <td rowspan="2">Test</td>
                                                            <td >One</td>
                                                            <td >One</td>
                                                            <td >One</td>
                                                            <td >One</td>
                                                            <td >One</td>
                                                        </tr>
                                                        <tr>
                                                            <td >One</td>
                                                            <td >One</td>
                                                            <td >One</td>
                                                            <td >One</td>
                                                            <td >One</td>
                                                        </tr> --}}
                                                        <tbody>
                                                            @php
                                                                $term_name = $schedules[0]['term_name'];
                                                                $term = $schedules[0]['term'];
                                                                $order = $schedules[0]['order'];
                                                                $staff = $schedules[0]['staff'];
                                                                //$type_name = $schedules[0]['type_name'];
                                                            @endphp
                                                            <tr>
                                                                <td class="fw-bold bg-success" colspan="6">{{ $schedules[0]['type_name'] .": ". $schedules[0]['description']}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-bold text-info" rowspan="{{ get_branch_schedule_term_count($staff,$schedules[0]['term_name']) }}">{{ $schedules[0]['term_name']}}</td>
                                                            @foreach ($schedules as $schedule)
                                                                @if ($schedule['term'] != $term && $schedule['term_name'] != $term_name)
                                                                    @php
                                                                        $term_name = $schedule['term_name'];
                                                                        $term = $schedule['term'];
                                                                        $order = $schedule['order'];
                                                                        $type_name = $schedule['type_name'];
                                                                    @endphp
                                                                    {{-- </tr> --}}

                                                                        @if ($schedule['staff'] != $staff)
                                                                            <tr>
                                                                                <td class="fw-bold bg-success" colspan="6">{{ $schedule['type_name'] .": ". $schedule['description']}}</td>
                                                                            </tr>
                                                                        @php
                                                                            $staff = $schedule['staff'];
                                                                        @endphp
                                                                        @endif
                                                                        <tr>
                                                                            <td class="fw-bold text-info" rowspan="{{ get_branch_schedule_term_count($staff,$schedule['term_name']) }}">{{ $schedule['term_name']}} </td>
                                                                            <td class="text-center text-dark">{{ date("g:i a", strtotime($schedule['start_time'])) }} to {{ date("g:i a", strtotime($schedule['end_time'])) }} </td>
                                                                @else
                                                                    <td class="text-center text-dark">{{ date("g:i a", strtotime($schedule['start_time'])) }} to {{ date("g:i a", strtotime($schedule['end_time'])) }}</td>
                                                                @endif
                                                                @if ($schedule['day'] == 'Friday')
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                </div>
                            </div>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">


</script>
