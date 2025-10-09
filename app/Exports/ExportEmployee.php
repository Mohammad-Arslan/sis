<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportEmployee implements FromCollection, WithHeadings
{
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

        // Apply branch filter for non-admin users
        if (!isSuperAdmin() && !isHeadOfficeEmp()) {
            $branch_id = get_branch_id();
            $query->where('branch_id', $branch_id);
        }

        $employees = $query->get();
        
        $data = [];
        foreach ($employees as $employee) {
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
            \Log::error('Error calculating total service for employee ID: ' . $employee->id . ' - ' . $e->getMessage());
            return '-';
        }
    }
}
