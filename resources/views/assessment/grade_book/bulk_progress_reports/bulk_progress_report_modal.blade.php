<div id="bulkProgressReportModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">
                    @if($data['class_level'] == 'EY')
                        Early Year: {{$data['class_name']}}
                    @elseif( str_contains($data['class_name'], '1') ||
                                str_contains($data['class_name'], 'one') ||
                                str_contains($data['class_name'], '2') ||
                                str_contains($data['class_name'], 'two'))
                        Lower Primary: {{$data['class_name']}}
                    @else
                        Upper Primary: {{$data['class_name']}}
                    @endif
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body student_progress_report_modal p-0 border-0 my-3 mx-0">
                {!! $data['html_body'] !!}
                <div class="hstack gap-2 justify-content-end d-print-none mt-4 p-4">
                    <a href="" id="print_bulk_report" class="btn btn-info"><i
                            class="ri-printer-line align-bottom me-1"></i> Print</a>
                </div>
            </div>
        </div>
    </div>
</div>
