<div id="assignBranchToNWA" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Un-Assigned Branches</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @forelse ($branches as $branch)
                <div class="list-group">
                    <label class="list-group-item" for="branchId_{{ $branch->id}}">
                        <input class="form-check-input me-1 assign-branch-nwa" name="branch_id" type="checkbox" value="{{ $branch->id}}" {{ in_array($branch->id,$nwa_branches) ? 'checked' : '' }}/>
                        {{ $branch->br_name }}
                    </label>
                </div>
                @empty
                <div class="alert alert-info alert-border-left alert-dismissible fade show" role="alert">
                    <i class="ri-airplay-line me-3 align-middle fs-16"></i><strong>Info</strong>
                    No Un-Assigned Branches <a>Create New Branch</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
