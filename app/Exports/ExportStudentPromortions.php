<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Branch;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\StudentTransferCase;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentBehaviourSkill;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportStudentPromortions implements FromCollection, WithHeadings
{
    // private $request;
    // public function __construct($request = null)
    // {
    //     $this->request = $request->query('academic_year');
    // }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //$academic_year = AcademicYear::where('active',1)->first();
        //$academic_year = $this->request;
        $StudentRemarks = \DB::table('student_behaviour_skill_remarks')
            ->selectRaw('student_behaviour_skill_remarks.student_id as student_id,student_behaviour_skill_remarks.teacher_comments as teacher_comments , student_behaviour_skill_remarks.schoolhead_comments as schoolhead_comments , student_behaviour_skill_remarks.is_promoted as is_promoted, student_behaviour_skills.branch_id,student_behaviour_skills.class_id,student_behaviour_skills.section_id,student_behaviour_skills.term_id,student_behaviour_skills.academic_year_id')
            ->join('student_behaviour_skills', 'student_behaviour_skill_remarks.student_behaviour_skill_id', '=', 'student_behaviour_skills.id')
            ->where('student_behaviour_skills.term_id',2)->whereNull('student_behaviour_skill_remarks.deleted_at');
        if (!isHeadOfficeEmp() && !isSuperAdmin())
        {
            $StudentRemarks = $StudentRemarks->where(function ($query){
                $query->where('branch_id', get_branch_id());
            });
        }
        $StudentRemarks = $StudentRemarks->get();

        $i=0;
            ini_set('max_execution_time', 180);
            foreach ($StudentRemarks as $StudentRemark) {
                $Student = Student::where('id',$StudentRemark->student_id)->where('status', 'on_roll')->with([
                    'branch',
                    'state',
                    'active_class.branch_class_sections.com_classes',
                    'active_class.branch_class_sections.sections'
                ])->get();
                if(isset($Student[0])) {
                    $data[$i]['full_name'] = $Student[0]->first_name. ' ' . $Student[0]->last_name;
                    $data[$i]['student_id'] = !empty($Student[0]->roll_no) ? $Student[0]->roll_no : $Student[0]->registration_no;
                    $data[$i]['branch'] = isset($Student[0]['branch']) ? $Student[0]['branch']['br_name'] . ' (' . $Student[0]['branch']['branch_code'] . ')' : '';
                    $class_name = isset($Student[0]['active_class']['branch_class_sections']['com_classes']) ? $Student[0]['active_class']['branch_class_sections']['com_classes']['class_name'] : 'N/A';
                    $section_name = isset($Student[0]['active_class']['branch_class_sections']['sections']) ? $Student[0]['active_class']['branch_class_sections']['sections']['section_name'] : 'N/A';
                    $data[$i]['class_section'] = $class_name . ' / ' . $section_name;
                    if($StudentRemark->is_promoted == 1) {
                        $data[$i]['status'] = 'Promoted' ;
                    } else {
                        $data[$i]['status'] = 'Not Promoted';
                    }
                    $data[$i]['schoolhead_comments'] = !empty($StudentRemark->schoolhead_comments) ? $StudentRemark->schoolhead_comments : '';
                    $data[$i]['teacher_comments'] = !empty($StudentRemark->teacher_comments) ? $StudentRemark->teacher_comments : '';
                    $i++;
                }
            }

        $StudentPromortions = array();
        foreach($data as $key => $collection)
        {
            $array_data['branch'] = isset($collection['branch']) ? $collection['branch'] : '';

            $array_data['student_id'] =  isset($collection['student_id']) ? $collection['student_id'] : '';
            $array_data['student_name'] = !empty($collection['full_name']) ? $collection['full_name'] : '';
            $array_data['class_section'] = isset($collection['class_section']) ? $collection['class_section'] : '';
            $array_data['is_promoted'] = isset($collection['status']) ? $collection['status'] : '';
            $array_data['schoolhead_comments'] = !empty($collection['schoolhead_comments']) ? $collection['schoolhead_comments'] : '';
            $array_data['teacher_comments'] = !empty($collection['teacher_comments']) ? $collection['teacher_comments'] : '';

            $StudentPromortions[] = $array_data;
        }
        return collect($StudentPromortions);
    }

    public function headings(): array
    {
        $csv_headers = [
            'Branch',
            'Student ID',
            'Student Name',
            'Class - Section',
            'Promotion Status',
            'Head Remarks',
            'Teacher Remarks',
        ];

        return $csv_headers;
    }
}
