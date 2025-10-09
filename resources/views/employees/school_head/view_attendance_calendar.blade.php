@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-h-100">
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
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                weekends: true,
                timeZone: 'local',
                themeSystem: 'bootstrap',
                headerToolbar: {
                    // left: 'prev,next today',
                    // center: 'title',
                    // right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                    right: 'prev,next today',
                },
                dateClick: function(info) {
                    var selected_date = info.dateStr;
                    var newdate = selected_date.split("-").reverse().join("-");
                    let route = '{{ route('getStudents', ['branch_class_section_id' => Request::route('branch_class_section_id'), 'subject_id' => Request::route('subject_id')]) }}';
                    location.href=route+'/'+newdate;
                },
            });

            calendar.render();
        });
    </script>
@endpush
