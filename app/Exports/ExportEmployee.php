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
            'user:id,name,email',
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
        if (!empty($this->filters['company_id'])) {
            $query->where('company_id', $this->filters['company_id']);
        }
        
        if (!empty($this->filters['branch_id'])) {
            $query->where('branch_id', $this->filters['branch_id']);
        }
        
        if (!empty($this->filters['department_id'])) {
            $query->where('department_id', $this->filters['department_id']);
        }
        
        if (!empty($this->filters['designation_id'])) {
            $query->where('designation_id', $this->filters['designation_id']);
        }
        
        if (!empty($this->filters['gender'])) {
            $query->whereHas('user', function($q) {
                $q->where('gender', $this->filters['gender']);
            });
        }
        
        if (!empty($this->filters['job_status'])) {
            $query->where('job_status', $this->filters['job_status']);
        }
        
        if (!empty($this->filters['date_from'])) {
            $query->where('hiring_date', '>=', $this->filters['date_from']);
        }
        
        if (!empty($this->filters['date_to'])) {
            $query->where('hiring_date', '<=', $this->filters['date_to']);
        }

        // Apply branch filter for non-admin users (skip in queue context)
        try {
            if (auth()->check() && !isSuperAdmin() && !isHeadOfficeEmp()) {
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
                    'employee_id' => $employee->employee_id ?? '-',
                'full_name' => $employee->preferred_name ?? '-',
                'email' => $employee->user?->email ?? '-',
                'mobile_number' => $employee->mobile_number ?? '-',
                'branch_name' => $employee->branch?->br_name ?? '-',
                'branch_code' => $employee->branch?->branch_code ?? '-',
                'region_name' => $employee->branch?->region?->region_name ?? '-',
                'state_name' => $employee->branch?->state?->state_name ?? '-',
                'company_name' => $employee->company?->company_name ?? '-',
                'department_name' => $employee->department?->department_name ?? '-',
                'designation_name' => $employee->designation?->designation_name ?? '-',
                'designation_type' => $employee->designation_type?->type_name ?? '-',
                'job_status' => $employee->job_status ?? '-',
                'marital_status' => $employee->marital_status ?? '-',
                'hiring_date' => $employee->hiring_date ? date('d-m-Y', strtotime($employee->hiring_date)) : '-',
                'total_service' => $this->calculateTotalService($employee),
                'confirm_date' => $employee->confirm_date ? date('d-m-Y', strtotime($employee->confirm_date)) : '-',
                'nationality' => $employee->nationality?->nationality_name ?? '-',
                'religion' => $employee->religion?->religion_name ?? '-',
                'address' => $employee->address ?? '-',
                'date_of_birth' => $employee->date_of_birth ? date('d-m-Y', strtotime($employee->date_of_birth)) : '-',
                'father_name' => $employee->father_name ?? '-',
                'spouse_name' => $employee->spouse_name ?? '-',
                'no_of_children' => $employee->no_of_children ?? '-',
                'children_in_ucs' => $employee->children_in_ucs ?? '-',
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
            'Employee ID',
            'Full Name',
            'Email',
            'Mobile Number',
            'Branch Name',
            'Branch Code',
            'Region',
            'Province',
            'Company',
            'Department',
            'Designation',
            'Designation Type',
            'Job Status',
            'Marital Status',
            'Hiring Date',
            'Total Service',
            'Confirm Date',
            'Nationality',
            'Religion',
            'Address',
            'Date of Birth',
            'Father Name',
            'Spouse Name',
            'No of Children',
            'Children in UCS',
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
            if (!$employee->hiring_date) {
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
            BeforeSheet::class => function(BeforeSheet $event) {
                // Initialize progress tracking
                $this->currentRow = 0;
                $this->exportedCount = 0;
                $this->skippedCount = 0;
                $this->errors = [];
            },
            AfterSheet::class => function(AfterSheet $event) {
                // Final progress update
                if ($this->progressCallback) {
                    $this->triggerProgressCallback();
                }
            },
        ];
    }
}
