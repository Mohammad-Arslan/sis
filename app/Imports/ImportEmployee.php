<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Employee;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Religion;
use App\Models\Nationality;
use App\Models\Company;
use App\Models\Region;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\DesignationType;
use App\Models\EmployeeLeaveQuota;
use App\Models\DesignationLeaveQuota;
use App\Models\ImportProgress;
use App\Models\ImportErrorLog;
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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ImportEmployee implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading, SkipsOnError, SkipsEmptyRows, SkipsOnFailure
{
    use SkipsFailures;

    public $importedCount = 0;
    public $skippedCount = 0;
    public $errors = [];
    public $currentRowNumber = 0; // Track current row number
    public $totalRowsProcessed = 0; // Track total rows including headers and empty rows

    // Import tracking
    protected $importId;
    protected $importProgress;
    protected $userId;

    // Cache for lookup tables to avoid repeated database queries
    private $countryCache = [];
    private $stateCache = [];
    private $cityCache = [];
    private $religionCache = [];
    private $nationalityCache = [];
    private $companyCache = [];
    private $regionCache = [];
    private $branchCache = [];
    private $departmentCache = [];
    private $designationCache = [];
    private $existingEmailCache = [];
    private $existingCnicCache = [];

    // Progress callback
    private $progressCallback = null;

    public function __construct($importId = null, $userId = null)
    {
        $this->importId = $importId;
        $this->userId = $userId;

        // Set dynamic PHP configuration for large imports
        $this->setDynamicConfiguration();

        // Pre-load all lookup tables into memory for faster access
        $this->preloadLookupTables();

        // Initialize import progress tracking
        if ($this->importId) {
            $this->importProgress = ImportProgress::where('import_id', $this->importId)->first();

            // Clear previous error logs for this import
            ImportErrorLog::truncateForImport($this->importId);
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

        Log::info('Dynamic configuration set for large import', [
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
        // Load countries
        $countries = Country::select('id', 'country_name')->get();
        foreach ($countries as $country) {
            $this->countryCache[strtolower(trim($country->country_name))] = $country->id;
        }

        // Load states
        $states = State::select('id', 'state_name')->get();
        foreach ($states as $state) {
            $this->stateCache[strtolower(trim($state->state_name))] = $state->id;
        }

        // Load cities
        $cities = City::select('id', 'city_name')->get();
        foreach ($cities as $city) {
            $this->cityCache[strtolower(trim($city->city_name))] = $city->id;
        }

        // Load religions
        $religions = Religion::select('id', 'religion_name')->get();
        foreach ($religions as $religion) {
            $this->religionCache[strtolower(trim($religion->religion_name))] = $religion->id;
        }

        // Load nationalities
        $nationalities = Nationality::select('id', 'nationality_name')->get();
        foreach ($nationalities as $nationality) {
            $this->nationalityCache[strtolower(trim($nationality->nationality_name))] = $nationality->id;
        }

        // Load companies
        $companies = Company::select('id', 'company_name')->get();
        foreach ($companies as $company) {
            $this->companyCache[strtolower(trim($company->company_name))] = $company->id;
        }

        // Load regions
        $regions = Region::select('id', 'region_name')->get();
        foreach ($regions as $region) {
            $this->regionCache[strtolower(trim($region->region_name))] = $region->id;
        }

        // Load branches
        $branches = Branch::select('id', 'br_name', 'branch_code')->get();
        foreach ($branches as $branch) {
            $this->branchCache[strtolower(trim($branch->br_name))] = $branch->id;
            $this->branchCache[strtolower(trim($branch->branch_code))] = $branch->id;
        }

        // Load departments
        $departments = Department::select('id', 'department_name')->get();
        foreach ($departments as $department) {
            $this->departmentCache[strtolower(trim($department->department_name))] = $department->id;
        }

        // Load designations
        $designations = Designation::select('id', 'designation_name', 'type_id')->get();
        foreach ($designations as $designation) {
            $this->designationCache[strtolower(trim($designation->designation_name))] = [
                'id' => $designation->id,
                'type_id' => $designation->type_id
            ];
        }

        // Load existing emails and CNICs to avoid duplicates
        $existingUsers = User::select('email', 'CNIC')->get();
        foreach ($existingUsers as $user) {
            if ($user->email) {
                $this->existingEmailCache[strtolower(trim($user->email))] = true;
            }
            if ($user->CNIC) {
                $this->existingCnicCache[trim($user->CNIC)] = true;
            }
        }
    }

    /**
     * Set progress callback for real-time updates
     */
    public function setProgressCallback(callable $callback): void
    {
        $this->progressCallback = $callback;
    }

    /**
     * Trigger progress callback and update database
     */
    private function triggerProgressCallback(): void
    {
        if ($this->progressCallback && $this->totalRowsProcessed % 10 === 0) {
            call_user_func($this->progressCallback, $this->getImportStats());
        }

        // Update database progress
        if ($this->importProgress) {
            $this->importProgress->updateProgress([
                'processed_rows' => $this->totalRowsProcessed,
                'imported_count' => $this->importedCount,
                'skipped_count' => $this->skippedCount,
                'error_count' => count($this->errors),
                'current_row' => $this->currentRowNumber,
                'current_message' => "Processing row {$this->currentRowNumber}...",
            ]);
        }
    }

    /**
     * Add error to database and local array
     */
    private function addError(array $errorData): void
    {
        $this->errors[] = $errorData;

        // Store error in ImportErrorLog table
        if ($this->importId && $this->userId) {
            ImportErrorLog::create([
                'import_id' => $this->importId,
                'import_type' => 'employee',
                'user_id' => $this->userId,
                'row_number' => $errorData['row'] ?? $this->currentRowNumber,
                'error_type' => $errorData['type'] ?? 'import_error',
                'field_name' => $errorData['field'] ?? null,
                'error_message' => $errorData['error'] ?? 'Unknown error',
                'problematic_value' => $errorData['value'] ?? null,
                'row_data' => $errorData['row_data'] ?? null,
                'occurred_at' => now(),
            ]);
        }

        // Also update ImportProgress for backward compatibility
        if ($this->importProgress) {
            $this->importProgress->addError($errorData);
        }
    }

    /**
     * Parse date values from Excel
     */
    private function parseDate($value, $format = 'Y-m-d')
    {
        // Handle '-' as null (from export format)
        if (empty($value) || $value === 'NULL' || $value === null || $value === '-') {
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

            // Try different date formats - PRIORITIZE d-m-Y format (export format)
            $formats = [
                'd-m-Y',     // Export format - try this first
                'Y-m-d',
                'd/m/Y',
                'm/d/Y',
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
        // Handle '-' as null (from export format)
        if (empty($value) || $value === 'NULL' || $value === null || $value === '-') {
            return false;
        }

        if (is_numeric($value)) {
            return true; // Excel date number
        }

        if (is_string($value)) {
            $value = trim($value);

            // PRIORITIZE d-m-Y format (export format)
            $formats = [
                'd-m-Y',     // Export format - try this first
                'Y-m-d',
                'd/m/Y',
                'm/d/Y',
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
        // Handle '-' as null (from export format)
        if (empty($value) || $value === '-') {
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
     * Clean value from export format (convert '-' to null/empty)
     */
    private function cleanValue($value)
    {
        if ($value === '-' || $value === 'NULL' || trim($value ?? '') === '') {
            return null;
        }
        return trim($value);
    }

    /**
     * Create the model instance
     */
    public function model(array $row)
    {
        // Increment row number for tracking (this happens for every row)
        $this->currentRowNumber++;

        // Trigger progress callback
        $this->triggerProgressCallback();

        try {
            // Skip the 'total_service' field from export (it's a calculated field, not for import)
            if (isset($row['total_service'])) {
                unset($row['total_service']);
            }

            // Clean all row values (convert '-' to null)
            foreach ($row as $key => $value) {
                if ($value === '-') {
                    $row[$key] = null;
                }
            }

            // Handle full_name field by splitting it into first_name and last_name
            if (isset($row['full_name']) && ! empty($row['full_name']) && (empty($row['first_name']) || empty($row['last_name']))) {
                $fullName = trim($row['full_name']);
                $nameParts = explode(' ', $fullName, 2);

                if (count($nameParts) >= 1) {
                    $row['first_name'] = $nameParts[0];
                    if (count($nameParts) >= 2) {
                        $row['last_name'] = $nameParts[1];
                    } else {
                        $row['last_name'] = '';
                    }
                }
            }

            // Skip header row (check if first_name contains header-like text)
            if (isset($row['first_name']) && in_array(strtolower(trim($row['first_name'])), ['first_name', 'first name', 'name', 'prefix', 'last_name', 'last name', 'full_name', 'full name'])) {
                return null; // Skip header row silently
            }

            // Skip if essential fields are missing or if row is completely empty
            // Check if we have either first_name or full_name (already handled '-' conversion above)
            $hasName = ! empty($row['first_name']) || ! empty($row['full_name']);
            $hasEmail = ! empty($row['email']);
            $hasCnic = ! empty($row['cnic']);

            if (! $hasName || ! $hasEmail || ! $hasCnic) {
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
                Log::warning("Skipping row due to missing essential fields", ['row' => $row]);

                // Add error to database
                $missingFields = [];
                if (! $hasName) {
                    $missingFields[] = 'first_name or full_name';
                }
                if (! $hasEmail) {
                    $missingFields[] = 'email';
                }
                if (! $hasCnic) {
                    $missingFields[] = 'cnic';
                }

                $this->addError([
                    'type' => 'missing_fields',
                    'row' => $this->currentRowNumber,
                    'field' => null,
                    'error' => 'Missing essential fields: ' . implode(', ', $missingFields),
                    'value' => 'first_name: ' . ($row['first_name'] ?? 'empty') . ', full_name: ' . ($row['full_name'] ?? 'empty') . ', email: ' . ($row['email'] ?? 'empty') . ', cnic: ' . ($row['cnic'] ?? 'empty'),
                    'timestamp' => now()->toDateTimeString(),
                ]);

                return null;
            }

            // Get email and CNIC for upsert logic
            $email = strtolower(trim($row['email']));
            $cnic = trim($row['cnic']);

            // Increment total rows processed (only for valid data rows)
            $this->totalRowsProcessed++;

            // Conditional validation for marital status
            $maritalStatus = trim($row['marital_status'] ?? '');
            if (strtolower($maritalStatus) === 'married') {
                // For married people, marriage date is required
                if (empty($row['date_of_marriage'])) {
                    $this->addError([
                        'type' => 'validation_error',
                        'row' => $this->currentRowNumber,
                        'field' => 'date_of_marriage',
                        'error' => 'Date of marriage is required when marital status is Married',
                        'value' => $row['date_of_marriage'] ?? '',
                        'timestamp' => now()->toDateTimeString(),
                    ]);
                    $this->skippedCount++;
                    return null;
                }

                // For married people, number of children is required (can be 0)
                if (! isset($row['no_of_children']) || $row['no_of_children'] === '' || $row['no_of_children'] === null || ! is_numeric($row['no_of_children'])) {
                    $this->addError([
                        'type' => 'validation_error',
                        'row' => $this->currentRowNumber,
                        'field' => 'no_of_children',
                        'error' => 'Number of children is required when marital status is Married (can be 0)',
                        'value' => $row['no_of_children'] ?? '',
                        'timestamp' => now()->toDateTimeString(),
                    ]);
                    $this->skippedCount++;
                    return null;
                }

                // For married people, children in UCS is required (can be 0)
                if (! isset($row['children_in_ucs']) || $row['children_in_ucs'] === '' || $row['children_in_ucs'] === null || ! is_numeric($row['children_in_ucs'])) {
                    $this->addError([
                        'type' => 'validation_error',
                        'row' => $this->currentRowNumber,
                        'field' => 'children_in_ucs',
                        'error' => 'Children in UCS is required when marital status is Married (can be 0)',
                        'value' => $row['children_in_ucs'] ?? '',
                        'timestamp' => now()->toDateTimeString(),
                    ]);
                    $this->skippedCount++;
                    return null;
                }
            } else {
                // For single/unmarried people, marriage-related fields should be empty
                if (! empty($row['date_of_marriage'])) {
                    $this->addError([
                        'type' => 'validation_error',
                        'row' => $this->currentRowNumber,
                        'field' => 'date_of_marriage',
                        'error' => 'Date of marriage should be empty when marital status is not Married',
                        'value' => $row['date_of_marriage'],
                        'timestamp' => now()->toDateTimeString(),
                    ]);
                    $this->skippedCount++;
                    return null;
                }

                if (! empty($row['no_of_children']) && $row['no_of_children'] != 0) {
                    $this->addError([
                        'type' => 'validation_error',
                        'row' => $this->currentRowNumber,
                        'field' => 'no_of_children',
                        'error' => 'Number of children should be 0 or empty when marital status is not Married',
                        'value' => $row['no_of_children'],
                        'timestamp' => now()->toDateTimeString(),
                    ]);
                    $this->skippedCount++;
                    return null;
                }

                if (! empty($row['children_in_ucs']) && $row['children_in_ucs'] != 0) {
                    $this->addError([
                        'type' => 'validation_error',
                        'row' => $this->currentRowNumber,
                        'field' => 'children_in_ucs',
                        'error' => 'Children in UCS should be 0 or empty when marital status is not Married',
                        'value' => $row['children_in_ucs'],
                        'timestamp' => now()->toDateTimeString(),
                    ]);
                    $this->skippedCount++;
                    return null;
                }
            }

            // Date validation logic
            $hiringDate = $this->parseDate($row['hiring_date'] ?? null);
            $confirmDate = $this->parseDate($row['confirm_date'] ?? null);
            $regularDate = $this->parseDate($row['regular_date'] ?? null);
            $probationEndDate = $this->parseDate($row['probation_end_date'] ?? null);

            if ($hiringDate && $confirmDate && $confirmDate <= $hiringDate) {
                $this->addError([
                    'type' => 'validation_error',
                    'row' => $this->currentRowNumber,
                    'field' => 'confirm_date',
                    'error' => 'Confirm date must be after hiring date',
                    'value' => $row['confirm_date'] ?? '',
                    'timestamp' => now()->toDateTimeString(),
                ]);
                $this->skippedCount++;
                return null;
            }

            if ($hiringDate && $regularDate && $regularDate <= $hiringDate) {
                $this->addError([
                    'type' => 'validation_error',
                    'row' => $this->currentRowNumber,
                    'field' => 'regular_date',
                    'error' => 'Regular date must be after hiring date',
                    'value' => $row['regular_date'] ?? '',
                    'timestamp' => now()->toDateTimeString(),
                ]);
                $this->skippedCount++;
                return null;
            }

            if ($hiringDate && $probationEndDate && $probationEndDate <= $hiringDate) {
                $this->addError([
                    'type' => 'validation_error',
                    'row' => $this->currentRowNumber,
                    'field' => 'probation_end_date',
                    'error' => 'Probation end date must be after hiring date',
                    'value' => $row['probation_end_date'] ?? '',
                    'timestamp' => now()->toDateTimeString(),
                ]);
                $this->skippedCount++;
                return null;
            }

            // Conditional validation for passport fields
            $passportNumber = trim($row['passport_number'] ?? '');
            $issueDate = $row['issue_date'] ?? '';
            $expiryDate = $row['expiry_date'] ?? '';

            // If passport number is provided, issue_date and expiry_date are required
            if (! empty($passportNumber)) {
                if (empty($issueDate)) {
                    $this->addError([
                        'type' => 'validation_error',
                        'row' => $this->currentRowNumber,
                        'field' => 'issue_date',
                        'error' => 'Issue date is required when passport number is provided',
                        'value' => $issueDate,
                        'timestamp' => now()->toDateTimeString(),
                    ]);
                    $this->skippedCount++;
                    return null;
                }

                if (empty($expiryDate)) {
                    $this->addError([
                        'type' => 'validation_error',
                        'row' => $this->currentRowNumber,
                        'field' => 'expiry_date',
                        'error' => 'Expiry date is required when passport number is provided',
                        'value' => $expiryDate,
                        'timestamp' => now()->toDateTimeString(),
                    ]);
                    $this->skippedCount++;
                    return null;
                }
            }

            // If issue_date or expiry_date is provided, passport_number is required
            if (! empty($issueDate) || ! empty($expiryDate)) {
                if (empty($passportNumber)) {
                    $this->addError([
                        'type' => 'validation_error',
                        'row' => $this->currentRowNumber,
                        'field' => 'passport_number',
                        'error' => 'Passport number is required when issue date or expiry date is provided',
                        'value' => $passportNumber,
                        'timestamp' => now()->toDateTimeString(),
                    ]);
                    $this->skippedCount++;
                    return null;
                }
            }

            // Conditional validation for job status and probation fields
            $jobStatus = trim($row['job_status'] ?? '');
            $probationEndDate = $row['probation_end_date'] ?? '';
            $probationExtended = trim($row['probation_extended'] ?? '');

            // If job status is Regular, probation fields should be empty
            if (strtolower($jobStatus) === 'regular') {
                if (! empty($probationEndDate)) {
                    $this->addError([
                        'type' => 'validation_error',
                        'row' => $this->currentRowNumber,
                        'field' => 'probation_end_date',
                        'error' => 'Probation end date should be empty when job status is Regular',
                        'value' => $probationEndDate,
                        'timestamp' => now()->toDateTimeString(),
                    ]);
                    $this->skippedCount++;
                    return null;
                }

                if (! empty($probationExtended)) {
                    $this->addError([
                        'type' => 'validation_error',
                        'row' => $this->currentRowNumber,
                        'field' => 'probation_extended',
                        'error' => 'Probation extended should be empty when job status is Regular',
                        'value' => $probationExtended,
                        'timestamp' => now()->toDateTimeString(),
                    ]);
                    $this->skippedCount++;
                    return null;
                }
            }

            // If job status is Probation, probation fields are required
            if (strtolower($jobStatus) === 'probation') {
                if (empty($probationEndDate)) {
                    $this->addError([
                        'type' => 'validation_error',
                        'row' => $this->currentRowNumber,
                        'field' => 'probation_end_date',
                        'error' => 'Probation end date is required when job status is Probation',
                        'value' => $probationEndDate,
                        'timestamp' => now()->toDateTimeString(),
                    ]);
                    $this->skippedCount++;
                    return null;
                }

                if (empty($probationExtended)) {
                    $this->addError([
                        'type' => 'validation_error',
                        'row' => $this->currentRowNumber,
                        'field' => 'probation_extended',
                        'error' => 'Probation extended is required when job status is Probation',
                        'value' => $probationExtended,
                        'timestamp' => now()->toDateTimeString(),
                    ]);
                    $this->skippedCount++;
                    return null;
                }

                // Validate probation_extended values (case insensitive)
                if (! empty($probationExtended) && ! in_array(strtolower($probationExtended), ['yes', 'no'])) {
                    $this->addError([
                        'type' => 'validation_error',
                        'row' => $this->currentRowNumber,
                        'field' => 'probation_extended',
                        'error' => 'Probation extended must be Yes or No',
                        'value' => $probationExtended,
                        'timestamp' => now()->toDateTimeString(),
                    ]);
                    $this->skippedCount++;
                    return null;
                }
            }

            // Define variables for database operations
            $cnic = trim($row['cnic']);
            $email = strtolower(trim($row['email']));

            // Validate lookup fields exist
            $nationalityId = $this->getLookupId($this->nationalityCache, $row['nationality'] ?? '', 'nationality');
            $religionId = $this->getLookupId($this->religionCache, $row['religion'] ?? '', 'religion');
            $countryId = $this->getLookupId($this->countryCache, $row['country'] ?? '', 'country');
            $stateId = $this->getLookupId($this->stateCache, $row['state'] ?? '', 'state');
            $cityId = $this->getLookupId($this->cityCache, $row['city'] ?? '', 'city');
            $companyId = $this->getLookupId($this->companyCache, $row['company'] ?? '', 'company');
            $regionId = $this->getLookupId($this->regionCache, $row['region'] ?? '', 'region');
            $branchId = $this->getLookupId($this->branchCache, $row['branch'] ?? '', 'branch');
            $departmentId = $this->getLookupId($this->departmentCache, $row['department'] ?? '', 'department');
            $designationId = $this->getLookupId($this->designationCache, $row['designation'] ?? '', 'designation');

            // Log errors for missing lookup records
            if (! $nationalityId && ! empty($row['nationality'])) {
                $this->addError([
                    'type' => 'lookup_error',
                    'row' => $this->currentRowNumber,
                    'field' => 'nationality',
                    'error' => 'Nationality does not exist in the system',
                    'value' => $row['nationality'],
                    'timestamp' => now()->toDateTimeString(),
                ]);
            }

            if (! $religionId && ! empty($row['religion'])) {
                $this->addError([
                    'type' => 'lookup_error',
                    'row' => $this->currentRowNumber,
                    'field' => 'religion',
                    'error' => 'Religion does not exist in the system',
                    'value' => $row['religion'],
                    'timestamp' => now()->toDateTimeString(),
                ]);
            }

            if (! $countryId && ! empty($row['country'])) {
                $this->addError([
                    'type' => 'lookup_error',
                    'row' => $this->currentRowNumber,
                    'field' => 'country',
                    'error' => 'Country does not exist in the system',
                    'value' => $row['country'],
                    'timestamp' => now()->toDateTimeString(),
                ]);
            }

            if (! $stateId && ! empty($row['state'])) {
                $this->addError([
                    'type' => 'lookup_error',
                    'row' => $this->currentRowNumber,
                    'field' => 'state',
                    'error' => 'State does not exist in the system',
                    'value' => $row['state'],
                    'timestamp' => now()->toDateTimeString(),
                ]);
            }

            if (! $cityId && ! empty($row['city'])) {
                $this->addError([
                    'type' => 'lookup_error',
                    'row' => $this->currentRowNumber,
                    'field' => 'city',
                    'error' => 'City does not exist in the system',
                    'value' => $row['city'],
                    'timestamp' => now()->toDateTimeString(),
                ]);
            }

            if (! $companyId && ! empty($row['company'])) {
                $this->addError([
                    'type' => 'lookup_error',
                    'row' => $this->currentRowNumber,
                    'field' => 'company',
                    'error' => 'Company does not exist in the system',
                    'value' => $row['company'],
                    'timestamp' => now()->toDateTimeString(),
                ]);
            }

            if (! $regionId && ! empty($row['region'])) {
                $this->addError([
                    'type' => 'lookup_error',
                    'row' => $this->currentRowNumber,
                    'field' => 'region',
                    'error' => 'Region does not exist in the system',
                    'value' => $row['region'],
                    'timestamp' => now()->toDateTimeString(),
                ]);
            }

            if (! $branchId && ! empty($row['branch'])) {
                $this->addError([
                    'type' => 'lookup_error',
                    'row' => $this->currentRowNumber,
                    'field' => 'branch',
                    'error' => 'Branch does not exist in the system',
                    'value' => $row['branch'],
                    'timestamp' => now()->toDateTimeString(),
                ]);
            }

            if (! $departmentId && ! empty($row['department'])) {
                $this->addError([
                    'type' => 'lookup_error',
                    'row' => $this->currentRowNumber,
                    'field' => 'department',
                    'error' => 'Department does not exist in the system',
                    'value' => $row['department'],
                    'timestamp' => now()->toDateTimeString(),
                ]);
            }

            if (! $designationId && ! empty($row['designation'])) {
                $this->addError([
                    'type' => 'lookup_error',
                    'row' => $this->currentRowNumber,
                    'field' => 'designation',
                    'error' => 'Designation does not exist in the system',
                    'value' => $row['designation'],
                    'timestamp' => now()->toDateTimeString(),
                ]);
            }

            DB::beginTransaction();

            // Upsert User record (update if exists, create if not)
            $userData = [
                'name' => trim($row['first_name']) . ' ' . (trim($row['last_name'] ?? '')),
                'first_name' => trim($row['first_name']),
                'last_name' => trim($row['last_name'] ?? ''),
                'email' => $email,
                'CNIC' => $cnic,
                'gender' => trim($row['gender'] ?? ''),
                'date_of_birth' => $this->parseDate($row['date_of_birth'] ?? null),
            ];

            // Check if user exists
            $user = User::where('email', $email)->orWhere('CNIC', $cnic)->first();
            $isNewUser = ! $user;

            if ($user) {
                // Update existing user
                $user->update($userData);
            } else {
                // Create new user with password
                $userData['password'] = Hash::make($row['password'] ?? 'password123');
                $user = User::create($userData);
                $this->importedCount++;
            }

            // Create new Employee record
            $employeeData = [
                'user_id' => $user->id,
                'prefix' => trim($row['prefix'] ?? 'Mr'),
                'preferred_name' => trim($row['preferred_name'] ?? ''),
                'father_name' => trim($row['father_name'] ?? ''),
                'spouse_name' => trim($row['spouse_name'] ?? ''),
                // 'pin_code' => trim($row['pin_code'] ?? null),
                // 'card_no' => trim($row['card_no'] ?? null),
                'nationality_id' => $nationalityId,
                'religion_id' => $religionId,
                'cnic_expiry' => $this->parseDate($row['cnic_expiry'] ?? null),
                'marital_status' => trim($row['marital_status'] ?? ''),
                'date_of_marriage' => $this->parseDate($row['date_of_marriage'] ?? null),
                'no_of_children' => intval($row['no_of_children'] ?? 0),
                'children_in_ucs' => intval($row['children_in_ucs'] ?? 0),
                'country_id' => $countryId,
                'state_id' => $stateId,
                'city_id' => $cityId,
                'job_status' => trim($row['job_status'] ?? 'Regular'), // Default to 'Regular' if empty
                'hiring_date' => $this->parseDate($row['hiring_date'] ?? null),
                'confirm_date' => $this->parseDate($row['confirm_date'] ?? null),
                'regular_date' => $this->parseDate($row['regular_date'] ?? null),
                'left_date' => $this->parseDate($row['left_date'] ?? null),
                'probation_end_date' => $this->parseDate($row['probation_end_date'] ?? null),
                'probation_extended' => trim($row['probation_extended'] ?? ''),
                'eobi_number' => trim($row['eobi_number'] ?? ''),
                'ni_number' => trim($row['ni_number'] ?? ''),
                'mobile_number' => trim($row['mobile_number'] ?? ''),
                'passport_number' => trim($row['passport_number'] ?? ''),
                'crb' => trim($row['crb'] ?? ''),
                'issue_date' => $this->parseDate($row['issue_date'] ?? null),
                'ss_no' => trim($row['ss_no'] ?? ''),
                'expiry_date' => $this->parseDate($row['expiry_date'] ?? null),
                'previous_id' => trim($row['previous_id'] ?? ''),
                'company_id' => $companyId,
                'region_id' => $regionId,
                'branch_id' => $branchId,
                'department_id' => $departmentId,
                'designation_id' => $designationId,
                'date_of_birth' => $this->parseDate($row['date_of_birth'] ?? null),
                'address' => trim($row['address'] ?? ''),
                'emp_image' => 'user-dummy-img.jpg', // Default image
            ];

            // Get designation type ID if designation is provided
            if ($designationId) {
                $designationKey = strtolower(trim($row['designation']));
                if (isset($this->designationCache[$designationKey])) {
                    $employeeData['designation_type_id'] = $this->designationCache[$designationKey]['type_id'];
                }
            }

            if (! empty($row['pin_code'])) {
                $employeeData['pin_code'] = trim($row['pin_code']);
            }

            if (! empty($row['card_no'])) {
                $employeeData['card_no'] = trim($row['card_no']);
            }

            // Upsert employee (update if exists, create if not)
            $employee = Employee::where('user_id', $user->id)->first();

            if ($employee) {
                // Update existing employee
                $employee->update($employeeData);
                $this->skippedCount++;
            } else {
                // Generate employee ID for new employee
                $maxEmployeeId = Employee::max('employee_id');
                $employeeData['employee_id'] = $maxEmployeeId ? $maxEmployeeId + 1 : 1001;

                // Create new employee
                $employee = Employee::create($employeeData);

                // Assign leave quotas for new employees only
                if (! empty($employeeData['designation_id'])) {
                    $designationLeaveQuotas = DesignationLeaveQuota::where('designation_id', $employeeData['designation_id'])->get();

                    foreach ($designationLeaveQuotas as $quota) {
                        EmployeeLeaveQuota::updateOrCreate(
                            [
                                'employee_id' => $employee->id,
                                'leave_type_id' => $quota->leave_type_id,
                            ],
                            [
                                'designation_id' => $quota->designation_id,
                                'no_of_allowed_leaves' => $quota->no_of_allowed_leaves
                            ]
                        );
                    }
                }
            }

            // Update caches
            $this->existingEmailCache[$email] = true;
            $this->existingCnicCache[$cnic] = true;

            DB::commit();

            Log::info("Successfully imported employee: {$user->name} (ID: {$employee->id})");

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

            return $employee;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->skippedCount++;

            $errorMessage = "Error importing employee row: " . $e->getMessage();
            Log::error($errorMessage, [
                'row' => $row,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Add error to database
            $this->addError([
                'type' => 'import_error',
                'row' => $this->currentRowNumber,
                'field' => null,
                'error' => $errorMessage,
                'value' => 'Row data: ' . json_encode(array_slice($row, 0, 5)), // Show first 5 fields
                'timestamp' => now()->toDateTimeString(),
            ]);

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
            'last_name' => ['nullable', 'string', 'max:100'],
            'full_name' => ['nullable', 'string', 'max:200'],
            'email' => ['required', 'email', 'max:255'],
            'cnic' => ['required', 'string', 'max:15', 'regex:/^[0-9]{5}-[0-9]{7}-[0-9]$/'],
            'gender' => ['nullable', Rule::in(['Male', 'Female', 'male', 'female'])],
            'prefix' => ['nullable', Rule::in(['Mr', 'Mrs', 'Ms', 'mr', 'mrs', 'ms'])],
            'pin_code' => ['nullable', 'string', 'max:50'],
            'card_no' => ['nullable', 'string', 'max:50'],
            'mobile_number' => ['nullable', 'string', 'max:20', 'regex:/^[\+]?[0-9\s\-\(\)]{7,20}$/'],
            'date_of_birth' => ['nullable', function ($attribute, $value, $fail) {
                if (! empty($value) && ! $this->isValidDateString($value)) {
                    $fail('Date of birth must be a valid date format.');
                }
            }],
            'hiring_date' => ['nullable', function ($attribute, $value, $fail) {
                if (! empty($value) && ! $this->isValidDateString($value)) {
                    $fail('Hiring date must be a valid date format.');
                }
            }],
            'confirm_date' => ['nullable', function ($attribute, $value, $fail) {
                if (! empty($value) && ! $this->isValidDateString($value)) {
                    $fail('Confirm date must be a valid date format.');
                }
            }],
            'regular_date' => ['nullable', function ($attribute, $value, $fail) {
                if (! empty($value) && ! $this->isValidDateString($value)) {
                    $fail('Regular date must be a valid date format.');
                }
            }],
            'left_date' => ['nullable', function ($attribute, $value, $fail) {
                if (! empty($value) && ! $this->isValidDateString($value)) {
                    $fail('Left date must be a valid date format.');
                }
            }],
            'probation_end_date' => ['nullable', function ($attribute, $value, $fail) {
                if (! empty($value) && ! $this->isValidDateString($value)) {
                    $fail('Probation end date must be a valid date format.');
                }
            }],
            'cnic_expiry' => ['nullable', function ($attribute, $value, $fail) {
                if (! empty($value) && ! $this->isValidDateString($value)) {
                    $fail('CNIC expiry date must be a valid date format.');
                }
            }],
            'date_of_marriage' => ['nullable', function ($attribute, $value, $fail) {
                if (! empty($value) && ! $this->isValidDateString($value)) {
                    $fail('Date of marriage must be a valid date format.');
                }
            }],
            'issue_date' => ['nullable', function ($attribute, $value, $fail) {
                if (! empty($value) && ! $this->isValidDateString($value)) {
                    $fail('Issue date must be a valid date format.');
                }
            }],
            'expiry_date' => ['nullable', function ($attribute, $value, $fail) {
                if (! empty($value) && ! $this->isValidDateString($value)) {
                    $fail('Expiry date must be a valid date format.');
                }
            }],
            'no_of_children' => ['nullable', function ($attribute, $value, $fail) {
                // Allow '-' from export format, will be converted to null in model()
                if ($value === '-' || $value === null || $value === '') {
                    return;
                }
                if (! is_numeric($value) || (int)$value < 0) {
                    $fail('Number of children must be a whole number (0 or greater).');
                }
            }],
            'children_in_ucs' => ['nullable', function ($attribute, $value, $fail) {
                // Allow '-' from export format, will be converted to null in model()
                if ($value === '-' || $value === null || $value === '') {
                    return;
                }
                if (! is_numeric($value) || (int)$value < 0) {
                    $fail('Children in UCS must be a whole number (0 or greater).');
                }
            }],
            'marital_status' => ['nullable', Rule::in(['Single', 'Married', 'Divorced', 'Widowed', 'single', 'married', 'divorced', 'widowed', '', '-'])],
            'job_status' => ['nullable', Rule::in(['Probation', 'Regular', 'Left', 'Adhoc', 'Contractual', 'probation', 'regular', 'left', 'adhoc', 'contractual', '', '-'])],
            'probation_extended' => ['nullable', Rule::in(['Yes', 'No', 'yes', 'no'])],
            // Custom validation: either first_name or full_name must be provided
            'first_name' => ['nullable', 'string', 'max:100', function ($attribute, $value, $fail) {
                $fullName = request()->input('full_name');
                if (empty($value) && empty($fullName)) {
                    $fail('Either first name or full name must be provided.');
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
            'first_name.required' => 'First name is required.',
            'first_name.string' => 'First name must be a string.',
            'first_name.max' => 'First name cannot exceed 100 characters.',
            'last_name.string' => 'Last name must be a string.',
            'last_name.max' => 'Last name cannot exceed 100 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.max' => 'Email cannot exceed 255 characters.',
            'cnic.required' => 'CNIC is required.',
            'cnic.string' => 'CNIC must be a string.',
            'cnic.max' => 'CNIC cannot exceed 15 characters.',
            'cnic.regex' => 'CNIC must be in the format xxxxx-xxxxxxx-x.',
            'gender.in' => 'Gender must be Male or Female.',
            'prefix.in' => 'Prefix must be Mr, Mrs, or Ms.',
            'mobile_number.regex' => 'Mobile number must be a valid phone number format (e.g., +1234567890, (123) 456-7890, or 123-456-7890).',
            'marital_status.in' => 'Marital status must be Single, Married, Divorced, or Widowed.',
            'job_status.in' => 'Job status must be Probation, Regular, Left, Adhoc, or Contractual.',
            'probation_extended.in' => 'Probation extended must be Yes or No.',
            'no_of_children.integer' => 'Number of children must be a whole number.',
            'no_of_children.min' => 'Number of children cannot be negative.',
            'children_in_ucs.integer' => 'Children in UCS must be a whole number.',
            'children_in_ucs.min' => 'Children in UCS cannot be negative.',
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

        $errorMessage = "Error importing employee row: " . $e->getMessage();
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
                $problematicValue = "Check employee ID uniqueness";
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
                $problematicValue = "Check lookup data exists (company, branch, etc.)";
            } else {
                $cleanErrorMessage = "Database error occurred";
                $problematicValue = "Check data format and constraints";
            }
        } else {
            // For other errors, show the first few fields that might be problematic
            $cleanErrorMessage = "Error processing row data";
            $problematicValue = "Check all required fields";
        }

        // Add error to database
        $this->addError([
            'type' => 'import_error',
            'row' => $this->currentRowNumber,
            'field' => $fieldName,
            'error' => $cleanErrorMessage,
            'value' => $problematicValue,
            'timestamp' => now()->toDateTimeString(),
        ]);

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

            // Add error to database
            $this->addError([
                'type' => 'validation_error',
                'row' => $this->currentRowNumber, // Use custom counter instead of failure->row()
                'field' => $failure->attribute(),
                'error' => $errorMessage,
                'value' => $failure->values()[$failure->attribute()] ?? 'N/A',
                'timestamp' => now()->toDateTimeString(),
            ]);

            $this->errors[] = [
                'row' => $this->currentRowNumber, // Use custom counter instead of failure->row()
                'error' => $errorMessage
            ];
        }
    }
}
