<div class="card tasks-box" data-task_id="{{ $task->id }}" id="task-{{ $task->id }}">
    <div class="card-body">
        <div class="d-flex mb-2">
            <h6 class="fs-15 mb-0 flex-grow-1 text-truncate"><a href="{{ route('task-detail', ['task_id' => $task->id]) }}">{{ $task->title }}</a></h6>
            <div class="dropdown">
                <a href="javascript:void(0);" class="text-muted" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill"></i></a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                    <li><a class="dropdown-item" href="apps-tasks-details.html"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                    <li><a class="dropdown-item" href="#"><i class="ri-edit-2-line align-bottom me-2 text-muted"></i> Edit</a></li>
                    <li><a class="dropdown-item" data-bs-toggle="modal" href="#deleteRecordModal"><i class="ri-delete-bin-5-line align-bottom me-2 text-muted"></i> Delete</a></li>
                </ul>
            </div>
        </div>
        <p class="text-muted">{{ $task->summary }}</p>


        @if ($task->sub_tasks->count() > 0)

        <div class="mb-3">
            <div class="d-flex mb-1">
                <div class="flex-grow-1">
                    <h6 class="text-muted mb-0"><span class="text-secondary">15%</span> of 100%</h6>
                </div>
                <div class="flex-shrink-0">
                    <span class="text-muted">03 Jan, 2022</span>
                </div>
            </div>
            <div class="progress rounded-3 progress-sm">
                <div class="progress-bar bg-danger" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
        @endif

        <!--   -->
        <div class="d-flex align-items-center">
            <div class="flex-grow-1">

                @if ($task->task_detail_route)
                <span class="badge badge-soft-primary show-modal" data-url="{{route($task->task_detail_route, $task->taskable_id)}}" data-target="#franchiseInquireModal">{{ $task->project->project_type->name }}</span>
                @else
                <span class="badge badge-soft-primary">{{ $task->project->project_type->name }}</span>

                @endif

            </div>
            <div class="flex-shrink-0">
                <div class="avatar-group">
                    <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Alexis">
                        <img src="{{ asset('theme/dist/default/assets/images/users/avatar-6.jpg') }}" alt="" class="rounded-circle avatar-xxs">
                    </a>
                    <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Nancy">
                        <img src="{{ asset('theme/dist/default/assets/images/users/avatar-5.jpg') }}" alt="" class="rounded-circle avatar-xxs">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer border-top-dashed">
        <div class="d-flex">
            <div class="flex-grow-1">
                <h6 class="text-muted mb-0">#VL2436</h6>
            </div>
            <div class="flex-shrink-0">
                <ul class="link-inline mb-0">
                    <li class="list-inline-item">
                        <a href="javascript:void(0)" class="text-muted"><i class="ri-eye-line align-bottom"></i> 04</a>
                    </li>
                    <li class="list-inline-item">
                        <a href="javascript:void(0)" class="text-muted"><i class="ri-question-answer-line align-bottom"></i> 19</a>
                    </li>
                    <li class="list-inline-item">
                        <a href="javascript:void(0)" class="text-muted"><i class="ri-attachment-2 align-bottom"></i> 02</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>