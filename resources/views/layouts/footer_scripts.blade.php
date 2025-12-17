<!-- JAVASCRIPT -->
<script src="{{ asset('theme/dist/default/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('theme/dist/default/assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('theme/dist/default/assets/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('theme/dist/default/assets/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('theme/dist/default/assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="{{ asset('theme/dist/default/assets/js/plugins.js') }}"></script>

<!-- aos js -->
<script src="{{ asset('theme/dist/default/assets/libs/aos/aos.js') }}"></script>
<script src="{{ asset('theme/dist/default/assets/libs/prismjs/prism.js') }}"></script>
<script src="{{ asset('theme/dist/default/assets/js/pages/form-validation.init.js') }}"></script>
<!-- animation init -->
<script src="{{ asset('theme/dist/default/assets/js/pages/animation-aos.init.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.js">
</script>
<script src="{{ asset('theme/dist/default/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
{{-- <script src="{{asset('theme/dist/default/assets/js/pages/sweetalerts.init.js')}}"></script> --}}

<script src="{{ asset('theme/dist/default/assets/libs/fullcalendar/main.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- App js -->
<script src="{{ asset('theme/dist/default/assets/js/app.js') }}"></script>

<!-- Global CRUD Operations -->
<script src="{{ asset('js/crud-operations.js') }}"></script>

<!-- form masks init -->
<script src="{{ asset('theme/dist/default/assets/libs/cleave.js/cleave.min.js') }}"></script>
<script src="{{ asset('theme/dist/default/assets/js/blockui.js') }}"></script>
<script src="{{ asset('theme/dist/default/assets/js/treetable-script.js') }}"></script>
<!-- cleave.js -->
<script src="{{ asset('theme/dist/default/assets/libs/cleave.js/cleave.min.js') }}"></script>

<script src="{{ asset('theme/dist/default/assets/js/pages/materialdesign.list.js') }}"></script>
{{-- <script src="{{ asset('theme/dist/default/assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script> --}}
<script src="{{ asset('theme/dist/default/assets/libs/quill/quill.min.js') }}"></script>
<script src="{{ asset('theme/dist/default/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('theme/dist/default/assets/js/pages/dashboard-projects.init.js') }}"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/datetimepicker-jquery@2.5.11/build/jquery.datetimepicker.full.min.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script type="text/javascript">
    $('.clock-time').flatpickr({
        defaultDate: new Date(),
        maxDate: 'today',
        enableTime: true,
        dateFormat: 'Y-m-d H:i:ss'
    });

    $(".default-date").flatpickr({
        defaultDate: new Date(),
        dateFormat: 'd-m-Y'
    });

    $(".default-date-format").flatpickr({
        dateFormat: 'd-m-Y'
    })

    flatpickr("#rangeStartDate", {
        mode: "range",
        onChange: ([start, end]) => {
            if (start && end) {
                console.log({
                    start,
                    end
                });
            }
        }
    });

    function checkIfValueExistsInArray(array, value) {
        return array.includes(value)
    }

    function removeElementFromArray(array, element) {
        array.splice(array.indexOf(element), 1);
    }

    function selectAllItems(allCheckboxId, itemsClass, selectedItems, successCallback) {
        $(`#${allCheckboxId}`).on('change', function(e) {
            if (e.target.checked) {
                $(`.${itemsClass}`).prop('checked', true)
                $(`.${itemsClass}`).each(function() {
                    let checkValue = checkIfValueExistsInArray(selectedItems, $(this).val())
                    if (!checkValue) selectedItems.push($(this).val())
                })
            } else {
                $(`.${itemsClass}`).prop('checked', false)
                $(`.${itemsClass}`).each(function() {
                    let checkValue = removeElementFromArray(selectedItems, $(this).val())
                })
            }
            successCallback();
        })
    }

    function toggleItemCheckbox(itemIdentifier, selectedItems, successCallback) {
        $(itemIdentifier).on('change', function(e) {
            if (e.target.checked) {
                if (!checkIfValueExistsInArray(selectedItems, e.target.value)) selectedItems.push(
                    e.target.value)
            } else
                removeElementFromArray(selectedItems, e.target.value)

            successCallback()
        })
    }

    function hideLoading(div) {
        $(div).unblock();
    }

    function showLoading(div) {
        $(div).block({
            message: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
            overlayCSS: {
                backgroundColor: '#1B2024',
                opacity: 0.85,
                cursor: 'wait'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'none',
                color: '#fff'
            }
        });
    }

    function setInputErrors(form, errorsArr) {
        var objKeys = Object.keys(errorsArr)

        objKeys.forEach(element => {
            var input = $(`[name="${element}"]`);
            input.addClass('is-invalid');
            input.siblings('.invalid-tooltip').text(errorsArr[element][0]);
        });
    }

    $(document).on('click', '.accordion-button', function(e) {
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });

    $(document).ready(function() {

        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            console.log('on tab change');
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        })

        $('button[data-bs-toggle="pill"]').on('shown.bs.tab', function(e) {
            ;
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        })

        $('button[data-bs-toggle="collapse"]').on('shown.bs.tab', function(e) {
            console.log('ok');
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        })



        $.extend($.fn.dataTableExt.oStdClasses, {
            "sFilterInput": "form-control",
            "sLengthSelect": "form-control"
        });

        if ($("#CNIC").length) {
            var cleaveCNIC = new Cleave('#CNIC', {
                numericOnly: true,
                delimiter: '-',
                blocks: [5, 7, 1],
            });
        }

        if ($("#NTN").length) {
            var cleaveNTN = new Cleave('#NTN', {
                numericOnly: true,
                delimiter: '-',
                blocks: [7, 1],
            });
        }

        if ($("#STRN").length) {
            var cleaveSTRN = new Cleave('#STRN', {
                numericOnly: true,
                delimiter: '-',
                blocks: [7, 1],
            });
        }

        if ($("#feeMonth").length) {
            var cleaveDateFormat = new Cleave("#feeMonth", {
                date: !0,
                datePattern: ["m", "y"],
            });
        }

        if ($(".mobile-mask").length) {
            $(".mobile-mask").toArray().forEach(function(field) {
                var cleaveMobileMask = new Cleave(field, {
                    numericOnly: true,
                    delimiter: '-',
                    //prefix: '03',
                    blocks: [4, 7],
                });
            });
        }
    });

    $(document).on('click', '.delete-record', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');
        var table = $(this).data('table');
        var isajax = $(this).data('isajax');
        var this_var = $(this);
        Swal.fire({
            html: '<div class="mt-3">' +
                '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                '<div class="pt-2 mx-5 mt-4 fs-15">' +
                '<h4>Are you sure?</h4>' +
                '<p class="mx-4 mb-0 text-muted">Are you Sure You want to Delete this Record ?</p>' +
                '</div>' +
                '</div>',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-primary w-xs me-2 mb-1',
            confirmButtonText: 'Yes, Delete It!',
            cancelButtonClass: 'btn btn-danger w-xs mb-1',
            buttonsStyling: false,
            showCloseButton: true
        }).then(function(result) {

            if (result.isConfirmed) {

                $.ajax({

                    url: url,
                    type: "DELETE",
                    // data : filters,
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    cache: false,
                    success: function(data) {
                        if (isajax == false)
                            this_var.closest('tr').remove();
                        else
                            $('#' + table).DataTable().ajax.reload(null, false);
                        
                        // Show success message
                        Swal.fire({
                            html: '<div class="mt-3">' +
                                '<lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#0ab39c,secondary:#405189" style="width:120px;height:120px"></lord-icon>' +
                                '<div class="mt-4 pt-2 fs-15">' +
                                '<h4>Success !</h4>' +
                                '<p class="text-muted mx-4 mb-0">' + (data.message || 'Record has been successfully deleted.') + '</p>' +
                                '</div></div>',
                            showCancelButton: !0,
                            showConfirmButton: !1,
                            cancelButtonClass: "btn btn-primary w-xs mb-1",
                            cancelButtonText: "Okay",
                            buttonsStyling: !1,
                            showCloseButton: !0
                        });
                    },
                    error: function(xhr) {
                        var message = 'An error occurred while deleting the record.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            html: '<div class="mt-3">' +
                                '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                                '<div class="mt-4 pt-2 fs-15">' +
                                '<h4>Error !</h4>' +
                                '<p class="text-muted mx-4 mb-0">' + message + '</p>' +
                                '</div></div>',
                            showCancelButton: !0,
                            showConfirmButton: !1,
                            cancelButtonClass: "btn btn-primary w-xs mb-1",
                            cancelButtonText: "Okay",
                            buttonsStyling: !1,
                            showCloseButton: !0
                        });
                    },
                    beforeSend: function() {

                    },
                    complete: function() {

                    }
                });
            }
        });
    });

    $(document).on('click', '.delete-row', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var table = $(this).data('table');
        var isajax = $(this).data('isajax');
        var this_var = $(this);

        Swal.fire({
            html: '<div class="mt-3">' +
                '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                '<div class="mt-4 pt-2 fs-15 mx-5">' +
                '<h4>Are you sure?</h4>' +
                '<p class="text-muted mx-4 mb-0">Are you Sure You want to Delete this Record ?</p>' +
                '</div>' +
                '</div>',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-primary w-xs me-2 mb-1',
            confirmButtonText: 'Yes, Delete It!',
            cancelButtonClass: 'btn btn-danger w-xs mb-1',
            buttonsStyling: false,
            showCloseButton: true
        }).then(function(result) {

            if (result.isConfirmed) {

                $.ajax({

                    url: url,
                    type: "DELETE",
                    // data : filters,
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    cache: false,
                    success: function(data) {
                        if (isajax == false)
                            this_var.closest('tr').remove();
                        else
                            this_var.closest('tr').remove();

                        Swal.fire({
                            html: '<div class="mt-3">' +
                                '<lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#0ab39c,secondary:#405189" style="width:120px;height:120px"></lord-icon>' +
                                '<div class="mt-4 pt-2 fs-15">' +
                                '<h4>Success !</h4>' +
                                '<p class="text-muted mx-4 mb-0">Record has been successfully deleted.</p>' +
                                '</div></div>',
                            showCancelButton: !0,
                            showConfirmButton: !1,
                            cancelButtonClass: "btn btn-primary w-xs mb-1",
                            cancelButtonText: "Okay",
                            buttonsStyling: !1,
                            showCloseButton: !0
                        })
                    },
                    error: function() {

                    },
                    beforeSend: function() {

                    },
                    complete: function() {

                    }
                });
            }
        });
    });
    $(document).on('click', '.delete-attachment', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var table = $(this).data('table');
        var isajax = $(this).data('isajax');
        var this_var = $(this);

        Swal.fire({
            html: '<div class="mt-3">' +
                '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                '<div class="mt-4 pt-2 fs-15 mx-5">' +
                '<h4>Are you sure?</h4>' +
                '<p class="text-muted mx-4 mb-0">Are you Sure You want to Delete this file ?</p>' +
                '</div>' +
                '</div>',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-primary w-xs me-2 mb-1',
            confirmButtonText: 'Yes, Delete It!',
            cancelButtonClass: 'btn btn-danger w-xs mb-1',
            buttonsStyling: false,
            showCloseButton: true
        }).then(function(result) {

            if (result.isConfirmed) {

                $.ajax({

                    url: url,
                    type: "GET",
                    // data : filters,
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    cache: false,
                    success: function(data) {
                        if (isajax == false)
                            this_var.closest('tr').remove();
                        else
                            this_var.closest('tr').remove();

                        Swal.fire({
                            html: '<div class="mt-3">' +
                                '<lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#0ab39c,secondary:#405189" style="width:120px;height:120px"></lord-icon>' +
                                '<div class="mt-4 pt-2 fs-15">' +
                                '<h4>Success !</h4>' +
                                '<p class="text-muted mx-4 mb-0">File has been successfully deleted.</p>' +
                                '</div></div>',
                            showCancelButton: !0,
                            showConfirmButton: !1,
                            cancelButtonClass: "btn btn-primary w-xs mb-1",
                            cancelButtonText: "Okay",
                            buttonsStyling: !1,
                            showCloseButton: !0
                        })
                    },
                    error: function() {

                    },
                    beforeSend: function() {

                    },
                    complete: function() {

                    }
                });
            }
        });
    });

    $(document).on('change', '.academic-year-select', yearSelectHandler);

    function yearSelectHandler(e) {
        var target = $(this).data('target');
        var url = $(this).data('url');
        var target_name = target?.split('_')[0];
        var mul_targets = target.split(',');
        console.log(target, url, target_name);
        url_param_split = url.split('/')
        url_param = url_param_split[url_param_split.length - 1];
        $.ajax({
            url: url + '?academic_year_id=' + $(this).val(),
            type: "GET",
            cache: false,
            success: function(data) {
                $('select[name="' + mul_targets[0] + '"]').html('');
                $('select[name="' + mul_targets[1] + '"]').html('');
                var options = `<option value="">Please select a Fee Package</option>`;
                var gen_option = `<option value="">Please select a Fee Period</option>`;

                $.each(data.fee_packages, function(index, value) {
                    options += '<option value="' + value.id + '">' + value
                        .package_name + '</option>';
                })
                $('select[name="fee_package_id"]').html(options);
                $.each(data.fee_periods, function(index, value) {
                    gen_option += '<option value="' + value.id + '">' +
                        value.period_name + ' ' + value.from_date + ' ' + value
                        .to_date + '</option>';
                })
                $('select[name="fee_period_id"]').html(gen_option);

            },
            error: function() {

            },
            beforeSend: function() {
                // showLoading();
            },
            complete: function() {
                // hideLoading();
            }
        });
    }

    $(document).on('change', '.fee-period-academic-year', feePeriodyearSelectHandler);

    function feePeriodyearSelectHandler(e) {
        var target = $(this).data('target');
        var url = $(this).data('url');
        var target_name = target?.split('_')[0];
        console.log(target, url, target_name);
        url_param_split = url.split('/')
        url_param = url_param_split[url_param_split.length - 1];
        $.ajax({
            url: url + '?academic_year_id=' + $(this).val(),
            type: "GET",
            cache: false,
            success: function(data) {
                $('select[name="fee_period_id"]').html('');
                var options = `<option value="">Please select a Fee Period</option>`;
                $('select[name="fee_period_id"]').html(options);
                $.each(data.fee_periods, function(index, value) {
                    options += '<option value="' + value.id + '">' +
                        value.period_name + ' ' + value.from_date + ' ' + value
                        .to_date + '</option>';
                })
                $('select[name="fee_period_id"]').html(options);

            },
            error: function() {

            },
            beforeSend: function() {
                // showLoading();
            },
            complete: function() {
                // hideLoading();
            }
        });
    }

    $(document).on('change', '.load-select', loadSelectHandler);

    function loadSelectHandler(e) {

        var target = $(this).data('target');
        var url = $(this).data('url');

        var target_name = target?.split('_')[0];

        var mul_targets = target.split(',');

        if (target_name == 'branch') {
            target_name = 'br';
        }

        console.log(target, url, target_name, 'here');
        url_param_split = url.split('/')
        url_param = url_param_split[url_param_split.length - 1];
        if (url_param === 'list-class-sections') {
            url = url + '?branch_id=' + $('#branch_id').val() + '&id=' + $(this).val();
        } else {
            url = url + '?id=' + $(this).val();
        }
        $.ajax({
            url: url,
            type: "GET",
            cache: false,
            success: function(data) {
                var options = `<option value="">Please select a ${target_name}</option>`;
                if (target == 'promoted_branch_class_id') {
                    options = `<option value="">Please select a ${target.split('_')[2]}</option>`;
                }
                var gen_option = `<option value="">Please select</option>`;
                gen_option_2 = gen_option;

                if (mul_targets.length > 1) {
                    if (url_param === 'list-class-section-feeperiod') {
                        $.each(data.classes, function(index, value) {
                            gen_option += '<option value="' + value.id + '">' + value
                                .class_name + '</option>';
                        })

                        $.each(data.fee_periods, function(index, value) {
                            gen_option_2 += '<option value="' + value.id + '">' +
                                value.period_name + ' ' + value.from_date + ' ' + value
                                .to_date + '</option>';
                        })
                    } else if (url_param === 'list-branch-classes-months') {
                        $.each(data.classes, function(index, value) {
                            gen_option += '<option value="' + value.id + '">' + value
                                .class_name + '</option>';
                        })

                        $.each(data.months, function(index, value) {
                            gen_option_2 += '<option value="' + value + '">' + value +
                                '</option>';
                        })
                    } else if (url_param_split.includes('get-class-section-subjects')) {
                        /*Add condition by Ghulam Farid */
                        if (target.includes('promoted_section_id')) {
                            $.each(data.sections, function(index, value) {
                                gen_option += '<option value="' + value.sections.id + '">' + value[
                                    'sections'][
                                    `section_name`
                                ] + '</option>';
                            })
                        } else {
                            $.each(data.sections, function(index, value) {
                                gen_option += '<option value="' + value.id + '">' + value[
                                    'sections'][
                                    `section_name`
                                ] + '</option>';
                            })
                        }

                        $.each(data.subjects, function(index, value) {
                            gen_option_2 += '<option value="' + value.id + '" ' +
                                'data-subject-type="' + value.subject_type + '">' + value
                                .subject_name +
                                '</option>';
                        })
                    }
                } else if (data) {
                    $.each(data, function(index, value) {
                        if (value?.academic_year_id) {
                            options += '<option value="' +
                                value
                                .academic_year_id + '">' + value.academic_year.title +
                                '</option>';
                        } else if (target_name == 'week') {
                            options += '<option value="' + value.id + '">' + value.name +
                                '</option>';
                        } else if (target == 'fee_period_dates') {
                            // Monthly Student Invoice Detail.
                            const [iD, iM, iY] = data.issue_date.split('-');
                            const [dD, dM, dY] = data.due_date.split('-');
                            const [vD, vM, vY] = data.valid_date.split('-');


                            const issue_date = new Date(+iY, +iM - 1, +iD);
                            const due_date = new Date(+dY, +dM - 1, +dD);
                            const validity_date = new Date(+vY, +vM - 1, +vD);

                            flatpickr("#loadIssueDate", {
                                //defaultDate: issue_date,
                                defaultDate: new Date(),
                            })

                            flatpickr("#loadDueDate", {
                                defaultDate: due_date,
                            })

                            flatpickr("#loadValidDate", {
                                defaultDate: validity_date,
                            })
                        } else if (url_param === 'list-branch-classes') {
                            /*Add condition by Ghulam Farid */
                            if (target === 'promoted_branch_class_id') {
                                options += '<option value="' + value.id + '">' + value[
                                    'com_classes'][
                                    `${target?.split('_')[2]}_name`
                                ] + '</option>';
                            } else {
                                options += '<option value="' + value.id + '">' + value[
                                    'com_classes'][
                                    `${target_name}_name`
                                ] + '</option>';
                            }
                        } else if (url_param === 'list-class-sections') {
                            options += '<option value="' + value.sections.id + '">' + value[
                                'sections'][`section_name`] + '</option>';
                        } else if (url_param === 'list-branch-classes-sections') {
                            options += '<option value="' + value.id + '">' + value[
                                'sections'][
                                `section_name`
                            ] + '</option>';
                        } else if (url_param === 'get-assessment-level-child') {
                            options += '<option value="' + value.id + '">' + value['name'] +
                                '</option>';
                        } else if (value?.section_id) {
                            options += '<option value="' + value
                                .section_id + '">' + value.sections.section_name +
                                '</option>';
                        } else if (url_param === 'list-class-subjects') {
                            options += '<option value="' + value.id + '">' + value.subject_name + '</option>';
                        } else {
                            console.log('in the last else');
                            options +=
                                '<option value="' + value.id + '">' + value[
                                    `${target_name}_name`] + '</option>';
                        }
                    });
                }
                console.log(options, 'option ...');

                if (mul_targets.length > 1) {
                    if (url_param === 'list-class-section-feeperiod') {
                        $('select[name="class_id"]').html(gen_option).attr('disabled', false);
                        $('select[name="fee_period_id"]').html(gen_option_2).attr('disabled',
                            false);
                    } else if (url_param === 'list-branch-classes-months') {
                        $('select[name="class_id"]').html(gen_option).attr('disabled', false);
                        $('select[name="selected_month"]').html(gen_option_2).attr('disabled',
                            false);
                    } else if (url_param_split.includes('get-class-section-subjects')) {
                        /*Add condition by Ghulam Farid */
                        if (target.includes('promoted_section_id')) {
                            $('select[name="promoted_section_id"]').html(gen_option).attr('disabled',
                                false);
                            $('select[name="subject_id"]').html(gen_option_2).attr('disabled', false);
                        }
                        // else if(target.includes('system_notification')){
                        //     $('select[name="section"]').html(gen_option).attr('disabled', false);
                        // }
                        else {
                            $('select[name="section_id"]').html(gen_option).attr('disabled', false);
                            $('select[name="subject_id"]').html(gen_option_2).attr('disabled', false);
                        }
                    }
                } else {
                    $('select[name="' + target + '"]').html(options).attr('disabled', false);
                }
            },
            error: function() {

            },
            beforeSend: function() {
                // showLoading();
            },
            complete: function() {
                // hideLoading();
            }
        });
    }

    $(document).on('click', '.show-modal', function(e) {

        var target = $(this).data('target');
        var url = $(this).data('url');
        console.log('show modal', target, url);

        $.ajax({

            url: url,
            type: "GET",
            // dataType: 'html',
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            cache: false,
            success: function(data) {
                $('#modal-div').html(data);
                $(target).modal('show');
            },
            error: function() {

            },
            beforeSend: function() {

            },
            complete: function() {

            }
        });
    });

    $(document).on('change', '.load-select-skills', function(e) {


        var target = $(this).data('target2');
        var url = $(this).data('url2');

        console.log(target, url);

        $.ajax({

            url: url + '?id=' + $(this).val(),
            type: "GET",
            cache: false,
            success: function(data) {

                var options = '<option selected disabled> Select an Option</option>';

                if (data) {
                    $.each(data, function(index, value) {
                        options += '<option value="' + value.id + '">' + value.title +
                            '</option>';
                    });
                }
                $('select[name="' + target + '"]').html(options).attr('disabled', false);
            },
            error: function() {

            },
            beforeSend: function() {
                // showLoading();
            },
            complete: function() {
                // hideLoading();
            }
        });
    });

    $('.disable-max-date').flatpickr({
        maxDate: 'today',
    });

    $('.disable-min-date').flatpickr({
        minDate: 'today',
    });

    /*function to get url params*/
    function getUrlVars() {
        var vars = [],
            hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for (var i = 0; i < hashes.length; i++) {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    }

    $(document).on('change', '#header_nwa_branch_id', function(e) {
        let branch_id = $(this).val();
        $.ajax({
            url: '{{ route('network-associates-branches.set_branch') }}',
            type: "POST",
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            data: {
                branch_id: branch_id
            },
            cache: false,
            success: function(data) {
                location.reload();
            },
            error: function() {

            },
            beforeSend: function() {

            },
            complete: function() {

            }
        });
    });

    function getMonthNumberFromName(monthName) {
        return new Date(`${monthName} 1, 2022`).getMonth() + 1;
    }


    // Function to fetch and display curriculum attainment targets based on class and subject
    // Function to fetch and display curriculum attainment targets based on class and subject
    // function fetchCurriculumAttainmentTargets() {
    //     var classId = $('select[name="class_id"]').val();
    //     var subjectId = $('select[name="subject_id"]').val();

    //     // Use classId and subjectId to make an AJAX request to fetch curriculum attainment targets
    //     $.ajax({
    //         url: '/fetch-curriculum-attainment-targets',
    //         method: 'GET',
    //         data: {
    //             class_id: classId,
    //             subject_id: subjectId
    //         },
    //         success: function(response) {
    //             // Update the list of curriculum attainment targets
    //             $('#curriculum_attainment_targets_list').html(response.html);
    //         },
    //         error: function(error) {
    //             console.error('Error fetching curriculum attainment targets:', error);
    //         }
    //     });
    // }



    function daysInMonth(month, year) {
        return new Date(year, getMonthNumberFromName(month), 0).getDate();
    }

    function dmyStringToData(date) {
        var sDate = date.split('-')
        return new Date(+sDate[2], +sDate[1] - 1, +sDate[0])
    }

    function addDaysToDate(dateObj, days) {
        var miliseconds = dateObj.setDate(dateObj.getDate() + days);
        return new Date(miliseconds)
    }

    function getShortAttendance(value) {
        return value === '<span class="badge bg-primary">Present</span>' ?
            '<span class="fw-bold text-primary">P</span>' :
            value === '<span class="badge bg-danger">Absent</span>' ? '<span class="fw-bold text-danger">A</span>' :
            value === '<span class="badge bg-info">Leave</span>' ? '<span class="fw-bold text-info">L</span>' :
            value === '<span class="badge bg-warning">Tardy</span>' ? '<span class="fw-bold text-warning">T</span>' :
            value === '<span class="badge bg-success">Exempted</span>' ? '<span class="fw-bold text-success">E</span>' :
            '-'
    }
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '#print_student_progress_report', function(e) {
            e.preventDefault();
            let target = $('.show-progress-report-modal').data('target');
            $(target).modal().hide();
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            var newstr = $('.student_progress_report_modal').html();
            var oldstr = document.body.innerHTML;
            document.body.innerHTML = newstr;
            $("body").removeAttr("style");
            // $('body').css('overflow','scroll');

            window.print();
            document.body.innerHTML = oldstr;
            location.reload();
            // $("body").removeAttr("style");
            return false;
        });

        $(document).on('click', '#print_withdrawl_form', function(e) {
            e.preventDefault();
            let target = $('#withdrawalFormModal').data('target');
            $(target).modal().hide();
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            var newstr = $('#withdrawalFormModal').html();
            var oldstr = document.body.innerHTML;
            document.body.innerHTML = newstr;
            $("body").removeAttr("style");
            $('body').css('overflow', 'scroll');

            window.print();
            document.body.innerHTML = oldstr;
            location.reload();
            // $("body").removeAttr("style");
            return false;
        });

        $(document).on('click', '#print_transfer_form', function(e) {
            e.preventDefault();
            let target = $('#transferFormModal').data('target');
            $(target).modal().hide();
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            var newstr = $('.transfer_case_modal').html();

            var oldstr = document.body.innerHTML;
            document.body.innerHTML = newstr;
            // $("body").removeAttr("style");
            // $('body').css('overflow', 'scroll');
            window.print();
            document.body.innerHTML = oldstr;
            location.reload();
            // $("body").removeAttr("style");
            return false;
        });
        // print bulk report

        {{-- $(document).on('click', '#print_bulk_report', function (e) {
            e.preventDefault();
            const target = $('.get_bulk_report').data('target');
            $(target).modal().hide();
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            const report_html = $('.student_progress_report_modal').html();
            const w = window.open();

            w.document.write(`<meta charset="UTF-8">
            <meta name="viewport"
            content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            @include('assessment.grade_book.bulk_progress_reports.bulk_progress_report_lower_primary_style')
            <title>Lower Primary Progress Report</title>`);
            w.document.close();
            $(w.document.body).html(report_html);
            $(w.document.body).addClass('A4');

            // w.print();
            // w.close();
            $("body").removeAttr("style");

            return false;
        }); --}}

        $(document).on('click', '#print_bulk_report', function(e) {
            e.preventDefault();
            let target = $('.get_bulk_report').data('target');
            $(target).modal().hide();
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            var newstr = $('.student_progress_report_modal').html();
            var oldstr = document.body.innerHTML;
            document.body.innerHTML = newstr;
            $("body").removeAttr("style");
            // $('body').css('overflow','scroll');

            window.print();
            document.body.innerHTML = oldstr;
            // location.reload();
            // $("body").removeAttr("style");
            return false;
        });
    });
</script>
