@permission('apply-online-leave')
<div class="row">
    <div class="col-xl-2">
        <a href="{{route('leave.application', ($employee->isNotEmpty() ? $employee->first()->id : null))}}" class="btn btn-soft-success waves-effect waves-light"><i class="ri-pencil-line"></i> Apply&nbsp;Online </a>
    </div>
    <div class="col-xl-2">
        <a href="{{route('leave.application.applied-list', ($employee->isNotEmpty() ? $employee->first()->id : null))}}" class="btn btn-soft-success waves-effect waves-light"><i class="ri-list-check-2"></i> Applied List </a>
    </div>
</div><br><hr>
@endpermission
<div class="row">
    <div class="col-xl-12">
        <div class="card card-h-100">
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>
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
