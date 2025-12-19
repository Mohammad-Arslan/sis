<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportStudentRelation implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $branch_id = 0;
        if (! Auth::user()->hasRole('super_admin')) {
            $branch_id = get_branch_id();
        }
        if ($branch_id != 0) {
                $data = Student::where('branch_id', $branch_id)->with(
                    ['branch',
                    'active_class.branch_class_sections.com_classes',
                    'student_invoice',
                    'std_fee_package.fee_package.fee_package_type',
                    'std_fee_package.section',
                    'guardian']
                );
        } else {
                $data = Student::with(
                    ['branch',
                    'active_class.branch_class_sections.com_classes',
                    'student_invoice',
                    'std_fee_package.fee_package.fee_package_type',
                    'std_fee_package.section',
                    'guardian']
                );
        }
        $data = $data->get();
        $student_relation_array = array();
        foreach ($data as $key => $collection) {
                $array_data['branch_code'] = $collection['branch']['branch_code'];
                $array_data['branch_name'] = $collection['branch']['br_name'];
            if ($collection['roll_no'] == null) {
                $array_data['student_id'] = $collection['registration_no'];
            } else {
                $array_data['student_id'] = $collection['roll_no'];
            }

                $array_data['full_name'] = $collection['first_name'] . ' ' . $collection['middle_name'] . ' ' . $collection['last_name'];
                $array_data['gender'] = $collection['gender'];

            if (isset($collection['active_class']['branch_class_sections']['com_classes'])) {
                $array_data['class_name'] = $collection['active_class']['branch_class_sections']['com_classes']['class_name'] ;
            } else {
                $array_data['class_name'] = '' ;
            }
            if (isset($collection['std_fee_package']['section'])) {
                $array_data['section_name'] = $collection['std_fee_package']['section']['section_name'] ;
            } else {
                $array_data['section_name'] = '' ;
            }

                $array_data['admission_wef'] = Carbon::parse($collection['admission_wef'])->format('d-m-Y');

            if (isset($collection['std_fee_package']['fee_package'])) {
                $array_data['package_name'] = $collection['std_fee_package']['fee_package']['package_name'] ;
            } else {
                $array_data['package_name'] = '' ;
            }

            if (isset($collection['student_invoice'])) {
                $cost = calculate_total_price_by_invoice($collection['student_invoice']);
                $array_data['total_cost'] = number_format($cost['total']);
            } else {
                $array_data['total_cost'] = '';
            }

                $array_data['email'] = $collection['email'];

                $student_relation_array[] = $array_data;
        }
        return collect($student_relation_array);
    }

    public function headings(): array
    {
        $csv_headers = [
            'Branch ID',
            'Branch',
            'Student ID',
            'Name',
            'Gender',
            'Class',
            'Section',
            'Fee Package',
            'Monthly Fee (Rs.)',
            'Parent Name',
            'Parent Contact',
        ];

        return $csv_headers;
    }
}
