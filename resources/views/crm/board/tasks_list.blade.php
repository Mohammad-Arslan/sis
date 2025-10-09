<div class="tasks-list">
    <div class="d-flex mb-3">
        <div class="flex-grow-1">
           <h6 class="fs-14 text-uppercase fw-semibold mb-0">{{ $task_status->name }} <small class="badge bg-success align-bottom ms-1">{{ $task_status->tasks->count() }}</small></h6>
        </div>
        <div class="flex-shrink-0">
            <div class="dropdown card-header-dropdown">
                <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="fw-medium text-muted fs-12">Priority<i class="mdi mdi-chevron-down ms-1"></i></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="#">Priority</a>
                    <a class="dropdown-item" href="#">Date Added</a>
                </div>
            </div>
        </div>
    </div>
    <div data-simplebar class="tasks-wrapper px-3 mx-n3">
        <div id="{{ $task_status->slug }}-task" data-task_status="{{ $task_status->id }}" class="tasks">
        	@foreach ($task_status->tasks as $task)
	        	@include('crm.board.task_card')
			@endforeach
        </div>
    </div>
    <!-- <div class="my-3">
        <button class="btn btn-soft-info w-100 add-task" data-task_status="{{ $task_status->id }}">Add More</button>
    </div> -->
</div>