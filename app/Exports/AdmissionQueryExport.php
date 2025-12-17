<?php

namespace App\Exports;

use App\Models\AdmissionQuery;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AdmissionQueryExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if (isHeadOfficeEmp() || isSuperAdmin()) {
            $admission_queries = AdmissionQuery::with(
                'city',
                'town',
                'branch',
                'com_class',
                'source',
                'academic_year',
                'inquiry_type',
            );
        }
        else{
            $admission_queries = AdmissionQuery::where('branch_id',get_branch_id())->with(
                'city',
                'town',
                'branch',
                'com_class',
                'source',
                'academic_year',
                'inquiry_type',
            );
        }
        $admission_queries = $admission_queries->get();
        $result_array = array();
        foreach ($admission_queries as $key => $collection) {
            $array_data['Sr_No'] = $collection['id'];
            $array_data['Inquiry_Number'] = isset($collection['inquiry_number']) ? $collection['inquiry_number'] : '-';
            $array_data['Inquiry_Type'] = $collection['inquiry_type']['type'];
            $array_data['Student_Name'] = $collection['student_name'];
            $array_data['Student_Age'] = $collection['student_age'];
            $array_data['Parent_Name'] = $collection['parent_name'];
            $array_data['Parent_Email'] = $collection['parent_email'];
            $array_data['Parent_Contact'] = $collection['parent_contact'];
            $array_data['City'] = $collection['city']['city_name'];
            $array_data['Town'] = $collection['town']['town_name'];
            $array_data['Branch'] = $collection['branch']['br_name'];
            $array_data['Class'] = $collection['com_class']['class_name'];
            $array_data['Source'] = $collection['source']['source_name'];
            $array_data['academic_year'] = $collection['academic_year']['title'];
            $array_data['Created_Date'] = Carbon::parse($collection['created_at'])->format('d-m-Y');

            $result_array[] = $array_data;
        }
        return collect($result_array);
    }

    public function headings(): array
    {
        $csv_headers = [
            'Sr No',
            'Inquiry Number',
            'Inquiry Type',
            'Student Name',
            'Student Age',
            'Parent Name',
            'Parent Email',
            'Parent Contact',
            'City',
            'Town',
            'Branch',
            'Class',
            'Source',
            'Academic Year',
            'Created Date',
        ];

        return $csv_headers;
    }
}
