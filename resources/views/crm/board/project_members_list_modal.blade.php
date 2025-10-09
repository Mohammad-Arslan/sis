<div id="assignEmpModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Members List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive table-card">
                            <table class="table table-borderless table-nowrap align-middle mb-0"> 
                                <thead class="table-light text-muted">
                                    <tr>
                                        <th scope="col">Member</th>
                                        <th scope="col" class="text-center">Tasks</th>
                                        <th scope="col" class="text-center" style="width:10px;">Actions</th>
                                    </tr>
                                </thead>                                                
                                <tbody>
                                    @foreach ($project_members as $project_member)
                                    <tr>
                                        <td class="d-flex">
                                            <img src="{{ asset('theme/dist/default/assets/images/users/avatar-'.$project_member->user_id.'.jpg') }}" alt="" class="avatar-xs rounded-3 me-2">
                                            <div>
                                                <h5 class="fs-13 mb-0">{{ $project_member->user->name }}</h5>
                                                <p class="fs-12 mb-0 text-muted">UI/UX Designer</p>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $project_member->user->tasks->count() }}</td>
                                        <td class="text-center">

                                            @if(in_array($project_member->user_id, $task_members))
                                            <button type="button" class="btn btn-danger btn-icon btn-sm waves-effect waves-light toggle-member-task" data-task_id="{{$task_id}}" data-user_id="{{$project_member->user_id}}" data-url="{{route('toggle-task-member')}}"  data-project_id="{{$project_id}}">
                                                <i class="ri-delete-bin-5-line"></i>
                                            </button>
                                            @else
                                            <button type="button" class="btn btn-success btn-icon btn-sm waves-effect waves-light toggle-member-task" data-user_id="{{$project_member->user_id}}" data-url="{{route('toggle-task-member')}}"  data-task_id="{{$task_id}}" data-project_id="{{$project_id}}">
                                                <i class="ri-check-double-line"></i>
                                            </button>
                                            @endif
                                            
                                        </td>
                                    </tr>
                                    @endforeach    
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>