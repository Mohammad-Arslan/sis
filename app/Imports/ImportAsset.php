<?php

namespace App\Imports;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Supplier;
use App\Models\Branch;
use App\Models\Department;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use PDOException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImportAsset implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading, SkipsOnError, SkipsEmptyRows, SkipsOnFailure
{
    use SkipsFailures;

    public $importedCount = 0;
    public $skippedCount = 0;
    public $errors = [];
    public $currentRowNumber = 0; // Track current row number (excluding header)
    public $totalRowsProcessed = 0; // Track total rows including headers and empty rows
    public $importId = null;
    public $processedRows = []; // Track processed rows to avoid duplicates
    public $excelSerialNumbers = []; // Track serial numbers within the Excel file
    public $duplicateSerialInExcel = []; // Track duplicate serials within Excel
    public $duplicateAssetInDb = 0; // Count of assets already existing in DB
    public $rowToExcelRowMap = []; // Map internal row numbers to Excel row numbers

    // Cache for lookup tables to avoid repeated database queries
    public $categoryCache = [];
    public $supplierCache = [];
    public $branchCache = [];
    public $departmentCache = [];
    public $userCache = [];
    public $existingAssetTagCache = [];
    public $existingSerialNumberCache = [];

    public function __construct($importId = null)
    {
        // Store import ID for logging
        $this->importId = $importId;

        // Set dynamic PHP configuration for large imports
        $this->setDynamicConfiguration();

        // Pre-load all lookup tables into memory for faster access
        $this->preloadLookupTables();

        // Clear the log file at the start of each import session if no specific import ID
        if (! $importId) {
            $logPath = storage_path('logs/asset_import.log');
            if (File::exists($logPath)) {
                File::put($logPath, '');
            }
        }

        // Reset row counter
        $this->currentRowNumber = 0;
    }

    /**
     * Set dynamic PHP configuration for large imports
     */
    private function setDynamicConfiguration()
    {
        // Increase memory limit for large imports (up to 2GB)
        $currentMemoryLimit = ini_get('memory_limit');
        $currentMemoryBytes = $this->convertToBytes($currentMemoryLimit);
        $requiredMemoryBytes = 2 * 1024 * 1024 * 1024; // 2GB

        if ($currentMemoryBytes < $requiredMemoryBytes) {
            ini_set('memory_limit', '2G');
        }

        // Increase max execution time (30 minutes)
        ini_set('max_execution_time', 1800);

        // Increase input time limit
        ini_set('max_input_time', 1800);

        // Increase post max size for large file uploads
        $currentPostMaxSize = ini_get('post_max_size');
        $currentPostMaxBytes = $this->convertToBytes($currentPostMaxSize);
        $requiredPostMaxBytes = 100 * 1024 * 1024; // 100MB

        if ($currentPostMaxBytes < $requiredPostMaxBytes) {
            ini_set('post_max_size', '100M');
        }

        // Increase upload max filesize
        $currentUploadMaxSize = ini_get('upload_max_filesize');
        $currentUploadMaxBytes = $this->convertToBytes($currentUploadMaxSize);
        $requiredUploadMaxBytes = 100 * 1024 * 1024; // 100MB

        if ($currentUploadMaxBytes < $requiredUploadMaxBytes) {
            ini_set('upload_max_filesize', '100M');
        }

        // Disable output buffering for better memory management
        if (ob_get_level()) {
            ob_end_clean();
        }

        // Set garbage collection to run more frequently
        gc_enable();

        Log::info('Dynamic configuration set for large asset import', [
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'post_max_size' => ini_get('post_max_size'),
            'upload_max_filesize' => ini_get('upload_max_filesize')
        ]);
    }

    /**
     * Convert memory/upload size string to bytes
     */
    private function convertToBytes($sizeStr)
    {
        $sizeStr = trim($sizeStr);
        $last = strtolower($sizeStr[strlen($sizeStr) - 1]);
        $size = (int) $sizeStr;

        switch ($last) {
            case 'g':
                $size *= 1024;
            case 'm':
                $size *= 1024;
            case 'k':
                $size *= 1024;
        }

        return $size;
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Pre-load all lookup tables to avoid repeated database queries
     */
    private function preloadLookupTables()
    {
        // Load asset categories
        $categories = AssetCategory::select('id', 'name', 'code')->get();
        foreach ($categories as $category) {
            $this->categoryCache[strtolower(trim($category->name))] = $category->id;
            if ($category->code) {
                $this->categoryCache[strtolower(trim($category->code))] = $category->id;
            }
        }

        // Load suppliers
        $suppliers = Supplier::select('id', 'name', 'code')->get();
        foreach ($suppliers as $supplier) {
            $this->supplierCache[strtolower(trim($supplier->name))] = $supplier->id;
            if ($supplier->code) {
                $this->supplierCache[strtolower(trim($supplier->code))] = $supplier->id;
            }
        }

        // Load branches - be more flexible with branch names
        $branches = Branch::select('id', 'br_name', 'abbreviation', 'branch_code')->get();
        foreach ($branches as $branch) {
            $this->branchCache[strtolower(trim($branch->br_name))] = $branch->id;
            if ($branch->abbreviation) {
                $this->branchCache[strtolower(trim($branch->abbreviation))] = $branch->id;
            }
            if ($branch->branch_code) {
                $this->branchCache[strtolower(trim($branch->branch_code))] = $branch->id;
            }
        }

        // Load departments
        $departments = Department::select('id', 'department_name', 'abbreviation')->get();
        foreach ($departments as $department) {
            $this->departmentCache[strtolower(trim($department->department_name))] = $department->id;
            if ($department->abbreviation) {
                $this->departmentCache[strtolower(trim($department->abbreviation))] = $department->id;
            }
        }

        // Load users (employees)
        $users = User::select('id', 'first_name', 'last_name', 'email')
            ->whereHas('employee', function ($query) {
                $query->whereNotNull('department_id')
                    ->whereNotNull('branch_id');
            })
            ->get();
        foreach ($users as $user) {
            $fullName = strtolower(trim($user->first_name . ' ' . $user->last_name));
            $this->userCache[$fullName] = $user->id;
            $this->userCache[strtolower(trim($user->email))] = $user->id;
        }

        // Load existing asset tags and serial numbers to avoid duplicates
        $existingAssets = Asset::select('asset_tag', 'serial_number')->get();
        foreach ($existingAssets as $asset) {
            if ($asset->asset_tag) {
                $this->existingAssetTagCache[trim($asset->asset_tag)] = true;
            }
            if ($asset->serial_number) {
                $this->existingSerialNumberCache[trim($asset->serial_number)] = true;
            }
        }

        // Note: Debug info is not logged to main import file

        // Log cache statistics for debugging
        Log::info('Asset import cache loaded', [
            'categories' => count($this->categoryCache),
            'suppliers' => count($this->supplierCache),
            'branches' => count($this->branchCache),
            'departments' => count($this->departmentCache),
            'users' => count($this->userCache),
            'existing_assets' => count($this->existingAssetTagCache),
            'sample_branches' => array_slice(array_keys($this->branchCache), 0, 5),
            'sample_departments' => array_slice(array_keys($this->departmentCache), 0, 5)
        ]);
    }

    /**
     * Debug method to check what's available in lookup caches
     */
    public function debugLookupCaches()
    {
        return [
            'branches' => array_keys($this->branchCache),
            'departments' => array_keys($this->departmentCache),
            'categories' => array_keys($this->categoryCache),
            'suppliers' => array_keys($this->supplierCache),
            'users' => array_keys($this->userCache)
        ];
    }

    /**
     * Parse date values from Excel
     */
    private function parseDate($value, $format = 'Y-m-d')
    {
        if (empty($value) || $value === 'NULL' || $value === null) {
            return null;
        }

        // If it's a numeric value (Excel date), convert it
        if (is_numeric($value)) {
            try {
                $date = Date::excelToDateTimeObject($value);
                return $date->format($format);
            } catch (\Exception $e) {
                Log::error("Failed to parse Excel date: {$value}", ['error' => $e->getMessage()]);
                return null;
            }
        }

        // If it's already a string, try to parse it
        if (is_string($value)) {
            $value = trim($value);

            // Try different date formats
            $formats = [
                'Y-m-d',
                'd/m/Y',
                'm/d/Y',
                'd-m-Y',
                'm-d-Y',
                'Y/m/d',
                'd.m.Y',
                'm.d.Y'
            ];

            foreach ($formats as $dateFormat) {
                try {
                    $date = Carbon::createFromFormat($dateFormat, $value);
                    return $date->format('Y-m-d');
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        Log::warning("Could not parse date: {$value}");
        return null;
    }

    /**
     * Check if a date string is valid
     */
    private function isValidDateString($value)
    {
        if (empty($value) || $value === 'NULL' || $value === null) {
            return false;
        }

        if (is_numeric($value)) {
            return true; // Excel date number
        }

        if (is_string($value)) {
            $value = trim($value);

            $formats = [
                'Y-m-d',
                'd/m/Y',
                'm/d/Y',
                'd-m-Y',
                'm-d-Y',
                'Y/m/d',
                'd.m.Y',
                'm.d.Y'
            ];

            foreach ($formats as $format) {
                try {
                    Carbon::createFromFormat($format, $value);
                    return true;
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        return false;
    }

    /**
     * Get lookup ID from cache
     */
    private function getLookupId($cache, $value, $type)
    {
        if (empty($value)) {
            return null;
        }

        $key = strtolower(trim($value));
        if (isset($cache[$key])) {
            // Handle both simple values and arrays (for designations)
            if (is_array($cache[$key])) {
                return $cache[$key]['id'];
            }
            return $cache[$key];
        }

        Log::warning("Could not find {$type} for value: {$value}");
        return null;
    }

    /**
     * Create the model instance
     */
    public function model(array $row)
    {
        // Increment total rows processed (including headers and empty rows)
        $this->totalRowsProcessed++;

        try {
            // Skip header row (check if name contains header-like text)
            if (isset($row['name']) && in_array(strtolower(trim($row['name'])), ['name', 'asset_name', 'asset name', 'asset_tag', 'asset tag'])) {
                return null; // Skip header row silently
            }

            // Skip if essential fields are missing or if row is completely empty
            if (empty($row['name']) || empty($row['serial_number'])) {
                // Check if this is a completely empty row
                $hasAnyData = false;
                foreach ($row as $value) {
                    if (! empty($value) && $value !== null && $value !== '') {
                        $hasAnyData = true;
                        break;
                    }
                }

                if (! $hasAnyData) {
                    // Completely empty row, skip silently
                    return null;
                }

                // Row has some data but missing essential fields
                $this->skippedCount++;

                // Write to log file
                $logEntry = [
                    'type' => 'missing_fields',
                    'row' => $this->currentRowNumber + 1, // Use +1 since we haven't incremented yet
                    'field' => null,
                    'error' => 'Missing essential fields: name or serial_number',
                    'value' => 'name: ' . ($row['name'] ?? 'empty') . ', serial_number: ' . ($row['serial_number'] ?? 'empty'),
                    'timestamp' => now()->toDateTimeString(),
                ];
                $this->writeLog('missing_fields', 'Missing essential fields', $logEntry);

                return null;
            }

            // Convert serial number to string if it's numeric
            if (isset($row['serial_number']) && is_numeric($row['serial_number'])) {
                $row['serial_number'] = (string) $row['serial_number'];
            }

            // Check if asset already exists by asset tag or serial number (using cached data)
            $serialNumber = trim($row['serial_number']);
            $assetTag = trim($row['asset_tag'] ?? '');

            // Generate asset tag if not provided
            if (empty($assetTag)) {
                $asset = new \App\Models\Asset();
                $assetTag = $asset->generateAssetTag();
                $row['asset_tag'] = $assetTag;
            }

            // Check for duplicates in database
            if (
                isset($this->existingSerialNumberCache[$serialNumber]) ||
                (! empty($assetTag) && isset($this->existingAssetTagCache[$assetTag]))
            ) {
                $this->skippedCount++;
                return null; // Skip this row
            }

            // Only increment row number when we're actually processing a valid data row
            $this->currentRowNumber++;

            // Date validation logic
            $purchaseDate = $this->parseDate($row['purchase_date'] ?? null);
            $warrantyEndDate = $this->parseDate($row['warranty_end_date'] ?? null);

            if ($purchaseDate && $warrantyEndDate && $warrantyEndDate <= $purchaseDate) {
                $logEntry = [
                    'type' => 'validation_error',
                    'row' => $this->currentRowNumber,
                    'field' => 'warranty_end_date',
                    'error' => 'Warranty end date must be after purchase date',
                    'value' => $row['warranty_end_date'] ?? '',
                    'timestamp' => now()->toDateTimeString(),
                ];
                $this->errors[] = $logEntry;
                $this->writeLog('validation_error', 'Warranty end date must be after purchase date', $logEntry);
                $this->skippedCount++;
                return null;
            }

            // Validate lookup fields exist
            $categoryId = $this->getLookupId($this->categoryCache, $row['category'] ?? '', 'category');
            $supplierId = $this->getLookupId($this->supplierCache, $row['supplier'] ?? '', 'supplier');

            // Try multiple possible column names for branch
            $branchValue = $row['Current Branch'] ?? $row['current_branch'] ?? $row['branch'] ?? '';
            $branchId = $this->getLookupId($this->branchCache, $branchValue, 'branch');

            // Try multiple possible column names for department
            $departmentValue = $row['Current Department'] ?? $row['current_department'] ?? $row['department'] ?? '';
            $departmentId = $this->getLookupId($this->departmentCache, $departmentValue, 'department');

            // Handle assigned to user
            $assignedToValue = $row['Assigned To'] ?? $row['assigned_to'] ?? $row['AssignedTo'] ?? '';
            $userId = null;
            if (! empty($assignedToValue) && strtolower(trim($assignedToValue)) !== 'n/a' && strtolower(trim($assignedToValue)) !== 'na') {
                $userId = $this->getLookupId($this->userCache, $assignedToValue, 'user');
            }

            // Log errors for missing lookup records
            if (! $categoryId && ! empty($row['category'])) {
                $logEntry = [
                    'type' => 'lookup_error',
                    'row' => $this->currentRowNumber,
                    'field' => 'category',
                    'error' => 'Category does not exist in the system',
                    'value' => $row['category'],
                    'timestamp' => now()->toDateTimeString(),
                ];
                $this->errors[] = $logEntry;
                $this->writeLog('lookup_error', 'Category does not exist in the system', $logEntry);
            }

            if (! $branchId && ! empty($branchValue)) {
                $logEntry = [
                    'type' => 'lookup_error',
                    'row' => $this->currentRowNumber,
                    'field' => 'branch',
                    'error' => 'Branch does not exist in the system',
                    'value' => $branchValue,
                    'timestamp' => now()->toDateTimeString(),
                ];
                $this->errors[] = $logEntry;
                $this->writeLog('lookup_error', 'Branch does not exist in the system', $logEntry);
                $this->skippedCount++;
                return null; // Branch is required
            }

            if (! $departmentId && ! empty($departmentValue)) {
                $logEntry = [
                    'type' => 'lookup_error',
                    'row' => $this->currentRowNumber,
                    'field' => 'department',
                    'error' => 'Department does not exist in the system',
                    'value' => $departmentValue,
                    'timestamp' => now()->toDateTimeString(),
                ];
                $this->errors[] = $logEntry;
                $this->writeLog('lookup_error', 'Department does not exist in the system', $logEntry);
            }

            if (! $supplierId && ! empty($row['supplier'])) {
                $logEntry = [
                    'type' => 'lookup_error',
                    'row' => $this->currentRowNumber,
                    'field' => 'supplier',
                    'error' => 'Supplier does not exist in the system',
                    'value' => $row['supplier'],
                    'timestamp' => now()->toDateTimeString(),
                ];
                $this->errors[] = $logEntry;
                $this->writeLog('lookup_error', 'Supplier does not exist in the system', $logEntry);
            }

            $this->importedCount++;

            DB::beginTransaction();

            try {
                // Create new Asset record
                $assetData = [
                    'asset_tag' => $assetTag,
                    'name' => trim($row['name']),
                    'description' => trim($row['description'] ?? ''),
                    'category_id' => $categoryId,
                    'supplier_id' => $supplierId,
                    'serial_number' => $serialNumber,
                    'model' => trim($row['model'] ?? ''),
                    'brand' => trim($row['brand'] ?? ''),
                    'purchase_date' => $purchaseDate,
                    'purchase_price' => ! empty($row['purchase_price']) ? floatval($row['purchase_price']) : null,
                    'warranty_end_date' => $warrantyEndDate,
                    'condition' => strtolower(trim($row['condition'] ?? 'good')),
                    'status' => strtolower(trim($row['status'] ?? 'active')),
                    'current_branch_id' => $branchId,
                    'current_department_id' => $departmentId,
                    'assigned_to_user_id' => $userId,
                    'qr_code' => 'ASSET-' . $assetTag,
                ];

                // Create new asset
                $asset = Asset::create($assetData);

                // Update caches to prevent duplicates
                $this->existingAssetTagCache[$assetTag] = true;
                $this->existingSerialNumberCache[$serialNumber] = true;

                DB::commit();

                Log::info("Successfully imported asset: {$asset->name} (ID: {$asset->id})");

                // Memory management for large imports
                if ($this->importedCount % 100 === 0) {
                    // Force garbage collection every 100 records
                    gc_collect_cycles();

                    // Log memory usage for monitoring
                    $memoryUsage = memory_get_usage(true);
                    $memoryPeak = memory_get_peak_usage(true);
                    Log::info("Import progress: {$this->importedCount} records imported", [
                        'memory_usage' => $this->formatBytes($memoryUsage),
                        'memory_peak' => $this->formatBytes($memoryPeak),
                        'memory_limit' => ini_get('memory_limit')
                    ]);
                }

                return $asset;
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e; // Re-throw to be caught by outer try-catch
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->skippedCount++;

            $errorMessage = "Error importing asset row: " . $e->getMessage();
            Log::error($errorMessage, [
                'row' => $row,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Write to log file
            $logEntry = [
                'type' => 'import_error',
                'row' => $this->currentRowNumber,
                'field' => null,
                'error' => $errorMessage,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'row_data' => json_encode(array_slice($row, 0, 5)), // Show first 5 fields
                'timestamp' => now()->toDateTimeString(),
            ];
            $this->writeLog('import_error', $errorMessage, $logEntry);

            $this->errors[] = [
                'row' => $this->currentRowNumber,
                'error' => $errorMessage
            ];

            return null;
        }
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'asset_tag' => ['nullable', 'string', 'max:50'], // Made optional since we generate it
            'serial_number' => ['required', 'string', 'max:255'], // Serial number must be provided in Excel file
            'description' => ['nullable', 'string'],
            'model' => ['nullable', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'condition' => ['nullable', Rule::in(['new', 'good', 'fair', 'poor', 'damaged', 'New', 'Good', 'Fair', 'Poor', 'Damaged'])],
            'status' => ['nullable', Rule::in(['active', 'inactive', 'maintenance', 'retired', 'lost', 'stolen', 'Active', 'Inactive', 'Maintenance', 'Retired', 'Lost', 'Stolen'])],
            'purchase_date' => ['nullable', function ($attribute, $value, $fail) {
                if (! empty($value) && ! $this->isValidDateString($value)) {
                    $fail('Purchase date must be a valid date format.');
                }
            }],
            'warranty_end_date' => ['nullable', function ($attribute, $value, $fail) {
                if (! empty($value) && ! $this->isValidDateString($value)) {
                    $fail('Warranty end date must be a valid date format.');
                }
            }],
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages(): array
    {
        return [
            'name.required' => 'Asset name is required.',
            'name.string' => 'Asset name must be a string.',
            'name.max' => 'Asset name cannot exceed 255 characters.',
            'asset_tag.string' => 'Asset tag must be a string.',
            'asset_tag.max' => 'Asset tag cannot exceed 50 characters.',
            'serial_number.required' => 'Serial number is required and must be provided in the Excel file.',
            'serial_number.string' => 'Serial number must be a string.',
            'serial_number.max' => 'Serial number cannot exceed 255 characters.',
            'description.string' => 'Description must be a string.',
            'model.string' => 'Model must be a string.',
            'model.max' => 'Model cannot exceed 255 characters.',
            'brand.string' => 'Brand must be a string.',
            'brand.max' => 'Brand cannot exceed 255 characters.',
            'purchase_price.numeric' => 'Purchase price must be a number.',
            'purchase_price.min' => 'Purchase price cannot be negative.',
            'condition.in' => 'Condition must be new, good, fair, poor, or damaged (case insensitive).',
            'status.in' => 'Status must be active, inactive, maintenance, retired, lost, or stolen (case insensitive).',
            'purchase_date.date' => 'Purchase date must be a valid date.',
            'warranty_end_date.date' => 'Warranty end date must be a valid date.',
        ];
    }

    /**
     * Batch size for processing
     */
    public function batchSize(): int
    {
        // Optimize batch size based on available memory
        $memoryLimit = ini_get('memory_limit');
        $memoryBytes = $this->convertToBytes($memoryLimit);

        // For large memory (2GB+), use larger batches
        if ($memoryBytes >= 2 * 1024 * 1024 * 1024) {
            return 200; // 200 records per batch
        } elseif ($memoryBytes >= 1 * 1024 * 1024 * 1024) {
            return 100; // 100 records per batch
        } else {
            return 50; // Default batch size
        }
    }

    /**
     * Chunk size for reading
     */
    public function chunkSize(): int
    {
        // Optimize chunk size based on available memory
        $memoryLimit = ini_get('memory_limit');
        $memoryBytes = $this->convertToBytes($memoryLimit);

        // For large memory (2GB+), use larger chunks
        if ($memoryBytes >= 2 * 1024 * 1024 * 1024) {
            return 500; // 500 records per chunk
        } elseif ($memoryBytes >= 1 * 1024 * 1024 * 1024) {
            return 250; // 250 records per chunk
        } else {
            return 100; // Default chunk size
        }
    }

    /**
     * Handle errors during import
     */
    public function onError(\Throwable $e)
    {
        $this->skippedCount++;

        $errorMessage = "Error importing asset row: " . $e->getMessage();
        Log::error($errorMessage, [
            'row' => $this->currentRowNumber,
            'exception' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        // Extract the problematic row data for better error reporting
        $rowData = $e->getTrace()[0]['args'][0] ?? [];
        $problematicValue = 'N/A';

        // Try to identify the specific field that caused the error
        $fieldName = null;
        $cleanErrorMessage = $errorMessage;
        $problematicValue = 'N/A';

        if (strpos($errorMessage, 'Undefined variable') !== false) {
            // Extract variable name from error message
            preg_match('/Undefined variable \$(\w+)/', $errorMessage, $matches);
            if (isset($matches[1])) {
                $fieldName = $matches[1];
                $problematicValue = $rowData[$fieldName] ?? 'Field not found in row data';
                $cleanErrorMessage = "Missing required field: {$fieldName}";
            }
        } elseif (strpos($errorMessage, 'Integrity constraint violation') !== false) {
            // Handle database constraint violations
            if (strpos($errorMessage, 'Duplicate entry') !== false) {
                // For upsert behavior, don't log duplicates as errors - they're expected
                // Just increment skipped count and continue silently
                $this->skippedCount++;
                return; // Don't log this as an error
            } elseif (strpos($errorMessage, 'PRIMARY') !== false) {
                // Handle primary key violations (shouldn't happen with proper upsert)
                $cleanErrorMessage = "Primary key constraint violation";
                $problematicValue = "Check asset ID uniqueness";
            } else {
                $cleanErrorMessage = "Database constraint violation";
                $problematicValue = "Check data integrity";
            }
        } elseif (strpos($errorMessage, 'SQLSTATE') !== false) {
            // Handle other SQL errors
            if (strpos($errorMessage, 'Data too long') !== false) {
                $cleanErrorMessage = "Data too long for database field";
                $problematicValue = "Check field length limits";
            } elseif (strpos($errorMessage, 'Cannot add or update a child row') !== false) {
                $cleanErrorMessage = "Foreign key constraint violation";
                $problematicValue = "Check lookup data exists (category, supplier, etc.)";
            } else {
                $cleanErrorMessage = "Database error occurred";
                $problematicValue = "Check data format and constraints";
            }
        } else {
            // For other errors, show the first few fields that might be problematic
            $cleanErrorMessage = "Error processing row data";
            $problematicValue = "Check all required fields";
        }

        // Write to log file
        $logEntry = [
            'type' => 'import_error',
            'row' => $this->currentRowNumber,
            'field' => $fieldName,
            'error' => $cleanErrorMessage,
            'value' => $problematicValue,
            'exception' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'row_data' => json_encode(array_slice($rowData, 0, 5)), // Show first 5 fields
            'timestamp' => now()->toDateTimeString(),
        ];
        $this->writeLog('import_error', $cleanErrorMessage, $logEntry);

        $this->errors[] = [
            'error' => $errorMessage,
            'row' => $this->currentRowNumber
        ];
    }

    /**
     * Configure heading row
     */
    public function headingRow(): int
    {
        return 1; // First row is the header
    }

    /**
     * Get import statistics
     */
    public function getImportStats(): array
    {
        return [
            'imported' => $this->importedCount,
            'skipped' => $this->skippedCount,
            'errors' => count($this->errors),
            'total_processed' => $this->importedCount + $this->skippedCount,
            'total_rows' => $this->totalRowsProcessed
        ];
    }

    /**
     * Reset counters
     */
    public function resetCounters(): void
    {
        $this->importedCount = 0;
        $this->skippedCount = 0;
        $this->errors = [];
        $this->currentRowNumber = 0;
        $this->totalRowsProcessed = 0;
    }

    /**
     * Handle validation failures
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->skippedCount++;

            $errorMessage = "Validation failed: " . implode(', ', $failure->errors());
            Log::warning($errorMessage, [
                'row' => $this->currentRowNumber, // Use custom counter instead of failure->row()
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors(),
                'values' => $failure->values()
            ]);

            // Write to log file
            $logEntry = [
                'type' => 'validation_error',
                'row' => $this->currentRowNumber, // Use custom counter instead of failure->row()
                'field' => $failure->attribute(),
                'error' => $errorMessage,
                'value' => $failure->values()[$failure->attribute()] ?? 'N/A',
                'timestamp' => now()->toDateTimeString(),
            ];
            $this->writeLog('validation_error', $errorMessage, $logEntry);

            $this->errors[] = [
                'row' => $this->currentRowNumber, // Use custom counter instead of failure->row()
                'error' => $errorMessage
            ];
        }
    }

    /**
     * Log summary of import results
     */
    public function logImportSummary()
    {
        $stats = $this->getImportStats();

        // Log duplicate assets found in database
        if ($this->duplicateAssetInDb > 0) {
            $this->writeLog('duplicate_summary', "Skipped {$this->duplicateAssetInDb} assets that already exist in database", [
                'duplicate_count' => $this->duplicateAssetInDb,
                'type' => 'assets_already_in_database'
            ]);
        }

        // Log duplicate serial numbers within Excel file
        if (! empty($this->duplicateSerialInExcel)) {
            $this->writeLog('duplicate_serial_summary', "Found " . count($this->duplicateSerialInExcel) . " duplicate serial numbers within Excel file", [
                'duplicate_count' => count($this->duplicateSerialInExcel),
                'duplicates' => $this->duplicateSerialInExcel,
                'type' => 'duplicate_serials_in_excel'
            ]);
        }

        // Log overall import summary
        $this->writeLog('import_summary', "Import completed with {$stats['imported']} assets imported and {$stats['skipped']} skipped", [
            'imported' => $stats['imported'],
            'skipped' => $stats['skipped'],
            'errors' => $stats['errors'],
            'total_processed' => $stats['total_processed'],
            'total_rows' => $stats['total_rows']
        ]);
    }

    /**
     * Write log entry to asset import log file
     */
    private function writeLog($type, $message, $context = [])
    {
        $logEntry = array_merge([
            'type' => $type,
            'message' => $message,
            'timestamp' => now()->toDateTimeString(),
            'import_id' => $this->importId
        ], $context);

        // Write to general asset import log
        File::append(storage_path('logs/asset_import.log'), json_encode($logEntry) . PHP_EOL);

        // Also write to specific log file if we have an import ID
        if ($this->importId) {
            $specificLogPath = storage_path("logs/asset_import_{$this->importId}.log");
            File::append($specificLogPath, json_encode($logEntry) . PHP_EOL);
        }
    }
}
