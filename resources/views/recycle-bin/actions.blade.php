<a href="javascript:void(0);" 
   class="btn btn-sm btn-warning btn-icon waves-effect waves-light restore-record" 
   data-id="{{ $id }}" 
   data-url="{{ $restoreUrl }}"
   data-table="recycle-bin-table"
   title="Restore">
   <i class="ri-restart-line"></i>
</a>

<a href="javascript:void(0);" 
   class="btn btn-sm btn-danger btn-icon waves-effect waves-light force-delete-record" 
   data-id="{{ $id }}" 
   data-url="{{ $deleteUrl }}"
   data-table="recycle-bin-table"
   data-model-type="{{ $modelType }}"
   title="Permanently Delete">
   <i class="ri-delete-bin-7-line"></i>
</a>

