<div class="modal fade" id="LessonPlanModal" tabindex="-1" aria-labelledby="LessonPlanModalLabel" aria-modal="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="LessonPlanModalLabel">Lesson Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @permission('duplicate-lesson-plan')
                <div class="row col-md-12 col-sm-12 d-flex justify-content-end">
                    <button class="btn btn-sm btn-secondary col-md-2 col-sm-2" onclick="GetDuplicateSelection()"> Duplicate Selected</button>
                </div>
                @endpermission
                <div class="col-md-12 col-sm-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-nowrap mb-0" id="dailyLessonPlan_Datatable" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>Topic</th>
                                    <th>Day</th>
                                    <th>Status</th>
                                    <th>Created on</th>
                                    @if(!auth()->user()->hasRole('teacher'))
                                        <th>Created by</th>
                                        <th>Approved by</th>
                                    @endif
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
