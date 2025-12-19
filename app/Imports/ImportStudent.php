<?php

namespace App\Imports;

use App\Models\AcademicYear;
use App\Models\BranchAcademicYear;
use App\Models\BranchClass;
use App\Models\BranchClassSection;
use App\Models\ClassStudent;
use App\Models\ClassStudentSubject;
use App\Models\ClassSubject;
use App\Models\ComClass;
use App\Models\Employee;
use App\Models\Relation;
use App\Models\Section;
use App\Models\Student;
use App\Models\Guardian;
use App\Models\SiblingInformation;
use App\Models\Language;
use App\Models\Religion;
use App\Models\Nationality;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Branch;
use App\Models\StudentLedger;
use App\Models\StudentLedgerInvoice;
use App\Models\Town;
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

class ImportStudent implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading, SkipsOnError, SkipsEmptyRows, SkipsOnFailure
{
    use SkipsFailures;

    public $importedCount = 0;
    public $skippedCount = 0;
    public $errors = [];

    // Cache for lookup tables to avoid repeated database queries
    private $languageCache = [];
    private $religionCache = [];
    private $nationalityCache = [];
    private $countryCache = [];
    private $stateCache = [];
    private $cityCache = [];
    private $branchCache = [];
    private $academicYearCache = [];
    private $existingCnicCache = [];
    private $townCache = [];
    private $permanentCityCache = [];
    private $relationCache = [];
    private $employeeCache = [];
    private $comClassCache = [];
    private $sectionCache = [];

    public function __construct()
    {
        // Pre-load all lookup tables into memory for faster access
        $this->preloadLookupTables();
        // Clear the custom log file at the start of each import session
        $logPath = storage_path('logs/student_import.log');
        if (File::exists($logPath)) {
            File::put($logPath, '');
        }
    }



    /**
     * Pre-load all lookup tables to avoid repeated database queries
     */
    private function preloadLookupTables()
    {
        // Load languages
        $languages = Language::select('id', 'language_name')->get();
        foreach ($languages as $language) {
            $this->languageCache[strtolower(trim($language->language_name))] = $language->id;
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

        // Load branches
        $branches = Branch::select('id', 'br_name')->get();
        foreach ($branches as $branch) {
            $this->branchCache[strtolower(trim($branch->br_name))] = $branch->id;
        }

        // Load academic years
        $academicYears = AcademicYear::select('id', 'title')->get();
        foreach ($academicYears as $academicYear) {
            $this->academicYearCache[strtolower(trim($academicYear->title))] = $academicYear->id;
        }

        // Pre-load existing CNICs for faster duplicate checking
        $existingCnics = Student::whereNotNull('cnic')
            ->where('cnic', '!=', '')
            ->pluck('cnic')
            ->map(function ($cnic) {
                return preg_replace('/[\s\-]/', '', $cnic);
            })
            ->toArray();

        $this->existingCnicCache = array_flip($existingCnics);

        // Load towns
        $towns = Town::select('id', 'town_name')->get();
        foreach ($towns as $town) {
            $this->townCache[strtolower(trim($town->town_name))] = $town->id;
        }
        // Load permanent cities (for per_city)
        $permanentCities = City::select('id', 'city_name')->get();
        foreach ($permanentCities as $city) {
            $this->permanentCityCache[strtolower(trim($city->city_name))] = $city->id;
        }
        // Load relations
        $relations = Relation::select('id', 'relation_name')->get();
        foreach ($relations as $relation) {
            $this->relationCache[strtolower(trim($relation->relation_name))] = $relation->id;
        }
        // Load employees (by employee_no)
        $employees = Employee::select('employee_id')->get();
        foreach ($employees as $employee) {
            $this->employeeCache[strtolower(trim($employee->employee_id))] = $employee->employee_id;
        }
        // Load classes
        $comClasses = ComClass::select('id', 'class_name')->get();
        foreach ($comClasses as $comClass) {
            $this->comClassCache[strtolower(trim($comClass->class_name))] = $comClass->id;
        }
        // Load sections
        $sections = Section::select('id', 'section_name')->get();
        foreach ($sections as $section) {
            $this->sectionCache[strtolower(trim($section->section_name))] = $section->id;
        }
    }

    /**
     * Parse date from various formats (Excel numeric or readable string)
     */
    private function parseDate($value, $format = 'Y-m-d')
    {
        if (empty($value) || $value === 'NULL') {
            return null;
        }

        // If it's a numeric value (Excel date), convert using PhpSpreadsheet
        if (is_numeric($value)) {
            try {
                return Date::excelToDateTimeObject($value)->format($format);
            } catch (\Exception $e) {
                return null;
            }
        }

        // If it's a string, try to parse it as a date
        if (is_string($value)) {
            $value = trim($value);

            // Try common date formats
            $formats = [
                'Y-m-d',           // 2024-01-15
                'd/m/Y',           // 15/01/2024
                'm/d/Y',           // 01/15/2024
                'd-m-Y',           // 15-01-2024
                'm-d-Y',           // 01-15-2024
                'Y/m/d',           // 2024/01/15
                'd.m.Y',           // 15.01.2024
                'm.d.Y',           // 01.15.2024
                'Y.m.d',           // 2024.01.15
                'd/m/y',           // 15/01/24
                'm/d/y',           // 01/15/24
                'd-m-y',           // 15-01-24
                'm-d-y',           // 01-15-24
            ];

            foreach ($formats as $dateFormat) {
                try {
                    $date = Carbon::createFromFormat($dateFormat, $value);
                    if ($date !== false) {
                        return $date->format($format);
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }

            // Try Carbon's parse method for more flexible parsing
            try {
                $date = Carbon::parse($value);
                return $date->format($format);
            } catch (\Exception $e) {
                return null;
            }
        }

        return null;
    }

    /**
     * Parse datetime from various formats
     */
    private function parseDateTime($value, $format = 'Y-m-d H:i:s')
    {
        if (empty($value) || $value === 'NULL') {
            return null;
        }

        // If it's a numeric value (Excel datetime), convert using PhpSpreadsheet
        if (is_numeric($value)) {
            try {
                return Date::excelToDateTimeObject($value)->format($format);
            } catch (\Exception $e) {
                return null;
            }
        }

        // If it's a string, try to parse it as a datetime
        if (is_string($value)) {
            $value = trim($value);

            // Try common datetime formats
            $formats = [
                'Y-m-d H:i:s',     // 2024-01-15 14:30:00
                'Y-m-d H:i',       // 2024-01-15 14:30
                'd/m/Y H:i:s',     // 15/01/2024 14:30:00
                'd/m/Y H:i',       // 15/01/2024 14:30
                'm/d/Y H:i:s',     // 01/15/2024 14:30:00
                'm/d/Y H:i',       // 01/15/2024 14:30
                'd-m-Y H:i:s',     // 15-01-2024 14:30:00
                'd-m-Y H:i',       // 15-01-2024 14:30
                'm-d-Y H:i:s',     // 01-15-2024 14:30:00
                'm-d-Y H:i',       // 01-15-2024 14:30
            ];

            foreach ($formats as $dateFormat) {
                try {
                    $date = Carbon::createFromFormat($dateFormat, $value);
                    if ($date !== false) {
                        return $date->format($format);
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }

            // Try Carbon's parse method for more flexible parsing
            try {
                $date = Carbon::parse($value);
                return $date->format($format);
            } catch (\Exception $e) {
                return null;
            }
        }

        return null;
    }

    /**
     * Check if a string is a valid date
     */
    private function isValidDateString($value)
    {
        if (empty($value) || ! is_string($value)) {
            return false;
        }

        $value = trim($value);

        // Try common date formats
        $formats = [
            'Y-m-d',           // 2024-01-15
            'd/m/Y',           // 15/01/2024
            'm/d/Y',           // 01/15/2024
            'd-m-Y',           // 15-01-2024
            'm-d-Y',           // 01-15-2024
            'Y/m/d',           // 2024/01/15
            'd.m.Y',           // 15.01.2024
            'm.d.Y',           // 01.15.2024
            'Y.m.d',           // 2024.01.15
            'd/m/y',           // 15/01/24
            'm/d/y',           // 01/15/24
            'd-m-y',           // 15-01-24
            'm-d-y',           // 01-15-24
        ];

        foreach ($formats as $dateFormat) {
            try {
                $date = Carbon::createFromFormat($dateFormat, $value);
                if ($date !== false) {
                    return true;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        // Try Carbon's parse method for more flexible parsing
        try {
            $date = Carbon::parse($value);
            return $date !== false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if a string is a valid datetime
     */
    private function isValidDateTimeString($value)
    {
        if (empty($value) || ! is_string($value)) {
            return false;
        }

        $value = trim($value);

        // Try common datetime formats
        $formats = [
            'Y-m-d H:i:s',     // 2024-01-15 14:30:00
            'Y-m-d H:i',       // 2024-01-15 14:30
            'd/m/Y H:i:s',     // 15/01/2024 14:30:00
            'd/m/Y H:i',       // 15/01/2024 14:30
            'm/d/Y H:i:s',     // 01/15/2024 14:30:00
            'm/d/Y H:i',       // 01/15/2024 14:30
            'd-m-Y H:i:s',     // 15-01-2024 14:30:00
            'd-m-Y H:i',       // 15-01-2024 14:30
            'm-d-Y H:i:s',     // 01-15-2024 14:30:00
            'm-d-Y H:i',       // 01-15-2024 14:30
        ];

        foreach ($formats as $dateFormat) {
            try {
                $date = Carbon::createFromFormat($dateFormat, $value);
                if ($date !== false) {
                    return true;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        // Try Carbon's parse method for more flexible parsing
        try {
            $date = Carbon::parse($value);
            return $date !== false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function model(array $row)
    {
        try {
            // Check if student already exists by CNIC (using cached data)
            if (! empty($row['cnic']) && $row['cnic'] !== 'NULL') {
                // Normalize CNIC format for comparison (remove dashes and spaces)
                $normalizedCnic = preg_replace('/[\s\-]/', '', $row['cnic']);

                // Check if CNIC exists in cache
                if (isset($this->existingCnicCache[$normalizedCnic])) {
                    $this->skippedCount++;
                    return null; // Skip this row
                }
            }

            // Get IDs from cache instead of database queries
            $languageId = $this->languageCache[strtolower(trim($row['language'] ?? ''))] ?? null;
            $religionId = $this->religionCache[strtolower(trim($row['religion'] ?? ''))] ?? null;
            $nationalityId = $this->nationalityCache[strtolower(trim($row['nationality'] ?? ''))] ?? null;
            $countryId = $this->countryCache[strtolower(trim($row['country'] ?? ''))] ?? null;
            $stateId = $this->stateCache[strtolower(trim($row['state'] ?? ''))] ?? null;
            $cityId = $this->cityCache[strtolower(trim($row['city'] ?? ''))] ?? null;
            $branchId = $this->branchCache[strtolower(trim($row['branch'] ?? ''))] ?? null;
            $academicYearId = $this->academicYearCache[strtolower(trim($row['admission_year'] ?? ''))] ?? null;

            // Validate that all required records exist
            $errors = [];
            // For each lookup field, log a custom_error if not found
            if (! $languageId) {
                $msg = 'Language name does not exist.';
                $logEntry = [
                    'type' => 'custom_error',
                    'row' => $row['row_number'] ?? null,
                    'field' => 'language',
                    'errors' => [$msg],
                    'value' => $row['language'] ?? null,
                    'trace' => null,
                    'timestamp' => now()->toDateTimeString(),
                ];
                $this->errors[] = $logEntry;
                File::append(storage_path('logs/student_import.log'), json_encode($logEntry) . PHP_EOL);
            }
            if (! $religionId) {
                $msg = 'Religion name does not exist.';
                $logEntry = [
                    'type' => 'custom_error',
                    'row' => $row['row_number'] ?? null,
                    'field' => 'religion',
                    'errors' => [$msg],
                    'value' => $row['religion'] ?? null,
                    'trace' => null,
                    'timestamp' => now()->toDateTimeString(),
                ];
                $this->errors[] = $logEntry;
                File::append(storage_path('logs/student_import.log'), json_encode($logEntry) . PHP_EOL);
            }
            if (! $nationalityId) {
                $msg = 'Nationality name does not exist.';
                $logEntry = [
                    'type' => 'custom_error',
                    'row' => $row['row_number'] ?? null,
                    'field' => 'nationality',
                    'errors' => [$msg],
                    'value' => $row['nationality'] ?? null,
                    'trace' => null,
                    'timestamp' => now()->toDateTimeString(),
                ];
                $this->errors[] = $logEntry;
                File::append(storage_path('logs/student_import.log'), json_encode($logEntry) . PHP_EOL);
            }
            if (! $countryId) {
                $msg = 'Country name does not exist.';
                $logEntry = [
                    'type' => 'custom_error',
                    'row' => $row['row_number'] ?? null,
                    'field' => 'country',
                    'errors' => [$msg],
                    'value' => $row['country'] ?? null,
                    'trace' => null,
                    'timestamp' => now()->toDateTimeString(),
                ];
                $this->errors[] = $logEntry;
                File::append(storage_path('logs/student_import.log'), json_encode($logEntry) . PHP_EOL);
            }
            if (! $stateId) {
                $msg = 'State name does not exist.';
                $logEntry = [
                    'type' => 'custom_error',
                    'row' => $row['row_number'] ?? null,
                    'field' => 'state',
                    'errors' => [$msg],
                    'value' => $row['state'] ?? null,
                    'trace' => null,
                    'timestamp' => now()->toDateTimeString(),
                ];
                $this->errors[] = $logEntry;
                File::append(storage_path('logs/student_import.log'), json_encode($logEntry) . PHP_EOL);
            }
            if (! $cityId) {
                $msg = 'City name does not exist.';
                $logEntry = [
                    'type' => 'custom_error',
                    'row' => $row['row_number'] ?? null,
                    'field' => 'city',
                    'errors' => [$msg],
                    'value' => $row['city'] ?? null,
                    'trace' => null,
                    'timestamp' => now()->toDateTimeString(),
                ];
                $this->errors[] = $logEntry;
                File::append(storage_path('logs/student_import.log'), json_encode($logEntry) . PHP_EOL);
            }
            if (! $branchId) {
                $msg = 'Branch name does not exist.';
                $logEntry = [
                    'type' => 'custom_error',
                    'row' => $row['row_number'] ?? null,
                    'field' => 'branch',
                    'errors' => [$msg],
                    'value' => $row['branch'] ?? null,
                    'trace' => null,
                    'timestamp' => now()->toDateTimeString(),
                ];
                $this->errors[] = $logEntry;
                File::append(storage_path('logs/student_import.log'), json_encode($logEntry) . PHP_EOL);
            }
            if (! $academicYearId) {
                $msg = 'Academic year does not exist.';
                $logEntry = [
                    'type' => 'custom_error',
                    'row' => $row['row_number'] ?? null,
                    'field' => 'admission_year',
                    'errors' => [$msg],
                    'value' => $row['admission_year'] ?? null,
                    'trace' => null,
                    'timestamp' => now()->toDateTimeString(),
                ];
                $this->errors[] = $logEntry;
                File::append(storage_path('logs/student_import.log'), json_encode($logEntry) . PHP_EOL);
            }

            if (! empty($errors)) {
                $logEntry = [
                    'type' => 'validation',
                    'row' => $row['row_number'] ?? null,
                    'field' => null,
                    'errors' => $errors,
                    'value' => null,
                    'trace' => null,
                    'timestamp' => now()->toDateTimeString(),
                ];
                $this->errors[] = $logEntry;
                File::append(storage_path('logs/student_import.log'), json_encode($logEntry) . PHP_EOL);
                throw ValidationException::withMessages($errors);
            }

            $this->importedCount++;

            // Use bulk insert for better performance
            $studentData = [
                'first_name' => $row['first_name'],
                'middle_name' => $row['middle_name'] ?? null,
                'last_name' => $row['last_name'],
                'gender' => $row['gender'],
                'email' => $row['email'] ?? null,
                'date_of_birth' => $this->parseDate($row['dob']),
                'admission_wef' => $this->parseDate($row['admission_wef']),
                'registration_date' => $this->parseDate($row['registration_date']),
                'passport_number' => $row['passport_number'] ?? null,
                'birth_place' => $row['birth_place'] ?? null,
                'status' => $row['status'] ?? 'processing',
                'transfer_status' => $row['transfer_status'] ?? null,
                'language_id' => $languageId,
                'religion_id' => $religionId,
                'nationality_id' => $nationalityId,
                'country_id' => $countryId,
                'state_id' => $stateId,
                'city_id' => $cityId,
                'branch_id' => $branchId,
                'security_deposit' => $row['security_deposit'] ?? null,
                'security_amount' => $row['security_amount'] ?? null,
                'security_number' => $row['security_number'] === 'NULL' ? null : $row['security_number'],
                'admission_year_id' => $academicYearId,
                'registration_fee' => $row['registration_fee'] ?? null,
                'test_date_time' => $this->parseDateTime($row['test_date_time']),
                'interview_date_time' => $this->parseDateTime($row['interview_date_time']),
                'student_image' => $row['student_image'] === 'NULL' ? null : $row['student_image'],
                'cnic' => $row['cnic'] === 'NULL' ? null : $row['cnic'],
                'emergency_phone_number' => $row['emergency_phone_number'] === 'NULL' ? null : $row['emergency_phone_number'],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert and get the ID
            $studentId = DB::table('students')->insertGetId($studentData);
            $student = Student::find($studentId);

            // Address import logic (after student is created)
            $resCountryId = $this->countryCache[strtolower(trim($row['res_country'] ?? ''))] ?? null;
            $resStateId = $this->stateCache[strtolower(trim($row['res_state'] ?? ''))] ?? null;
            $resCityId = $this->cityCache[strtolower(trim($row['res_city'] ?? ''))] ?? null;
            $resTownId = $this->townCache[strtolower(trim($row['town'] ?? ''))] ?? null;
            $perCityId = $this->permanentCityCache[strtolower(trim($row['per_city'] ?? ''))] ?? null;

            // Check if we have the minimum required fields for address creation
            if ($resCountryId && $resStateId && $resCityId && $resTownId && $perCityId) {
                $student->student_address()->create([
                    'res_country_id' => $resCountryId,
                    'res_state_id' => $resStateId,
                    'res_city_id' => $resCityId,
                    'res_town_id' => $resTownId,
                    'res_postal_code' => $row['postal_code'] ?? null,
                    'res_contact_person' => $row['contact_person'] ?? null,
                    'res_phone' => $row['res_phone'] ?? null,
                    'res_sms_number' => $row['res_sms_number'] ?? null,
                    'res_mobile' => $row['residential_mobile'] ?? null,
                    'street_address' => $row['res_street_address'] ?? null,
                    'per_city_id' => $perCityId,
                    'per_phone' => $row['per_phone'] ?? null,
                    'per_postal_code' => $row['per_postal_code'] ?? null,
                    'per_address' => $row['per_address'] ?? null,
                ]);
            } else {
                // Log missing fields for debugging
                $missingFields = [];
                if (! $resCountryId) {
                    $missingFields[] = 'res_country';
                }
                if (! $resStateId) {
                    $missingFields[] = 'res_state';
                }
                if (! $resCityId) {
                    $missingFields[] = 'res_city';
                }
                if (! $resTownId) {
                    $missingFields[] = 'town';
                }
                if (! $perCityId) {
                    $missingFields[] = 'per_city';
                }

                $msg = 'Address not created for student: ' . ($student->first_name ?? '') . '. Missing required fields: ' . implode(', ', $missingFields);
                $this->errors[] = $msg;
                $logEntry = [
                    'type' => 'error',
                    'row' => $row['row_number'] ?? null,
                    'field' => null,
                    'errors' => [$msg],
                    'value' => null,
                    'trace' => null,
                    'timestamp' => now()->toDateTimeString(),
                ];
                File::append(storage_path('logs/student_import.log'), json_encode($logEntry) . PHP_EOL);
            }
            // Guardian import logic (after student and address are created)
            $relationId = $this->relationCache[strtolower(trim($row['relation'] ?? ''))] ?? null;
            $snsEmployee = strtolower(trim($row['sns_employee'] ?? 'no'));
            $employeeNo = $row['employee_no'] ?? null;
            $employeeNoValidated = null;
            if ($snsEmployee === 'yes') {
                if (! $employeeNo || ! isset($this->employeeCache[strtolower(trim($employeeNo))])) {
                    // If employee_no is required but not found, skip guardian creation
                    $msg = 'Employee number required and must exist for SNS employee for student: ' . ($student->first_name ?? '');
                    $this->errors[] = $msg;
                    $logEntry = [
                        'type' => 'error',
                        'row' => $row['row_number'] ?? null,
                        'field' => null,
                        'errors' => [$msg],
                        'value' => null,
                        'trace' => null,
                        'timestamp' => now()->toDateTimeString(),
                    ];
                    File::append(storage_path('logs/student_import.log'), json_encode($logEntry) . PHP_EOL);
                } else {
                    $employeeNoValidated = $this->employeeCache[strtolower(trim($employeeNo))];
                }
            }
            if ($relationId && $row['guardian_name']) {
                $guardianCNIC = $row['guardian_cnic'] ?? null;

                // Check if guardian with same CNIC already exists for this student
                $existingGuardian = $student->guardians()
                    ->where('CNIC', $guardianCNIC)
                    ->first();

                if ($existingGuardian) {
                    // Guardian already exists, skip creation but still process sibling logic
                    $guardian = $existingGuardian;
                } else {
                    // Create new guardian only if it doesn't exist
                    $guardianData = [
                        'guardian_name' => $row['guardian_name'],
                        'relation_id' => $relationId,
                        'CNIC' => $guardianCNIC,
                        'mobile' => $row['guardian_mobile'] ?? null,
                        'email' => $row['guardian_email'] ?? null,
                        'student_id' => $student->id,
                        'is_parent' => $snsEmployee === 'yes' ? 'yes' : 'no',
                        'employee_no' => $employeeNoValidated,
                    ];
                    $guardian = $student->guardians()->create($guardianData);
                }

                // --- Sibling/Family logic (mimic GuardianController@store) ---
                if ($guardian && $guardianCNIC) {
                    // Check if student already has a sibling relationship
                    $existingSiblingInfo = SiblingInformation::where('student_id', $student->id)->first();

                    if (! $existingSiblingInfo) {
                        // Find other students with the same guardian CNIC (excluding current student)
                        $siblings = Student::with('first_guardian.family.children')
                            ->where('id', '!=', $student->id) // Exclude current student
                            ->whereHas('first_guardian', function ($query) use ($guardianCNIC) {
                                $query->where('CNIC', $guardianCNIC);
                                $query->whereHas('family');
                            })
                            ->oldest('created_at')
                            ->get();

                        $no_of_siblings = $siblings->count();

                        if ($no_of_siblings > 0) {
                            // Link to existing family
                            $firstSibling = $siblings->first();
                            if ($firstSibling->first_guardian && $firstSibling->first_guardian->family) {
                                $existingFamily = $firstSibling->first_guardian->family;
                                $sibling_no = $existingFamily->children()->count() + 1;

                                // Check if this student is already in this family
                                $alreadyInFamily = $existingFamily->children()
                                    ->where('student_id', $student->id)
                                    ->exists();

                                if (! $alreadyInFamily) {
                                    $existingFamily->children()->create([
                                        'student_id' => $student->id,
                                        'sibling_no' => $sibling_no
                                    ]);
                                }
                            }
                        } else {
                            // Check if guardian already has a family
                            $existingFamily = $guardian->family;

                            if (! $existingFamily) {
                                // Create new family only if it doesn't exist
                                $family = $guardian->family()->create([
                                    'family_no' => rand(100000, 999999),
                                    'CNIC' => $guardianCNIC
                                ]);

                                // Check if student is already in this family (shouldn't happen, but safety check)
                                $alreadyInFamily = $family->children()
                                    ->where('student_id', $student->id)
                                    ->exists();

                                if (! $alreadyInFamily) {
                                    $family->children()->create([
                                        'student_id' => $student->id,
                                        'sibling_no' => 1
                                    ]);
                                }
                            } else {
                                // Guardian has family but student is not linked - add student to existing family
                                $alreadyInFamily = $existingFamily->children()
                                    ->where('student_id', $student->id)
                                    ->exists();

                                if (! $alreadyInFamily) {
                                    $sibling_no = $existingFamily->children()->count() + 1;
                                    $existingFamily->children()->create([
                                        'student_id' => $student->id,
                                        'sibling_no' => $sibling_no
                                    ]);
                                }
                            }
                        }
                    }
                }
            }

            // Class/Section/Academic Year assignment logic
            $branchId = $student->branch_id;
            $academicYearId = $this->academicYearCache[strtolower(trim($row['academic_year'] ?? ''))] ?? null;
            $classId = $this->comClassCache[strtolower(trim($row['class'] ?? ''))] ?? null;
            $sectionId = $this->sectionCache[strtolower(trim($row['section'] ?? ''))] ?? null;

            $branchAcademicYear = null;
            $branchClass = null;
            $branchClassSection = null;
            $missingAcademicLinks = [];

            if ($branchId && $academicYearId) {
                $branchAcademicYear = BranchAcademicYear::where([
                    'branch_id' => $branchId,
                    'academic_year_id' => $academicYearId
                ])->first();
                if (! $branchAcademicYear) {
                    $missingAcademicLinks[] = 'branch_academic_year';
                }
            } else {
                if (! $branchId) {
                    $missingAcademicLinks[] = 'branch_id';
                }
                if (! $academicYearId) {
                    $missingAcademicLinks[] = 'academic_year_id';
                }
            }
            if ($branchId && $classId) {
                $branchClass = BranchClass::where([
                    'branch_id' => $branchId,
                    'class_id' => $classId
                ])->first();
                if (! $branchClass) {
                    $msg = 'Class does not exist.';
                    $logEntry = [
                        'type' => 'custom_error',
                        'row' => $row['row_number'] ?? null,
                        'field' => 'class',
                        'errors' => [$msg],
                        'value' => $row['class'] ?? null,
                        'trace' => null,
                        'timestamp' => now()->toDateTimeString(),
                    ];
                    $this->errors[] = $logEntry;
                    File::append(storage_path('logs/student_import.log'), json_encode($logEntry) . PHP_EOL);
                }
            } else {
                if (! $classId) {
                    $missingAcademicLinks[] = 'class_id';
                }
            }
            if ($branchId && $classId && $sectionId) {
                $branchClassSection = BranchClassSection::where([
                    'branch_id' => $branchId,
                    'class_id' => $classId,
                    'section_id' => $sectionId
                ])->first();
                if (! $branchClassSection) {
                    $msg = 'Section does not exist.';
                    $logEntry = [
                        'type' => 'custom_error',
                        'row' => $row['row_number'] ?? null,
                        'field' => 'section',
                        'errors' => [$msg],
                        'value' => $row['section'] ?? null,
                        'trace' => null,
                        'timestamp' => now()->toDateTimeString(),
                    ];
                    $this->errors[] = $logEntry;
                    File::append(storage_path('logs/student_import.log'), json_encode($logEntry) . PHP_EOL);
                }
            } else {
                if (! $sectionId) {
                    $missingAcademicLinks[] = 'section_id';
                }
            }
            if ($branchAcademicYear && $branchClass && $branchClassSection) {
                // Mark previous ClassStudent records as inactive
                ClassStudent::where([
                    'student_id' => $student->id,
                    'is_valid' => 1
                ])->update([
                    'is_valid' => 0,
                    'active_till' => now()
                ]);

                // Create new ClassStudent record
                $classStudent = ClassStudent::create([
                    'academic_year_id' => $academicYearId,
                    'branch_class_section_id' => $branchClassSection->id,
                    'student_id' => $student->id,
                    'is_valid' => 1
                ]);

                // Class Subjects
                $classSubjects = ClassSubject::where('class_id', $classId)->where(function ($query) use ($student) {
                    $query->where('branch_id', $student->branch_id);
                    $query->orWhere('branch_id', null);
                })->get()->pluck('subject_id');

                foreach ($classSubjects as $subject) {
                    ClassStudentSubject::create([
                        'class_student_id' => $classStudent->id,
                        'subject_id' => $subject,
                        'is_valid' => 1
                    ]);
                }

                // Student Ledger
                $studentLedger = StudentLedger::create([
                    'student_id' => $student->id,
                    'class_student_id' => $classStudent->id
                ]);

                // Student Ledger Invoice empty record
                $academicStartDate = Carbon::parse($branchAcademicYear->start_date)->format('m');
                StudentLedgerInvoice::create_empty_record($studentLedger['id'], $academicStartDate);
            } else {
                $msg = 'Academic info not created for student: ' . ($student->first_name ?? '') . '. Missing: ' . implode(', ', $missingAcademicLinks);
                $this->errors[] = $msg;
                $logEntry = [
                    'type' => 'error',
                    'row' => $row['row_number'] ?? null,
                    'field' => null,
                    'errors' => [$msg],
                    'value' => null,
                    'trace' => null,
                    'timestamp' => now()->toDateTimeString(),
                ];
                File::append(storage_path('logs/student_import.log'), json_encode($logEntry) . PHP_EOL);
            }
            return $student;
        } catch (ValidationException $ve) {
            $logEntry = [
                'type' => 'validation',
                'row' => $row['row_number'] ?? null,
                'field' => null,
                'errors' => $ve->errors(),
                'value' => null,
                'trace' => $ve->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ];
            $this->errors[] = $logEntry;
            File::append(storage_path('logs/student_import.log'), json_encode($logEntry) . PHP_EOL);
            return null;
        } catch (QueryException $qe) {
            $logEntry = [
                'type' => 'db_error',
                'row' => $row['row_number'] ?? null,
                'field' => null,
                'errors' => ['A database error occurred. Please check your data for missing or invalid values.'],
                'value' => null,
                'trace' => null,
                'timestamp' => now()->toDateTimeString(),
            ];
            $this->errors[] = $logEntry;
            File::append(storage_path('logs/student_import.log'), json_encode($logEntry) . PHP_EOL);
            // Log full error internally
            \Log::error('Student import DB error', ['row' => $row, 'error' => $qe->getMessage(), 'trace' => $qe->getTraceAsString()]);
            return null;
        } catch (PDOException $pe) {
            $logEntry = [
                'type' => 'db_error',
                'row' => $row['row_number'] ?? null,
                'field' => null,
                'errors' => ['A database error occurred. Please check your data for missing or invalid values.'],
                'value' => null,
                'trace' => null,
                'timestamp' => now()->toDateTimeString(),
            ];
            $this->errors[] = $logEntry;
            File::append(storage_path('logs/student_import.log'), json_encode($logEntry) . PHP_EOL);
            // Log full error internally
            \Log::error('Student import PDO error', ['row' => $row, 'error' => $pe->getMessage(), 'trace' => $pe->getTraceAsString()]);
            return null;
        } catch (\Exception $e) {
            $logEntry = [
                'type' => 'exception',
                'row' => $row['row_number'] ?? null,
                'field' => null,
                'errors' => ['An exception occurred. Please check your data for missing or invalid values.'],
                'value' => null,
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ];
            $this->errors[] = $logEntry;
            //File::append(storage_path('logs/student_import.log'), json_encode($logEntry) . PHP_EOL);
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'gender' => ['required', Rule::in(['Male', 'Female', 'male', 'female'])],
            'email' => [
                'nullable',
                'email',
                'max:255',
            ],
            'dob' => ['required', function ($attribute, $value, $fail) {
                if (empty($value) || $value === 'NULL') {
                    $fail('Date of birth is required.');
                    return;
                }

                // Check if it's a valid date (numeric Excel format or readable string)
                if (! is_numeric($value) && ! $this->isValidDateString($value)) {
                    $fail('Date of birth must be a valid date format (e.g., 2024-01-15, 15/01/2024, or Excel date number).');
                }
            }],
            'admission_wef' => ['required', function ($attribute, $value, $fail) {
                if (empty($value) || $value === 'NULL') {
                    $fail('Admission start date is required.');
                    return;
                }

                // Check if it's a valid date (numeric Excel format or readable string)
                if (! is_numeric($value) && ! $this->isValidDateString($value)) {
                    $fail('Admission start date must be a valid date format (e.g., 2024-01-15, 15/01/2024, or Excel date number).');
                }
            }],
            'registration_date' => ['required', function ($attribute, $value, $fail) {
                if (empty($value) || $value === 'NULL') {
                    $fail('Registration date is required.');
                    return;
                }

                // Check if it's a valid date (numeric Excel format or readable string)
                if (! is_numeric($value) && ! $this->isValidDateString($value)) {
                    $fail('Registration date must be a valid date format (e.g., 2024-01-15, 15/01/2024, or Excel date number).');
                }
            }],
            'passport_number' => ['nullable', 'string', 'regex:/^[A-Z0-9]{6,15}$/', 'max:15'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(['on_roll', 'registered', 'left', 'pass-out', 'Processing', 'on_roll', 'registered', 'left', 'pass-out', 'processing'])],
            'transfer_status' => ['nullable', 'string', 'max:255'],
            'language' => ['required', 'string'],
            'religion' => ['required', 'string'],
            'nationality' => ['required', 'string'],
            'country' => ['required', 'string'],
            'state' => ['required', 'string'],
            'city' => ['required', 'string'],
            'branch' => ['required', 'string'],
            'security_deposit' => ['nullable', 'numeric', 'min:0'],
            'security_amount' => ['nullable', 'numeric', 'min:0'],
            'security_number' => ['nullable', 'string', 'max:50'],
            'admission_year' => ['required', 'string'],
            'registration_fee' => ['nullable', 'numeric', 'min:0'],
            'test_date_time' => ['nullable', function ($attribute, $value, $fail) {
                if (! empty($value) && $value !== 'NULL') {
                    // Check if it's a valid datetime (numeric Excel format or readable string)
                    if (! is_numeric($value) && ! $this->isValidDateTimeString($value)) {
                        $fail('Test date/time must be a valid datetime format (e.g., 2024-01-15 14:30, 15/01/2024 14:30, or Excel datetime number).');
                    }
                }
            }],
            'interview_date_time' => ['nullable', function ($attribute, $value, $fail) {
                if (! empty($value) && $value !== 'NULL') {
                    // Check if it's a valid datetime (numeric Excel format or readable string)
                    if (! is_numeric($value) && ! $this->isValidDateTimeString($value)) {
                        $fail('Interview date/time must be a valid datetime format (e.g., 2024-01-15 14:30, 15/01/2024 14:30, or Excel datetime number).');
                    }
                }
            }],
            'student_image' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (! empty($value)) {
                        // Sanitize and validate the URL to prevent XSS attacks
                        $cleanedValue = trim($value);

                        // Check for dangerous protocols
                        $dangerousProtocols = [
                            'javascript:',
                            'data:',
                            'vbscript:',
                            'file:',
                            'ftp:',
                            'mailto:',
                            'tel:',
                            'sms:',
                            'about:'
                        ];

                        $lowerValue = strtolower($cleanedValue);
                        foreach ($dangerousProtocols as $protocol) {
                            if (str_starts_with($lowerValue, $protocol)) {
                                $fail('Student image must be a valid HTTP/HTTPS image URL only.');
                                return;
                            }
                        }

                        // Only allow HTTP/HTTPS URLs or relative paths
                        if (! preg_match('/^(https?:\/\/|\/)/i', $cleanedValue)) {
                            $fail('Student image must start with http://, https://, or be a relative path starting with /.');
                            return;
                        }

                        // If it's a full URL, validate it further
                        if (preg_match('/^https?:\/\//i', $cleanedValue)) {
                            // Validate URL format
                            if (! filter_var($cleanedValue, FILTER_VALIDATE_URL)) {
                                $fail('Student image must be a valid URL.');
                                return;
                            }

                            // Check if URL ends with common image extensions
                            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];
                            $urlPath = parse_url($cleanedValue, PHP_URL_PATH);
                            $extension = strtolower(pathinfo($urlPath, PATHINFO_EXTENSION));

                            if (! in_array($extension, $allowedExtensions)) {
                                $fail('Student image must be a valid image file with extension: ' . implode(', ', $allowedExtensions) . '.');
                                return;
                            }
                        }

                        // Additional security check for encoded malicious content
                        $decodedValue = urldecode($cleanedValue);
                        $lowerDecodedValue = strtolower($decodedValue);
                        foreach ($dangerousProtocols as $protocol) {
                            if (str_contains($lowerDecodedValue, $protocol)) {
                                $fail('Student image contains potentially malicious content.');
                                return;
                            }
                        }

                        // Check for script tags or event handlers
                        $maliciousPatterns = [
                            '<script',
                            '</script>',
                            'onload',
                            'onerror',
                            'onclick',
                            'onmouseover',
                            'onfocus',
                            'onblur',
                            'onchange',
                            'onsubmit',
                            'eval(',
                            'expression(',
                            'alert(',
                            'confirm(',
                            'prompt(',
                        ];

                        foreach ($maliciousPatterns as $pattern) {
                            if (str_contains($lowerDecodedValue, $pattern)) {
                                $fail('Student image contains potentially malicious script content.');
                                return;
                            }
                        }
                    }
                }
            ],
            'cnic' => [
                'nullable',
                'max:15', // Accommodate formatted CNIC (xxxxx-xxxxxxx-x = 15 chars)
                function ($attribute, $value, $fail) {
                    // If CNIC is empty, check if passport_number is provided
                    if (empty($value) && empty(request()->input('passport_number'))) {
                        $fail('Either CNIC, Passport number, or Smart Card is required.');
                    }

                    // Validate CNIC format (with or without dashes)
                    if (! empty($value)) {
                        // Remove dashes to validate the numeric part
                        $cleanedCnic = str_replace('-', '', $value);

                        // Check if it contains only digits
                        if (! preg_match('/^[0-9]+$/', $cleanedCnic)) {
                            $fail('CNIC must contain only digits and dashes.');
                        }

                        // Check if it's exactly 13 digits
                        if (strlen($cleanedCnic) !== 13) {
                            $fail('CNIC must be exactly 13 digits. Format: xxxxx-xxxxxxx-x or 13 consecutive digits.');
                        }

                        // If dashes are present, validate the exact format
                        if (strpos($value, '-') !== false) {
                            if (! preg_match('/^[0-9]{5}-[0-9]{7}-[0-9]{1}$/', $value)) {
                                $fail('CNIC with dashes must follow the format: xxxxx-xxxxxxx-x (5-7-1 digit pattern).');
                            }
                        }
                    }
                }
            ],
            'emergency_phone_number' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[\+]?[0-9\s\-\(\)]{7,20}$/',
                function ($attribute, $value, $fail) {
                    if (! empty($value)) {
                        // Remove common phone number formatting characters
                        $cleanedPhone = preg_replace('/[\s\-\(\)]/', '', $value);

                        // Check if it starts with + for international format
                        if (str_starts_with($cleanedPhone, '+')) {
                            $cleanedPhone = substr($cleanedPhone, 1);
                        }

                        // Must contain only digits after cleaning
                        if (! preg_match('/^[0-9]+$/', $cleanedPhone)) {
                            $fail('Emergency phone number must contain only digits, spaces, hyphens, parentheses, and optionally start with +.');
                            return;
                        }

                        // Must be between 7 and 15 digits (international standard)
                        if (strlen($cleanedPhone) < 7 || strlen($cleanedPhone) > 15) {
                            $fail('Emergency phone number must be between 7 and 15 digits long.');
                            return;
                        }
                    }
                }
            ],
            // Address fields
            'res_country' => ['required', 'string'],
            'res_state' => ['required', 'string'],
            'res_city' => ['required', 'string'],
            'town' => ['required', 'string'],
            'postal_code' => ['required', 'alpha_num', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'res_phone' => ['required', 'string', 'max:255'],
            'res_sms_number' => ['required', 'string', 'max:255'],
            'residential_mobile' => ['required', 'string', 'max:255'],
            'res_street_address' => ['required', 'string', 'max:255'],
            'per_city' => ['required', 'string'],
            'per_phone' => ['required', 'string', 'max:255'],
            'per_postal_code' => ['required', 'alpha_num', 'max:255'],
            'per_address' => ['required', 'string', 'max:255'],
            // Guardian fields
            'guardian_name' => ['required', 'string', 'max:255'],
            'relation' => ['required', 'string'],
            'guardian_cnic' => ['nullable', 'string', 'max:15', 'regex:/^[0-9]{5}-[0-9]{7}-[0-9]$/'],
            'guardian_mobile' => ['required', 'string', 'max:20', 'regex:/^[\+]?[0-9\s\-\(\)]{7,20}$/'],
            'sns_employee' => ['nullable', 'in:yes,no,Yes,No,YES,NO'],
            'employee_no' => [
                'nullable',
                'max:10',
                function ($attribute, $value, $fail) {
                    $snsEmployee = strtolower(request()->input('sns_employee', 'no'));
                    if ($snsEmployee === 'yes' && empty($value)) {
                        $fail('Employee number is required if SNS employee is yes.');
                    }
                }
            ],
            'guardian_email' => ['nullable', 'email', 'max:255'],
            // Class/Section/Academic Year fields
            'academic_year' => ['required', 'string'],
            'class' => ['required', 'string'],
            'section' => ['required', 'string'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'first_name.string' => 'First name must be a string.',
            'first_name.max' => 'First name cannot exceed 255 characters.',
            'middle_name.string' => 'Middle name must be a string.',
            'middle_name.max' => 'Middle name cannot exceed 255 characters.',
            'last_name.string' => 'Last name must be a string.',
            'last_name.max' => 'Last name cannot exceed 255 characters.',
            'gender.required' => 'Gender is required.',
            'gender.in' => 'Gender must be either Male or Female.',
            'email.email' => 'Email must be a valid email address with proper format (e.g., user@example.com).',
            'email.max' => 'Email cannot exceed 255 characters.',
            'dob.required' => 'Date of birth is required.',
            'admission_wef.required' => 'Admission start date is required.',
            'registration_date.required' => 'Registration date is required.',
            'passport_number.string' => 'Passport number must be a string.',
            'passport_number.regex' => 'Passport number must be 6-15 characters long and contain only uppercase letters and numbers.',
            'passport_number.max' => 'Passport number cannot exceed 15 characters.',
            'birth_place.string' => 'Birth place must be a string.',
            'birth_place.max' => 'Birth place cannot exceed 255 characters.',
            'status.in' => 'Status must be one of: on_roll, registered, left, pass-out, processing.',
            'transfer_status.string' => 'Transfer status must be a string.',
            'transfer_status.max' => 'Transfer status cannot exceed 255 characters.',
            'language.required' => 'Language name is required.',
            'religion.required' => 'Religion name is required.',
            'nationality.required' => 'Nationality name is required.',
            'country.required' => 'Country name is required.',
            'state.required' => 'State name is required.',
            'city.required' => 'City name is required.',
            'branch.required' => 'Branch name is required.',
            'security_deposit.numeric' => 'Security deposit must be a number.',
            'security_deposit.min' => 'Security deposit cannot be negative.',
            'security_amount.numeric' => 'Security amount must be a number.',
            'security_amount.min' => 'Security amount cannot be negative.',
            'security_number.string' => 'Security number must be a string.',
            'security_number.max' => 'Security number cannot exceed 50 characters.',
            'admission_year.required' => 'Admission year is required.',
            'registration_fee.numeric' => 'Registration fee must be a number.',
            'registration_fee.min' => 'Registration fee cannot be negative.',
            'student_image.string' => 'Student image must be a string.',
            'student_image.max' => 'Student image path cannot exceed 255 characters.',
            'cnic.max' => 'CNIC cannot exceed 15 characters (including dashes).',
            'emergency_phone_number.string' => 'Emergency phone number must be a string.',
            'emergency_phone_number.max' => 'Emergency phone number cannot exceed 20 characters.',
            'emergency_phone_number.regex' => 'Emergency phone number must be a valid phone number format (e.g., +1234567890, (123) 456-7890, or 123-456-7890).',
            // Address fields
            'res_country.required' => 'Residence country is required.',
            'res_state.required' => 'Residence state is required.',
            'res_city.required' => 'Residence city is required.',
            'town.required' => 'Town is required.',
            'postal_code.required' => 'Postal code is required.',
            'postal_code.max' => 'Postal code cannot exceed 255 characters.',
            'contact_person.required' => 'Contact person is required.',
            'contact_person.max' => 'Contact person cannot exceed 255 characters.',
            'res_phone.required' => 'Residence phone is required.',
            'res_phone.max' => 'Residence phone cannot exceed 255 characters.',
            'res_sms_number.required' => 'Residence SMS number is required.',
            'res_sms_number.max' => 'Residence SMS number cannot exceed 255 characters.',
            'residential_mobile.required' => 'Residential mobile is required.',
            'residential_mobile.max' => 'Residential mobile cannot exceed 255 characters.',
            'res_street_address.required' => 'Residence street address is required.',
            'res_street_address.max' => 'Residence street address cannot exceed 255 characters.',
            'per_city.required' => 'Permanent city is required.',
            'per_phone.required' => 'Permanent phone is required.',
            'per_phone.max' => 'Permanent phone cannot exceed 255 characters.',
            'per_postal_code.required' => 'Permanent postal code is required.',
            'per_postal_code.max' => 'Permanent postal code cannot exceed 255 characters.',
            'per_address.required' => 'Permanent address is required.',
            'per_address.max' => 'Permanent address cannot exceed 255 characters.',
            // Guardian fields
            'guardian_name.required' => 'Guardian name is required.',
            'guardian_name.max' => 'Guardian name cannot exceed 255 characters.',
            'relation.required' => 'Guardian relation is required.',
            'guardian_cnic.max' => 'Guardian CNIC cannot exceed 15 characters.',
            'guardian_cnic.regex' => 'Guardian CNIC must be in the format xxxxx-xxxxxxx-x.',
            'guardian_mobile.required' => 'Guardian mobile is required.',
            'guardian_mobile.max' => 'Guardian mobile cannot exceed 20 characters.',
            'guardian_mobile.regex' => 'Guardian mobile must be a valid phone number.',
            'sns_employee.in' => 'SNS Employee must be Yes or No.',
            'employee_no.max' => 'Employee number cannot exceed 10 characters.',
            'employee_no.required_if' => 'Employee number is required if SNS employee is yes.',
            'guardian_email.email' => 'Guardian email must be a valid email address.',
            'guardian_email.max' => 'Guardian email cannot exceed 255 characters.',
            // Class/Section/Academic Year fields
            'academic_year.required' => 'Academic year is required.',
            'class.required' => 'Class is required.',
            'section.required' => 'Section is required.',
        ];
    }

    public function batchSize(): int
    {
        return config('import.student_import.batch_size', 50);
    }

    public function chunkSize(): int
    {
        return config('import.student_import.chunk_size', 50);
    }

    /**
     * Handle errors during import
     */
    public function onError(\Throwable $e)
    {
        if ($e instanceof ValidationException) {
            $logEntry = [
                'type' => 'validation',
                'row' => null,
                'field' => null,
                'errors' => $e->errors(),
                'value' => null,
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ];
        } else {
            $logEntry = [
                'type' => 'exception',
                'row' => null,
                'field' => null,
                'errors' => [$e->getMessage()],
                'value' => null,
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ];
        }
        $this->errors[] = $logEntry;
        File::append(storage_path('logs/student_import.log'), json_encode($logEntry) . PHP_EOL);
    }

    /**
     * Get import statistics
     */
    public function getImportStats(): array
    {
        return [
            'imported_count' => $this->importedCount,
            'skipped_count' => $this->skippedCount,
            'total_processed' => $this->importedCount + $this->skippedCount,
            'errors' => $this->errors
        ];
    }

    /**
     * Reset counters (useful for multiple imports)
     */
    public function resetCounters(): void
    {
        $this->importedCount = 0;
        $this->skippedCount = 0;
        $this->errors = [];
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $logEntry = [
                'type' => 'validation',
                'row' => $failure->row(),
                'field' => $failure->attribute(),
                'errors' => $failure->errors(),
                'value' => $failure->values()[$failure->attribute()] ?? null,
                'trace' => null,
                'timestamp' => now()->toDateTimeString(),
            ];
            $this->errors[] = $logEntry;
            File::append(storage_path('logs/student_import.log'), json_encode($logEntry) . PHP_EOL);
        }
    }
}
