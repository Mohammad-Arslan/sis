@extends('layouts.master')
@section('content')
@include('components.flash_message')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body form-steps">
                 @include('franchises.add_new_franchisee_form')
            </div>
        </div>
    </div>
</div>

@endsection
@push('header_scripts')
@endpush
@push('footer_scripts')

    <script type="text/javascript">

        var inquirer_id = {{ isset($franchise) ? $franchise->id : 0 }};

        document.querySelectorAll(".form-steps").forEach(function (form) {

            form.querySelectorAll(".previestab").forEach(function (prevButton) {
                prevButton.addEventListener("click", function () {
                    var prevTab = prevButton.getAttribute('data-previous');
                    var totalDone = prevButton.closest("form").querySelectorAll(".custom-nav .done").length;
                    for (var i = totalDone - 1; i < totalDone; i++) {
                        (prevButton.closest("form").querySelectorAll(".custom-nav .done")[i]) ? prevButton.closest("form").querySelectorAll(".custom-nav .done")[i].classList.remove('done'): '';
                    }
                    document.getElementById(prevTab).click();
                });
            });


            // Step number click
            var tabButtons = form.querySelectorAll('button[data-bs-toggle="pill"]');
            tabButtons.forEach(function (button, i) {
                button.setAttribute("data-position", i);
                button.addEventListener("click", function () {
                    var getProgreebar = button.getAttribute("data-progressbar");
                    if (getProgreebar) {
                        var totallength = document.getElementById("custom-progress-bar").querySelectorAll("li").length - 1;
                        var current = i;
                        var percent = (current / totallength) * 100;
                        document.getElementById("custom-progress-bar").querySelector('.progress-bar').style.width = percent + "%";
                    }
                    (form.querySelectorAll(".custom-nav .done").length > 0) ?
                    form.querySelectorAll(".custom-nav .done").forEach(function (doneTab) {
                        doneTab.classList.remove('done');
                    }): '';
                    for (var j = 0; j <= i; j++) {
                        tabButtons[j].classList.contains('active') ? tabButtons[j].classList.remove('done') : tabButtons[j].classList.add('done');
                    }
                });
            });
        });

        $(document).ready(function() {


            $('#franchiseCompanyTable').DataTable({
                searching: false,
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                ordering: true,
                pageLength: 10,
                scrollX: true,
                language: {search: "",
                    processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
                    searchPlaceholder: "Search..."},
                ajax: {
                    url: "{{ route('load-inquiry-led-franchise')}}?inquirer_id="+inquirer_id,
                    type: "POST",
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                },
                columns: [
                {data: 'company_name', name: 'company_name'},
                {data: 'experience', name: 'experience'},
                {data: 'connected', name: 'connected'},
                {data: 'remarks', name: 'remarks'},
                ]
            });

             $('#schoolBuildingTable').DataTable({
                searching: false,
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                ordering: true,
                pageLength: 10,
                scrollX: true,
                language: {search: "",
                    processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
                    searchPlaceholder: "Search..."},
                ajax: {
                    url: "{{ route('load-inquiry-school-building')}}?inquirer_id="+inquirer_id,
                    type: "POST",
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                },
                columns: [
                {data: 'cities.city_name', name: 'cities.city_name','defaultContent': '<i>N/A</i>'},
                {data: 'location', name: 'location'},
                {data: 'area_size', name: 'area_size'},
                {data: 'remarks', name: 'remarks'},
                ]
            });
        });

        $("#personalFormInfo").submit(function (e) {

            e.preventDefault();

            console.log(this.checkValidity());
            if (!this.checkValidity()) {
                return;
            }

            var nextTab = $('.save-personal-info').data('nexttab');
            var url = "{{ route('franchises.store') }}";
            var type = "POST";

            if(inquirer_id > 0){
                console.log(inquirer_id);
                url = "{{ route('inquiry-personal-detail-update')}}?inquirer_id="+inquirer_id;
            }

            $.ajax({
                url:url,
                type: type,
                data: $('#personalFormInfo').serializeArray(),
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                cache: false,
                success: function (data) {
                    inquirer_id = data.id;
                    $('#'+nextTab).trigger('click');
                },
                error: function () {

                },
                beforeSend: function () {

                },
                complete: function () {
                }
            });
        });


        $("#educationalOrgFormInfo").submit(function (e) {

            e.preventDefault();

            console.log(this.checkValidity());
            if (!this.checkValidity()) {
                return;
            }

            var nextTab = $('.save-educational-info').data('nexttab');

            $.ajax({
                url: "{{ route('inquiry-educational-organization')}}?inquirer_id="+inquirer_id,
                type: "POST",
                data: $('#educationalOrgFormInfo').serializeArray(),
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                cache: false,
                success: function (data) {

                    $('#v-pills-educational-organization-tab').addClass('done');
                    $('#'+nextTab).trigger('click');
                },
                error: function () {

                },
                beforeSend: function () {

                },
                complete: function () {
                }
            });
        });


        $("#addFranchiseForm").submit(function (e) {

            e.preventDefault();

            console.log(this.checkValidity());
            if (!this.checkValidity()) {
                return;
            }

            $.ajax({
                url: "{{ route('inquiry-led-franchise')}}?inquirer_id="+inquirer_id,
                type: "POST",
                data: $('#addFranchiseForm').serializeArray(),
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                cache: false,
                success: function (data) {
                },
                error: function () {

                },
                beforeSend: function () {

                },
                complete: function () {
                }
            });
        });

        $(document).on('click', '.led-franchise-info', function(e) {
            e.preventDefault();
            var nextTab = $('.led-franchise-info').data('nexttab');

            console.log(nextTab);

            $('#v-pills-led-franchise-tab').addClass('done');
            $('#'+nextTab).trigger('click');
        });


        $("#addSchoolBuildingForm").submit(function (e) {

            e.preventDefault();

            console.log(this.checkValidity());
            if (!this.checkValidity()) {
                return;
            }

            $.ajax({
                url: "{{ route('inquiry-school-building')}}?inquirer_id="+inquirer_id,
                type: "POST",
                data: $('#addSchoolBuildingForm').serializeArray(),
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                cache: false,
                success: function (data) {
                },
                error: function () {

                },
                beforeSend: function () {

                },
                complete: function () {
                }
            });
        });

        $(document).on('click', '.school_building_btn', function(e) {
            e.preventDefault();
            var nextTab = $('.school_building_btn').data('nexttab');
            $('#v-pills-school-building-tab').addClass('done');
            $('#'+nextTab).trigger('click');
        });


        $("#convertExistingBuilding").submit(function (e) {

            e.preventDefault();

            console.log(this.checkValidity());
            if (!this.checkValidity()) {
                return;
            }

            var nextTab = $('.save-convert-exist-building').data('nexttab');
            $.ajax({
                url: "{{ route('inquiry-existing-building') }}?inquirer_id="+inquirer_id,
                type: "POST",
                data: $('#convertExistingBuilding').serializeArray(),
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                cache: false,
                success: function (data) {
                    $('#v-pills-converting-building-tab').addClass('done');
                    $('#'+nextTab).trigger('click');
                },
                error: function () {

                },
                beforeSend: function () {

                },
                complete: function () {
                }
            });
        });


        $("#locationAndProperty").submit(function (e) {

            e.preventDefault();

            console.log(this.checkValidity());
            if (!this.checkValidity()) {
                return;
            }

            var nextTab = $('.save-location-and-property').data('nexttab');
            $.ajax({
                url: "{{ route('inquiry-property-details') }}?inquirer_id="+inquirer_id,
                type: "POST",
                data: $('#locationAndProperty').serializeArray(),
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                cache: false,
                success: function (data) {

                    $('#v-pills-location-and-property-details-tab').addClass('done');
                    $('#'+nextTab).trigger('click');
                },
                error: function () {

                },
                beforeSend: function () {

                },
                complete: function () {
                }
            });
        });

        $(document).on('hidden.bs.modal','#franchiseCompanyTable', function () {
            $('#franchiseCompanyTable').DataTable().ajax.reload(null, false);
        });

        $(document).on('hidden.bs.modal','#schoolBuildingTable', function () {
            $('#schoolBuildingTable').DataTable().ajax.reload(null, false);
        });
    </script>

@endpush
