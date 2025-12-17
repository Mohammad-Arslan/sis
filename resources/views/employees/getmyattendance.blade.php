@extends('layouts.master')

@section('content')
    @include('components.flash_message')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Attendance </h4>
                    <div class="flex-shrink-0">

                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('header_scripts')
@endpush

@push('footer_scripts')
<script type="text/javascript">

    $(document).ready(function() {
        var calendarEl = document.getElementById('calendar');
       // var startDate = calendarEl.FullCalendar('getView').intervalStart.format('DD/MM/YYYY');
       // var endDate = calendarEl.FullCalendar('getView').intervalEnd.format('DD/MM/YYYY');

        //console.log('Start Date => '+startDate);
       //console.log('End Date => '+endDate);
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            weekends: true,
            timeZone: 'local',
            themeSystem: 'bootstrap',
            headerToolbar: {
                right: 'prev,next today',
            },
            events: <?php echo json_encode(($employee->isNotEmpty() ? $employee->first()->employeeAttendanceEvents() : [])); ?>,
        });

        calendar.render();
    });
</script>
@endpush
