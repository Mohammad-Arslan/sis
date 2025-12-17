<div class="modal fade" id="editQualificationModal" tabindex="-1" aria-labelledby="editQualificationModalLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editQualificationModalLabel">Edit Qualification Record </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" class="row g-3 needs-validation" novalidate id="editQualificationForm">
                    <input type="hidden" id="id" name="id" value="">
                    <input type="hidden" id="franchise_application_id" name="franchise_application_id" value="">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="qualification" name="qualification" placeholder="Qualification" required>
                            <label for="qualification" class="form-label">Qualification</label>
                            <div class="invalid-tooltip">Qualification is required!</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="passingYear" name="passing_year" placeholder="Passing Year" required>
                            <label for="passingYear" class="form-label"> Year of Passing</label>
                            <div class="invalid-tooltip">Passing year is required!</div>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" id="instituteName" name="institute" placeholder="institute_name" required></textarea>
                            <label for="instituteName" class="form-label">Name of Institution</label>
                            <div class="invalid-tooltip">Institute is required!</div>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary update_qualification" type="submit">Update Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@push('header_scripts')


@endpush

@push('footer_scripts')

<script type="text/javascript">

    $(document).on('submit', '#editQualificationForm', function(e) {
        e.preventDefault();
        if (!this.checkValidity()) {
            return;
        }

        $.ajax({
            url: "{{ route('save-application-qualification') }}",
            type: "POST",
            data: $('#editQualificationForm').serializeArray(),
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            cache: false,
            success: function (data) {
                document.getElementById("editQualificationForm").reset();
                $('.btn-close').trigger('click');
                Toastify({
                    text: data.message,
                    className: data.class,
                    duration: 3000
                }).showToast();
            },
            error: function () {},
            beforeSend: function () {
                $('.message').html('');
                $('.message').removeClass('link-success');
                $('.message').removeClass('link-danger');
            },
            complete: function () {}
        });
        e.preventDefault();

    });

</script>

@endpush
