@extends('layouts.master')
<link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.7.2/main.min.css' rel='stylesheet' />
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.7.2/main.min.js'></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    /* Custom styles for calendar icons */
    .event-icon {
        font-size: 16px;
        cursor: pointer;
        margin-left: 5px;
    }

    .edit-icon {
        color: #000000;
        /* Blue color for edit icon */
    }

    .delete-icon {
        color: #dc3545;
        /* Red color for delete icon */
    }

    .view-icon {
        color: #23e450;
        /* Green color for view icon */
    }

    /* Calendar event styling */
    .fc-event {
        cursor: pointer;
        padding: 2px 4px;
        margin: 1px 0;
        border-radius: 3px;
    }

    .fc-event-title {
        font-size: 12px;
        font-weight: bold;
    }

    /* No data message styling */
    .no-data-message {
        text-align: center;
        padding: 20px;
        color: #666;
        font-style: italic;
    }
</style>

@section('content')
    <div class="container">
        <h2>Timetable</h2>
        

        
        <div class="mb-3">
            @if(Auth::user()->hasRole('subject_coordinator') || Auth::user()->hasRole('school-coordinator') || Auth::user()->hasRole('super_admin'))
                <!-- Create Timetable Button -->
                <a href="{{ route('timetables.create') }}" class="btn btn-primary">Schedule Timetable</a>
            @endif
        </div>

        @if($message != "")
            <div class="alert alert-info">
                {{ $message }}
            </div>
        @endif

        @if(empty($events))
            <div class="no-data-message">
                <h4>No Timetable Available</h4>
                <p>There are no timetable entries to display. Please create some timetable entries first.</p>
                <p><a href="{{ route('timetables.create') }}" class="btn btn-success">Create First Timetable Entry</a></p>
            </div>
        @else
            <div id="calendar"></div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Only initialize calendar if there are events
            @if(!empty($events))
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: {!! json_encode($events) !!},
                eventDisplay: 'block',
                eventTimeFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    meridiem: 'short'
                },
                themeSystem: 'bootstrap',
                height: 'auto',
                eventDidMount: function(info) {
                    var eventElement = info.el;

                    // Check if the user has the coordinator role
                    var isCoordinator = {{ Auth::user()->hasRole('subject_coordinator') || Auth::user()->hasRole('school-coordinator') || Auth::user()->hasRole('super_admin') ? 'true' : 'false' }};

                    // Check if the user has the teacher role
                    var isTeacher = {{ Auth::user()->hasRole('teacher') || Auth::user()->hasRole('subject_coordinator') || Auth::user()->hasRole('school-coordinator') || Auth::user()->hasRole('super_admin') ? 'true' : 'false' }};

                    if (isCoordinator) {
                        // Create edit icon
                        var editIcon = document.createElement('span');
                        editIcon.innerHTML = '<i class="fas fa-edit"></i>';
                        editIcon.className = 'event-icon edit-icon';
                        editIcon.title = 'Edit Timetable';
                        editIcon.addEventListener('click', function(e) {
                            e.stopPropagation();
                            window.location.href = '/timetables/' + info.event.id + '/edit';
                        });
                        eventElement.appendChild(editIcon);

                        // Create delete icon
                        var deleteIcon = document.createElement('span');
                        deleteIcon.innerHTML = '<i class="fas fa-trash"></i>';
                        deleteIcon.className = 'event-icon delete-icon';
                        deleteIcon.title = 'Delete Timetable';
                        deleteIcon.addEventListener('click', function(e) {
                            e.stopPropagation();
                            Swal.fire({
                                title: "Are you sure?",
                                text: "You won't be able to revert this!",
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#3085d6",
                                cancelButtonColor: "#d33",
                                confirmButtonText: "Yes, delete it!"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Send AJAX request to delete the event
                                    var eventId = info.event.id;
                                    $.ajax({
                                        url: '/timetables/' + eventId,
                                        type: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        success: function(response) {
                                            // If deletion is successful, remove the event from the calendar
                                            info.event.remove();
                                            Swal.fire({
                                                title: "Deleted!",
                                                text: "Your timetable entry has been deleted.",
                                                icon: "success"
                                            });
                                        },
                                        error: function(xhr, status, error) {
                                            console.error(xhr.responseText);
                                            Swal.fire({
                                                title: "Error!",
                                                text: "An error occurred while deleting the timetable entry.",
                                                icon: "error"
                                            });
                                        }
                                    });
                                }
                            });
                        });
                        eventElement.appendChild(deleteIcon);
                    }

                    if (isTeacher) {
                        // Create view icon
                        var viewIcon = document.createElement('span');
                        viewIcon.innerHTML = '<i class="fas fa-eye"></i>';
                        viewIcon.className = 'event-icon view-icon';
                        viewIcon.title = 'View Details';
                        viewIcon.addEventListener('click', function(e) {
                            e.stopPropagation();
                            // Display event details using SweetAlert
                            Swal.fire({
                                title: 'Timetable Details',
                                html: '<div style="text-align: left;">' + 
                                      '<p><strong>Teacher:</strong> ' + info.event.title.split('|')[0].replace('Teacher: ', '') + '</p>' +
                                      '<p><strong>Class Nature:</strong> ' + info.event.title.split('|')[1].replace(' Class Nature: ', '') + '</p>' +
                                      '<p><strong>Class:</strong> ' + info.event.title.split('|')[2].replace(' Class: ', '') + '</p>' +
                                      '<p><strong>Section:</strong> ' + info.event.title.split('|')[3].replace(' Section: ', '') + '</p>' +
                                      '<p><strong>Subject:</strong> ' + info.event.title.split('|')[4].replace(' Subject: ', '') + '</p>' +
                                      '<p><strong>Time:</strong> ' + info.event.title.split('|')[5].replace(' Time: ', '') + '</p>' +
                                      '</div>',
                                icon: 'info',
                                confirmButtonText: 'OK'
                            });
                        });
                        eventElement.appendChild(viewIcon);
                    }
                },
                eventClick: function(info) {
                    // Show event details when clicking on the event (for all users)
                    Swal.fire({
                        title: 'Timetable Details',
                        html: '<div style="text-align: left;">' + 
                              '<p><strong>Teacher:</strong> ' + info.event.title.split('|')[0].replace('Teacher: ', '') + '</p>' +
                              '<p><strong>Class Nature:</strong> ' + info.event.title.split('|')[1].replace(' Class Nature: ', '') + '</p>' +
                              '<p><strong>Class:</strong> ' + info.event.title.split('|')[2].replace(' Class: ', '') + '</p>' +
                              '<p><strong>Section:</strong> ' + info.event.title.split('|')[3].replace(' Section: ', '') + '</p>' +
                              '<p><strong>Subject:</strong> ' + info.event.title.split('|')[4].replace(' Subject: ', '') + '</p>' +
                              '<p><strong>Time:</strong> ' + info.event.title.split('|')[5].replace(' Time: ', '') + '</p>' +
                              '</div>',
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                }
            });
            calendar.render();
            @endif
        });
    </script>
@endsection
