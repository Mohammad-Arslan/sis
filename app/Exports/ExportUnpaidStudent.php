<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\StudentInvoice;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportUnpaidStudent implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        ini_set('max_execution_time', 180);
        $data = StudentInvoice::paid_unpaid_invoices_report('unpaid',1);

        $unpaid_student_array = array();
        foreach($data as $key => $collection)
        {
            $array_data['state'] = isset($collection['student']['state']['state_name']) ? $collection['student']['state']['state_name'] : '';
            $array_data['region'] = isset($collection['student']['branch']['region']) ? $collection['student']['branch']['region']['region_name'] : '';
            $array_data['branch_code'] = isset($collection['student']['branch']['branch_code']) ? $collection['student']['branch']['branch_code'] : '';
            $array_data['branch_name'] = isset($collection['student']['branch']['br_name']) ? $collection['student']['branch']['br_name'] : '';
            $array_data['invoice_no'] = isset($collection['invoice_no']) ? $collection['invoice_no'] : '';
            $array_data['issue_date'] = isset($collection['issue_date']) ? date('d-m-Y',strtotime($collection['issue_date'])) : '';
            $array_data['due_date'] = isset($collection['due_date']) ? date('d-m-Y',strtotime($collection['due_date'])) : '';;
            $array_data['validity_date'] = isset($collection['validity_date']) ? date('d-m-Y',strtotime($collection['validity_date'])) : '';
            $array_data['fee_month'] = get_month_diff($collection['fee_period']['from_date'], $collection['fee_period']['to_date']) == 1 ? get_month_name($collection['fee_period']['from_date']) : get_month_name($collection['fee_period']['from_date']) . ' - ' . get_month_name($collection['fee_period']['to_date']);
            $array_data['student_id'] = $collection['student']['registration_no'] ? $collection['student']['registration_no'] : $collection['student']['roll_no'];
            $array_data['full_name'] = isset($collection['student']) ? $collection['student']['first_name'] . ' ' . $collection['student']['middle_name'] . ' ' . $collection['student']['last_name'] : '';
            $array_data['gender'] = isset($collection['student']) ? $collection['student']['gender'] : '';
            $array_data['class_name'] = '';
            if(isset($collection['student']['active_class']['branch_class_sections']['com_classes']))
                $array_data['class_name'] = $collection['student']['active_class']['branch_class_sections']['com_classes']['class_name'];

            $array_data['section_name'] = isset($collection['student_fee_package']['section']) ? $collection['student_fee_package']['section']['section_name'] : '';
            if(isset($collection['student_fee_package']['academic_year_id'])) {
                $academicyear = AcademicYear::where('id', $collection['student_fee_package']['academic_year_id'])->get()->toArray();
                $array_data['academic_year'] =  $academicyear[0]['title'];
            }else{
                $array_data['academic_year'] = '';
            }
            $array_data['admission_wef'] = Carbon::parse($collection['admission_wef'])->format('d-m-Y');
            $array_data['package_name'] = isset($collection['student_fee_package']['fee_package']) ? $collection['student_fee_package']['fee_package']['package_name'] : '';
            $cost = calculate_total_price_by_invoice($collection);
            $array_data['total_cost'] = number_format($cost['total']);
            $array_data['email'] = $collection['student']['email'];

            $unpaid_student_array[] = $array_data;
        }
        return collect($unpaid_student_array);
    }

    public function headings(): array
    {
        $csv_headers = [
            'State',
            'Region',
            'Branch ID',
            'Branch',
            'Invoice No',
            'Issue Date',
            'Due Date',
            'Validity Date',
            'Fee Month',
            'Student ID',
            'Name',
            'Gender',
            'Class',
            'Section',
            'Academic Year',
            'Adm. Date',
            'Package Title',
            'Unpaid (Rs.)',
            'Email',
        ];

        return $csv_headers;
    }
}
