<?php

namespace App\Exports;

use App\Models\Employee;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;

class ExportEmployee implements FromCollection, WithHeadings, WithChunkReading, WithEvents
{
    protected $filters;
    protected $progressCallback;
    protected $exportedCount = 0;
    protected $skippedCount = 0;
    protected $errors = [];
    protected $currentRow = 0;
    protected $totalRows = 0;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Set progress callback for real-time updates
     */
    public function setProgressCallback(callable $callback): void
    {
        $this->progressCallback = $callback;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);

        $query = Employee::with([
            'user:id,name,email,first_name,last_name,CNIC,gender',
            'branch:id,br_name,branch_code,state_id,region_id',
            'branch.region:id,region_name',
            'branch.state:id,state_name',
            'company:id,company_name',
            'department:id,department_name',
            'designation:id,designation_name',
            'designation_type:id,type_name',
            'nationality:id,nationality_name',
            'religion:id,religion_name',
            'countries:id,country_name',
            'states:id,state_name',
            'cities:id,city_name'
        ]);

        // Apply filters
        if (! empty($this->filters['company_id'])) {
            $query->where('company_id', $this->filters['company_id']);
        }

        if (! empty($this->filters['branch_id'])) {
            $query->where('branch_id', $this->filters['branch_id']);
        }

        if (! empty($this->filters['department_id'])) {
            $query->where('department_id', $this->filters['department_id']);
        }

        if (! empty($this->filters['designation_id'])) {
            $query->where('designation_id', $this->filters['designation_id']);
        }

        if (! empty($this->filters['gender'])) {
            $query->whereHas('user', function ($q) {
                $q->where('gender', $this->filters['gender']);
            });
        }

        if (! empty($this->filters['job_status'])) {
            $query->where('job_status', $this->filters['job_status']);
        }

        if (! empty($this->filters['date_from'])) {
            $query->where('hiring_date', '>=', $this->filters['date_from']);
        }

        if (! empty($this->filters['date_to'])) {
            $query->where('hiring_date', '<=', $this->filters['date_to']);
        }

        // Apply branch filter for non-admin users (skip in queue context)
        try {
            if (auth()->check() && ! isSuperAdmin() && ! isHeadOfficeEmp()) {
                $branch_id = get_branch_id();
                $query->where('branch_id', $branch_id);
            }
        } catch (\Exception $e) {
            // In queue context, export all employees
            // This allows the export to work without authentication
        }

        // Get total count for progress tracking
        $this->totalRows = $query->count();

        $employees = $query->get();

        $data = [];
        $this->currentRow = 0;

        foreach ($employees as $employee) {
            $this->currentRow++;

            try {
                $data[] = [
                    // User fields (from users table)
                    'prefix' => $employee->prefix ?? '-',
                    'first_name' => $employee->user?->first_name ?? '-',
                    'last_name' => $employee->user?->last_name ?? '-',
                    'preferred_name' => $employee->preferred_name ?? '-',
                    'email' => $employee->user?->email ?? '-',
                    'cnic' => $employee->user?->CNIC ?? '-',
                    'cnic_expiry' => $employee->cnic_expiry ? date('d-m-Y', strtotime($employee->cnic_expiry)) : '-',
                    'gender' => $employee->user?->gender ?? '-',
                    'date_of_birth' => $employee->date_of_birth ? date('d-m-Y', strtotime($employee->date_of_birth)) : '-',

                    // Personal Information
                    'father_name' => $employee->father_name ?? '-',
                    'spouse_name' => $employee->spouse_name ?? '-',
                    'marital_status' => $employee->marital_status ?? '-',
                    'date_of_marriage' => $employee->date_of_marriage ? date('d-m-Y', strtotime($employee->date_of_marriage)) : '-',
                    'no_of_children' => $employee->no_of_children !== null ? $employee->no_of_children : '-',
                    'children_in_ucs' => $employee->children_in_ucs !== null ? $employee->children_in_ucs : '-',

                    // Contact Information
                    'mobile_number' => $employee->mobile_number ?? '-',
                    'address' => $employee->address ?? '-',

                    // Nationality and Religion
                    'nationality' => $employee->nationality?->nationality_name ?? '-',
                    'religion' => $employee->religion?->religion_name ?? '-',

                    // Location
                    'country' => $employee->countries?->country_name ?? '-',
                    'state' => $employee->states?->state_name ?? '-',
                    'city' => $employee->cities?->city_name ?? '-',

                    // Organization Information
                    'company' => $employee->company?->company_name ?? '-',
                    'region' => $employee->branch?->region?->region_name ?? '-',
                    'branch' => $employee->branch?->br_name ?? '-',
                    'branch_code' => $employee->branch?->branch_code ?? '-',
                    'department' => $employee->department?->department_name ?? '-',
                    'designation' => $employee->designation?->designation_name ?? '-',
                    'designation_type' => $employee->designation_type?->type_name ?? '-',

                    // Employment Status
                    'job_status' => $employee->job_status ?? '-',
                    'hiring_date' => $employee->hiring_date ? date('d-m-Y', strtotime($employee->hiring_date)) : '-',
                    'confirm_date' => $employee->confirm_date ? date('d-m-Y', strtotime($employee->confirm_date)) : '-',
                    'regular_date' => $employee->regular_date ? date('d-m-Y', strtotime($employee->regular_date)) : '-',
                    'left_date' => $employee->left_date ? date('d-m-Y', strtotime($employee->left_date)) : '-',
                    'probation_end_date' => $employee->probation_end_date ? date('d-m-Y', strtotime($employee->probation_end_date)) : '-',
                    'probation_extended' => $employee->probation_extended ?? '-',

                    // ID Numbers
                    'pin_code' => $employee->pin_code ?? '-',
                    'card_no' => $employee->card_no ?? '-',
                    'eobi_number' => $employee->eobi_number ?? '-',
                    'ni_number' => $employee->ni_number ?? '-',
                    'ss_no' => $employee->ss_no ?? '-',
                    'previous_id' => $employee->previous_id ?? '-',

                    // Passport Information
                    'passport_number' => $employee->passport_number ?? '-',
                    'issue_date' => $employee->issue_date ? date('d-m-Y', strtotime($employee->issue_date)) : '-',
                    'expiry_date' => $employee->expiry_date ? date('d-m-Y', strtotime($employee->expiry_date)) : '-',

                    // Other
                    'crb' => $employee->crb ?? '-',

                    // Calculated field (for information only - not imported)
                    'total_service' => $this->calculateTotalService($employee),
                ];

                $this->exportedCount++;

                // Trigger progress callback every 10 records
                if ($this->currentRow % 10 === 0 && $this->progressCallback) {
                    $this->triggerProgressCallback();
                }
            } catch (\Exception $e) {
                $this->errors[] = [
                    'row' => $this->currentRow,
                    'employee_id' => $employee->employee_id ?? 'N/A',
                    'error' => $e->getMessage(),
                ];
                $this->skippedCount++;

                // Still trigger progress callback for errors
                if ($this->progressCallback) {
                    $this->triggerProgressCallback();
                }
            }
        }

        // Final progress update
        if ($this->progressCallback) {
            $this->triggerProgressCallback();
        }

        return collect($data);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            // User fields (from users table)
            'Prefix',
            'First Name',
            'Last Name',
            'Preferred Name',
            'Email',
            'CNIC',
            'CNIC Expiry',
            'Gender',
            'Date of Birth',

            // Personal Information
            'Father Name',
            'Spouse Name',
            'Marital Status',
            'Date of Marriage',
            'No of Children',
            'Children in UCS',

            // Contact Information
            'Mobile Number',
            'Address',

            // Nationality and Religion
            'Nationality',
            'Religion',

            // Location
            'Country',
            'State',
            'City',

            // Organization Information
            'Company',
            'Region',
            'Branch',
            'Branch Code',
            'Department',
            'Designation',
            'Designation Type',

            // Employment Status
            'Job Status',
            'Hiring Date',
            'Confirm Date',
            'Regular Date',
            'Left Date',
            'Probation End Date',
            'Probation Extended',

            // ID Numbers
            'Pin Code',
            'Card No',
            'EOBI Number',
            'NI Number',
            'SS No',
            'Previous ID',

            // Passport Information
            'Passport Number',
            'Issue Date',
            'Expiry Date',

            // Other
            'CRB',

            // Calculated field (for information only)
            'Total Service',
        ];
    }

    /**
     * Calculate total service duration for an employee
     *
     * @param Employee $employee
     * @return string
     */
    private function calculateTotalService($employee)
    {
        try {
            if (! $employee->hiring_date) {
                return '-';
            }

            $hiringDate = \Carbon\Carbon::parse($employee->hiring_date);

            // If employee has left, calculate from hiring date to left date
            if ($employee->job_status === 'left' && $employee->left_date) {
                $endDate = \Carbon\Carbon::parse($employee->left_date);
            } else {
                // For active employees, calculate from hiring date to current date
                $endDate = \Carbon\Carbon::now();
            }

            // Calculate the difference
            $diff = $hiringDate->diff($endDate);

            $years = $diff->y;
            $months = $diff->m;
            $days = $diff->d;

            // Format the result
            $result = [];

            if ($years > 0) {
                $result[] = $years . ' ' . ($years == 1 ? 'Year' : 'Years');
            }

            if ($months > 0) {
                $result[] = $months . ' ' . ($months == 1 ? 'Month' : 'Months');
            }

            if ($days > 0 && $years == 0) {
                $result[] = $days . ' ' . ($days == 1 ? 'Day' : 'Days');
            }

            return empty($result) ? 'Less than 1 day' : implode(', ', $result);
        } catch (\Exception $e) {
            Log::error('Error calculating total service for employee ID: ' . $employee->id . ' - ' . $e->getMessage());
            return '-';
        }
    }

    /**
     * Trigger progress callback
     */
    protected function triggerProgressCallback(): void
    {
        if ($this->progressCallback) {
            $stats = [
                'total_processed' => $this->currentRow,
                'total_rows' => $this->totalRows,
                'exported' => $this->exportedCount,
                'skipped' => $this->skippedCount,
                'errors' => count($this->errors),
                'current_row' => $this->currentRow,
            ];

            call_user_func($this->progressCallback, $stats);
        }
    }

    /**
     * Get export statistics
     */
    public function getExportStats(): array
    {
        return [
            'total_processed' => $this->currentRow,
            'total_rows' => $this->totalRows,
            'exported' => $this->exportedCount,
            'skipped' => $this->skippedCount,
            'errors' => count($this->errors),
            'errors_data' => $this->errors,
        ];
    }

    /**
     * Get chunk size for processing
     */
    public function chunkSize(): int
    {
        return 100; // Process 100 records at a time
    }

    /**
     * Register events for progress tracking
     */
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                // Initialize progress tracking
                $this->currentRow = 0;
                $this->exportedCount = 0;
                $this->skippedCount = 0;
                $this->errors = [];
            },
            AfterSheet::class => function (AfterSheet $event) {
                // Final progress update
                if ($this->progressCallback) {
                    $this->triggerProgressCallback();
                }
            },
        ];
    }
}
