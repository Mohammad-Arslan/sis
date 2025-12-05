<div class="flex gap-2">
    <button onclick="openCountryModal({{ $row->id }})" 
            class="btn btn-sm btn-primary btn-circle" 
            title="Edit">
        <i class="ri-edit-line"></i>
    </button>
    <button onclick="deleteCountry({{ $row->id }})" 
            class="btn btn-sm btn-error btn-circle" 
            title="Delete">
        <i class="ri-delete-bin-line"></i>
    </button>
</div>

