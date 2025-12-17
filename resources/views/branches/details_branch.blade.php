<div id="branchModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">{{ $branch->id }} - {{ $branch->br_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5 class="fs-16">{{ $branch->br_name }}</h5>
                <p class="text-muted">One morning, when Gregor Samsa woke from troubled dreams, he found himself transformed in his bed into a horrible vermin. He lay on his armour-like back, and if he lifted his head a little he could see his brown belly, slightly domed and divided by arches into stiff sections.</p>
                <p class="text-muted">The bedding was hardly able to cover it and seemed ready to slide off any moment. His many legs, pitifully thin compared with the size of the rest of him, waved about helplessly as he looked. "What's happened to me?" he thought.</p>
                <hr/>
                <div class="card">
                    <div class="card-body shadow">
                        <h5 class="card-title mb-4">Skills</h5>
                        <div class="d-flex flex-wrap gap-2 fs-15">
                            <a href="javascript:void(0);" class="badge badge-soft-primary">Photoshop</a>
                            <a href="javascript:void(0);" class="badge badge-soft-primary">illustrator</a>
                            <a href="javascript:void(0);" class="badge badge-soft-primary">HTML</a>
                            <a href="javascript:void(0);" class="badge badge-soft-primary">CSS</a>
                            <a href="javascript:void(0);" class="badge badge-soft-primary">Javascript</a>
                            <a href="javascript:void(0);" class="badge badge-soft-primary">Php</a>
                            <a href="javascript:void(0);" class="badge badge-soft-primary">Python</a>
                        </div>
                    </div><!-- end card body -->
                </div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary ">Save Changes</button>
            </div> -->
        </div>
    </div>
</div>