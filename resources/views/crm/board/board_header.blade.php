<div class="card">
    <div class="card-body">
        <div class="row g-2">
            <!-- <div class="col-lg-auto">
                <div class="hstack gap-2">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createboardModal"><i class="ri-add-line align-bottom me-1"></i> Create Board</button>
                </div>
            </div> -->
            <div class="col-lg-3 col-auto">
                <div class="search-box">
                    <input type="text" class="form-control search" placeholder="Search for project, tasks...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>
            <div class="col-auto ms-sm-auto">
                <div class="avatar-group" id="newMembar">

                    @foreach ($project->members as $member)
                        <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="{{ $member->user->name }}">
                            <img src="{{ asset('theme/dist/default/assets/images/users/avatar-'.$member->user->id.'.jpg') }}" alt="" class="rounded-circle avatar-xs">
                        </a>
                    @endforeach

                    <a href="javascript: void(0);"  class="avatar-group-item add-project-member">
                        <div class="avatar-xs">
                            <div class="avatar-title rounded-circle">
                                +
                            </div>
                        </div>
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>