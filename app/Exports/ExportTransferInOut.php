<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Branch;
use App\Models\Student;
use App\Models\StudentTransferCase;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportTransferInOut implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = StudentTransferCase::with([
            'student',
            'reason',
            'student.active_class.branch_class_sections.com_classes',
            'student.active_class.branch_class_sections.sections',
            'created_by',
            'approved_by',
            'from_branch_model',
            'to_branch_model',
            'academic_year'
        ]);
        if (!isHeadOfficeEmp() && !isSuperAdmin())
        {
            $data->where('from_branch',  get_branch_id())->orWhere('to_branch', get_branch_id() );
        }
        $data = $data->get();
        $transfer_in_out_array = array();
        foreach($data as $key => $collection)
        {
            $array_data['academic_year'] = isset($collection['academic_year']['title']) ? $collection['academic_year']['title'] : '';
            $array_data['student_registration_no'] = isset($collection['student']['registration_no']) ? $collection['student']['registration_no'] : '';
            $studentInfo = Student::where('id', $collection['student']['id'])->first();
            $array_data['student_name'] = $studentInfo->first_name . ' ' . $studentInfo->middle_name . ' ' . $studentInfo->last_name;
            $array_data['class_section'] = isset($collection['student']['active_class']['branch_class_sections']['com_classes']['class_name']) ? $collection['student']['active_class']['branch_class_sections']['com_classes']['class_name'].'-'.$collection['student']['active_class']['branch_class_sections']['sections']['section_name'] : '';
            $array_data['application_id'] = isset($collection['application_id']) ? $collection['application_id'] : '';
            $array_data['request_date'] = isset($collection['request_date']) ? date('d-m-Y',strtotime($collection['request_date'])) : '';
            $array_data['transfer_wef'] = isset($collection['transfer_wef']) ? date('d-m-Y',strtotime($collection['transfer_wef'])) : '';
            $array_data['transfer_reason'] = isset($collection['reason']) ? $collection['reason']['transfer_reason'] : '';
            $branchInfo = Branch::where('id', $collection['from_branch'])->first();
            $array_data['from_branch_name'] = isset($branchInfo) ? $branchInfo->br_name . ' (' . $branchInfo->branch_code . ')' : '';
            $branchInfo = Branch::where('id', $collection['to_branch'])->first();
            $array_data['to_branch_name'] = isset($branchInfo) ? $branchInfo->br_name . ' (' . $branchInfo->branch_code . ')' : '';
            $array_data['joining_date'] = isset($collection['joining_date']) ? date('d-m-Y',strtotime($collection['joining_date'])) : '';
            $array_data['status'] = isset($collection['status']) ? $collection['status'] : '';

            $transfer_in_out_array[] = $array_data;
        }
        return collect($transfer_in_out_array);
    }

    public function headings(): array
    {
        $csv_headers = [
            'Academic Year',
            'Student ID',
            'Student Name',
            'Class - Section',
            'Order No',
            'Order Date',
            'WEF Date',
            'Transfer Reason',
            'Transfer From Branch',
            'Transfer To Branch',
            'Joining Date',
            'Status',
        ];

        return $csv_headers;
    }
}
