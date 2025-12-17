<div class="accordion-item mt-3">
    <h2 class="accordion-header" id="accordionborderedUploadDocuments">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accor_borderedcollapse6" aria-expanded="false" aria-controls="accor_borderedcollapse6" @if(!isset($franchise)) disabled @endif>
            Upload Documents
        </button>
    </h2>
    <div id="accor_borderedcollapse6" class="accordion-collapse collapse" aria-labelledby="accordionborderedUploadDocuments" data-bs-parent="#accordionBordered" style="">
        <div class="accordion-body">
            @if(isset($franchise))
            @include('franchise_application.upload_document_partial')
            @endif
        </div>
    </div>
</div>
