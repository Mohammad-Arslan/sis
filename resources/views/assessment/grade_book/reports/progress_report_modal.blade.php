<div id="progressReportModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">
                    @if( isset($data['class_level']) ? $data['class_level'] == 'EY' : 'N/A')
                        Early Year: {{$data['student_behaviour_skill']['com_class']['class_name'] ?? 'N/A'}}
                    @endif
                    @if(isset($data['student_behaviour_skill']['com_class']['class_name']))
                        @if( str_contains($data['student_behaviour_skill']['com_class']['class_name'], '1') ||
                                    str_contains($data['student_behaviour_skill']['com_class']['class_name'], 'one') ||
                                    str_contains($data['student_behaviour_skill']['com_class']['class_name'], '2') ||
                                    str_contains($data['student_behaviour_skill']['com_class']['class_name'], 'two'))
                            Lower Primary: {{$data['student_behaviour_skill']['com_class']['class_name']}}
                        @elseif(str_contains($data['student_behaviour_skill']['com_class']['class_name'], '3') ||
                                    str_contains($data['student_behaviour_skill']['com_class']['class_name'], 'three') ||
                                    str_contains($data['student_behaviour_skill']['com_class']['class_name'], '4') ||
                                    str_contains($data['student_behaviour_skill']['com_class']['class_name'], 'four') ||
                                    str_contains($data['student_behaviour_skill']['com_class']['class_name'], '5') ||
                                    str_contains($data['student_behaviour_skill']['com_class']['class_name'], 'five'))
                            Upper Primary: {{$data['student_behaviour_skill']['com_class']['class_name']}}
                        @endif
                    @endif
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body student_progress_report_modal p-0 border-0 my-3 mx-0">
                @if($data['class_level'] == 'EY')
                    @include('assessment.grade_book.reports.early_years', $data)
                @elseif(in_array($data['class_level'], ['LP', 'UP']))
                    @include('assessment.grade_book.reports.lower_primary', $data)
                @endif
            </div>
        </div>
    </div>
</div>
