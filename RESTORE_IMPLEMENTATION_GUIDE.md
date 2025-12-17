# Restore Functionality Implementation Guide

This guide shows how to implement restore functionality for soft-deleted models across your Laravel ERP application.

## Overview

When a model uses `SoftDeletes`, you can restore deleted records. This guide provides a complete pattern for implementing restore functionality following Laravel 12 and PHP 8.5 best practices.

---

## 1. Service Layer (Business Logic)

Add a `restore` method to your service class:

```php
<?php

namespace App\Services;

use App\Models\TaxType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TaxSettingsService
{
    /**
     * Restore a soft-deleted tax type
     */
    #[\NoDiscard]
    public function restoreTaxType(int $id): TaxType
    {
        try {
            return DB::transaction(function () use ($id) {
                $taxType = TaxType::withTrashed()->findOrFail($id);
                
                if (!$taxType->trashed()) {
                    throw new \Exception('Record is not deleted.');
                }
                
                $taxType->restore();
                Cache::forget('tax.tax-types');
                
                return $taxType->fresh();
            });
        } catch (\Exception $e) {
            Log::error('Failed to restore tax type', [
                'tax_type_id' => $id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Restore a soft-deleted tax
     */
    #[\NoDiscard]
    public function restoreTax(int $id): Tax
    {
        try {
            return DB::transaction(function () use ($id) {
                $tax = Tax::withTrashed()->findOrFail($id);
                
                if (!$tax->trashed()) {
                    throw new \Exception('Record is not deleted.');
                }
                
                $tax->restore();
                Cache::forget('tax.taxes');
                
                return $tax->fresh()->load(['tax_type', 'state']);
            });
        } catch (\Exception $e) {
            Log::error('Failed to restore tax', [
                'tax_id' => $id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
```

---

## 2. Controller Method

Add a `restore` method to your controller:

```php
<?php

namespace App\Http\Controllers;

use App\Services\TaxSettingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TaxSettingsController extends Controller
{
    public function __construct(
        private readonly TaxSettingsService $service
    ) {}

    /**
     * Restore a soft-deleted tax type
     */
    public function restoreTaxType(int $id): JsonResponse
    {
        try {
            $taxType = $this->service->restoreTaxType($id);

            return response()->json([
                'success' => true,
                'message' => 'Tax type restored successfully.',
                'data' => $taxType
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tax type not found.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to restore tax type', [
                'tax_type_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Failed to restore tax type. Please try again.'
            ], 500);
        }
    }

    /**
     * Restore a soft-deleted tax
     */
    public function restoreTax(int $id): JsonResponse
    {
        try {
            $tax = $this->service->restoreTax($id);

            return response()->json([
                'success' => true,
                'message' => 'Tax restored successfully.',
                'data' => $tax
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tax not found.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to restore tax', [
                'tax_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Failed to restore tax. Please try again.'
            ], 500);
        }
    }
}
```

---

## 3. Routes

Add restore routes to `routes/web.php`:

```php
// For Tax Types
Route::post('/tax-types/{id}/restore', [TaxSettingsController::class, 'restoreTaxType'])
    ->name('tax-types.restore');

// For Taxes
Route::post('/taxes/{id}/restore', [TaxSettingsController::class, 'restoreTax'])
    ->name('taxes.restore');
```

**Alternative RESTful approach:**

```php
Route::resource('tax-types', TaxSettingsController::class)->except(['create', 'edit']);
Route::post('tax-types/{id}/restore', [TaxSettingsController::class, 'restoreTaxType'])
    ->name('tax-types.restore');
```

---

## 4. DataTable Integration

### Option A: Show Restore Button Only for Deleted Records

In your DataTable query, include trashed records and show restore button conditionally:

```php
public function getTaxTypes(Request $request): JsonResponse
{
    if (!$request->ajax()) {
        return response()->json(['error' => 'Invalid request'], 400);
    }

    $data = TaxType::withTrashed(); // Include soft-deleted records
    
    return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($row) {
            if ($row->trashed()) {
                // Show restore button for deleted records
                return '<a href="javascript:void(0);" 
                           class="btn btn-sm btn-warning btn-icon waves-effect waves-light restore-record" 
                           data-id="' . $row->id . '" 
                           data-url="' . route('tax-types.restore', $row->id) . '"
                           data-table="tax-types-datatable"
                           title="Restore">
                           <i class="ri-restart-line"></i>
                       </a>';
            } else {
                // Show normal action buttons (edit, delete)
                return $this->service->generateModalActionButtons($row->id, 'TaxType');
            }
        })
        ->addColumn('deleted_at', function($row) {
            return $row->deleted_at ? $row->deleted_at->format('Y-m-d H:i:s') : '-';
        })
        ->rawColumns(['action'])
        ->make(true);
}
```

### Option B: Separate Tabs/Views for Active and Deleted Records

Create separate endpoints:

```php
// Active records
public function getTaxTypes(Request $request): JsonResponse
{
    $data = TaxType::query(); // Only active records
    // ... normal DataTable setup
}

// Deleted records
public function getDeletedTaxTypes(Request $request): JsonResponse
{
    $data = TaxType::onlyTrashed(); // Only deleted records
    
    return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($row) {
            return '<a href="javascript:void(0);" 
                       class="btn btn-sm btn-warning btn-icon waves-effect waves-light restore-record" 
                       data-id="' . $row->id . '" 
                       data-url="' . route('tax-types.restore', $row->id) . '"
                       data-table="deleted-tax-types-datatable"
                       title="Restore">
                       <i class="ri-restart-line"></i>
                   </a>';
        })
        ->rawColumns(['action'])
        ->make(true);
}
```

---

## 5. JavaScript Handler

Add restore functionality to `public/js/crud-operations.js` or your view's script section:

```javascript
/**
 * Handle restore record functionality
 */
$(document).on('click', '.restore-record', function(e) {
    e.preventDefault();
    
    const button = $(this);
    const id = button.data('id');
    const url = button.data('url');
    const tableId = button.data('table');
    
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to restore this record?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, restore it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Disable button during request
            button.prop('disabled', true);
            
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // Reload DataTable
                        if (tableId && $('#' + tableId).length) {
                            $('#' + tableId).DataTable().ajax.reload(null, false);
                        }
                        
                        // Show success message
                        Swal.fire({
                            html: '<div class="mt-3">' +
                                '<lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#0ab39c,secondary:#405189" style="width:120px;height:120px"></lord-icon>' +
                                '<div class="mt-4 pt-2 fs-15">' +
                                '<h4>Restored!</h4>' +
                                '<p class="text-muted mx-4 mb-0">' + (response.message || 'Record has been successfully restored.') + '</p>' +
                                '</div></div>',
                            showCancelButton: !0,
                            showConfirmButton: !1,
                            cancelButtonClass: "btn btn-primary w-xs mb-1",
                            cancelButtonText: "Okay",
                            buttonsStyling: !1,
                            showCloseButton: !0
                        });
                    } else {
                        showToast(response.message || 'Failed to restore record.', 'error');
                        button.prop('disabled', false);
                    }
                },
                error: function(xhr) {
                    handleAjaxError(xhr);
                    button.prop('disabled', false);
                }
            });
        }
    });
});
```

---

## 6. Blade View Example

### Action Column with Restore Button

```blade
{{-- resources/views/settings/tax/actions.blade.php --}}
@if($row->trashed())
    {{-- Show restore button for deleted records --}}
    <a href="javascript:void(0);" 
       class="btn btn-sm btn-warning btn-icon waves-effect waves-light restore-record" 
       data-id="{{ $row->id }}" 
       data-url="{{ route('tax-types.restore', $row->id) }}"
       data-table="tax-types-datatable"
       title="Restore">
       <i class="ri-restart-line"></i>
    </a>
@else
    {{-- Normal action buttons --}}
    @permission('edit-tax-type')
        <a href="javascript:void(0);" 
           class="btn btn-sm btn-success btn-icon waves-effect waves-light edit-tax-type" 
           data-id="{{ $row->id }}"
           title="Edit">
           <i class="mdi mdi-lead-pencil"></i>
        </a>
    @endpermission
    
    @permission('delete-tax-type')
        <a href="{{ route('tax-types.destroy', $row->id) }}" 
           data-table="tax-types-datatable" 
           class="btn btn-sm btn-danger btn-icon waves-effect delete-record"
           title="Delete">
           <i class="ri-delete-bin-5-line"></i>
        </a>
    @endpermission
@endif
```

### Using in DataTable

```php
// In Controller
->addColumn('action', function($row) {
    return view('settings.tax.actions', ['row' => $row])->render();
})
```

---

## 7. Complete Example: TaxType Model

### Model (app/Models/TaxType.php)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class TaxType extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];
}
```

### Service Method (app/Services/TaxSettingsService.php)
```php
#[\NoDiscard]
public function restoreTaxType(int $id): TaxType
{
    try {
        return DB::transaction(function () use ($id) {
            $taxType = TaxType::withTrashed()->findOrFail($id);
            
            if (!$taxType->trashed()) {
                throw new \Exception('Record is not deleted.');
            }
            
            $taxType->restore();
            Cache::forget('tax.tax-types');
            
            return $taxType->fresh();
        });
    } catch (\Exception $e) {
        Log::error('Failed to restore tax type', [
            'tax_type_id' => $id,
            'error' => $e->getMessage()
        ]);
        throw $e;
    }
}
```

### Controller Method (app/Http/Controllers/TaxSettingsController.php)
```php
public function restoreTaxType(int $id): JsonResponse
{
    try {
        $taxType = $this->service->restoreTaxType($id);

        return response()->json([
            'success' => true,
            'message' => 'Tax type restored successfully.',
            'data' => $taxType
        ]);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Tax type not found.'
        ], 404);
    } catch (\Exception $e) {
        Log::error('Failed to restore tax type', [
            'tax_type_id' => $id,
            'error' => $e->getMessage()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => $e->getMessage() ?: 'Failed to restore tax type. Please try again.'
        ], 500);
    }
}
```

### Route (routes/web.php)
```php
Route::post('/tax-types/{id}/restore', [TaxSettingsController::class, 'restoreTaxType'])
    ->name('tax-types.restore')
    ->middleware(['auth']);
```

---

## 8. Best Practices

### ✅ DO:
- Use `withTrashed()` to find soft-deleted records
- Check if record is trashed before restoring: `if (!$model->trashed())`
- Use transactions for data integrity
- Clear relevant cache after restore
- Use `#[\NoDiscard]` attribute on restore methods
- Return proper HTTP status codes (200, 404, 500)
- Log errors for debugging
- Show confirmation dialog before restore
- Reload DataTable after successful restore

### ❌ DON'T:
- Don't restore records that aren't deleted
- Don't forget to handle `ModelNotFoundException`
- Don't skip transaction wrapping
- Don't forget to clear cache
- Don't expose internal error messages to users
- Don't skip permission checks

---

## 9. Permission-Based Access

Add permission checks:

```php
// In Controller
public function restoreTaxType(int $id): JsonResponse
{
    // Check permission
    if (!auth()->user()->can('restore-tax-type')) {
        return response()->json([
            'success' => false,
            'message' => 'You do not have permission to restore tax types.'
        ], 403);
    }
    
    // ... rest of the method
}
```

```blade
{{-- In Blade view --}}
@permission('restore-tax-type')
    <a href="javascript:void(0);" 
       class="btn btn-sm btn-warning restore-record" 
       data-url="{{ route('tax-types.restore', $row->id) }}">
       <i class="ri-restart-line"></i> Restore
    </a>
@endpermission
```

---

## 10. Testing Restore Functionality

```php
// tests/Feature/TaxTypeRestoreTest.php
public function test_can_restore_soft_deleted_tax_type(): void
{
    $taxType = TaxType::factory()->create();
    $taxType->delete();
    
    $this->assertTrue($taxType->trashed());
    
    $response = $this->postJson(route('tax-types.restore', $taxType->id));
    
    $response->assertOk();
    $response->assertJson(['success' => true]);
    
    $this->assertFalse($taxType->fresh()->trashed());
}

public function test_cannot_restore_non_deleted_record(): void
{
    $taxType = TaxType::factory()->create();
    
    $response = $this->postJson(route('tax-types.restore', $taxType->id));
    
    $response->assertStatus(500);
}
```

---

## Summary

1. **Service Layer**: Add `restore{Model}` method with transaction and cache clearing
2. **Controller**: Add `restore{Model}` method with proper error handling
3. **Routes**: Add POST route for restore action
4. **DataTable**: Include `withTrashed()` and conditionally show restore button
5. **JavaScript**: Add `.restore-record` click handler with confirmation
6. **Blade**: Create action column view with conditional restore button
7. **Permissions**: Add permission checks where needed

This pattern can be applied to any model with soft deletes in your application.

