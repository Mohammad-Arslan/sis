<?php

namespace App\Exports;

use App\Models\StudentConcession;
use Carbon\Carbon;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\StudentInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportStudentConcession implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = StudentConcession::with(['student.student_concession', 'academic_year', 'fee_charge.fee_charges_type', 'fee_concession.fee_concession_type'])->get();
        // dd($data->toArray());

        $student_array = array();
        foreach($data as $key => $collection)
        {
            // dd($collection->toArray());
            $array_data['student_id'] = isset($collection['student']['roll_no']) ? $collection['student']['roll_no'] : '';
            $array_data['student_name'] = isset($collection['student']['first_name']) ? $collection['student']['first_name'] . ' ' . $collection['student']['middle_name'] . ' ' . $collection['student']['last_name']: '';
            $array_data['academic_year'] = isset($collection['academic_year']) ? $collection['academic_year']['title'] : '';
            $array_data['fee_charge'] = isset($collection['fee_charge']) ? $collection['fee_charge']['fee_charges_type']['name'] . ' (' . $collection['fee_charge']['amount'] . ' PKR)' : '';
            $array_data['fee_concession'] = isset($collection['fee_concession']) ? $collection['fee_concession']['fee_concession_type']['name'] . ' (' . $collection['fee_concession']['concession_percentage'] . ' %)' : '';

            // $array_data['validity_date'] = isset($collection['validity_date']) ? date('d-m-Y',strtotime($collection['validity_date'])) : '';
            // $array_data['fee_month'] = get_month_diff($collection['fee_period']['from_date'], $collection['fee_period']['to_date']) == 1 ? get_month_name($collection['fee_period']['from_date']) : get_month_name($collection['fee_period']['from_date']) . ' - ' . get_month_name($collection['fee_period']['to_date']);
            // $array_data['student_id'] = $collection['student']['registration_no'] ? $collection['student']['registration_no'] : $collection['student']['roll_no'];
            // $array_data['full_name'] = isset($collection['student']) ? $collection['student']['first_name'] . ' ' . $collection['student']['middle_name'] . ' ' . $collection['student']['last_name'] : '';
            // $array_data['gender'] = isset($collection['student']) ? $collection['student']['gender'] : '';
            // $array_data['class_name'] = '';
            // if(isset($collection['student']['active_class']['branch_class_sections']['com_classes']))
            //     $array_data['class_name'] = $collection['student']['active_class']['branch_class_sections']['com_classes']['class_name'];

            // $array_data['section_name'] = isset($collection['student_fee_package']['section']) ? $collection['student_fee_package']['section']['section_name'] : '';

            // if(isset($collection['student_fee_package']['academic_year_id'])) {
            //     $academicyear = AcademicYear::where('id', $collection['student_fee_package']['academic_year_id'])->get()->toArray();
            //     $array_data['academic_year'] =  $academicyear[0]['title'];
            // }else{
            //     $array_data['academic_year'] = '';
            // }
            // $array_data['admission_wef'] = Carbon::parse($collection['admission_wef'])->format('d-m-Y');
            // $array_data['package_name'] = isset($collection['student_fee_package']['fee_package']) ? $collection['student_fee_package']['fee_package']['package_name'] : '';
            // $cost = calculate_total_price_by_invoice($collection);
            // $array_data['total_cost'] = number_format($cost['total']);
            // $array_data['email'] = $collection['student']['email'];

            $student_array[] = $array_data;
        }
        return collect($student_array);
    }

    public function headings(): array
    {
        $csv_headers = [
            'Student ID',
            'Student Name',
            'Academic Year',
            'Fee Charges',
            'Fee Concession',
            // 'Issue Date',
            // 'Due Date',
            // 'Validity Date',
            // 'Fee Month',
            // 'Student ID',
            // 'Name',
            // 'Gender',
            // 'Class',
            // 'Section',
            // 'Academic Year',
            // 'Adm. Date',
            // 'Package Title',
            // 'Paid (Rs.)',
            // 'Email',
        ];

        return $csv_headers;
    }
}
