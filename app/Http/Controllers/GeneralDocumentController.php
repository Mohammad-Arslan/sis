<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\AttachmentType;
use App\Models\Branch;
use App\Models\ComClass;
use App\Models\GeneralDocument;
use App\Models\State;
use App\Models\Student;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class GeneralDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $general_documents = new GeneralDocument();
            if (isset($request->student_id)) {
                $student = Student::find($request->student_id);
                $general_documents = $student->general_documents();
            } else {
                $general_documents = $general_documents->whereNull('general_documentable_id');
                $general_documents = GeneralDocument::filteration($request, $general_documents);
            }

            $general_documents = $this->paginateData($general_documents, $request);

            $json_data = array(
                "draw"            => intval($request->input('draw')),
                "recordsTotal"    => intval($general_documents['totalData']),
                "recordsFiltered" => intval($general_documents['totalFiltered']),
                "data"            => $general_documents['data']
            );

            return response()->json($json_data);
        } else {
            $data['branches'] = Branch::all();
            $data['attachment_types'] = AttachmentType::where('module', 'attachment')->get();
            $data['classes'] = ComClass::all();
            $data['states'] = State::all();
            $data['academic_years'] = AcademicYear::all();
        }

        return view('general_document.index', $data);
    }

    public function paginateData($query, $request, $data = 0)
    {
        $limit = $request->input('length');
        $start = $request->input('start');
        $search = $request->input('search.value');
        $view_action = $data ? $data['view_action_route'] : 'general_document.action';

        $totalData = $query->count();
        $query = $query->offset($start)->limit($limit);
        $totalFiltered = $totalData;
        if ($search) {
            $query = $query->where('document_name', 'like', '%' . $search . '%');
            $totalFiltered = $query->count();
        }

        $query = $query->orderByDesc('id')->get();

        $data = array();
        if (!empty($query)) {
            foreach ($query as $key => $document) {
                $nestedData['document_name'] = $document['document_name'];
                $nestedData['branch_name'] = $document['branch'] ? $document['branch']['br_name'] : 'N/A';
                $nestedData['class_name'] = $document['com_class'] ? $document['com_class']['class_name'] : 'N/A';
                $nestedData['subject_name'] = $document['subject'] ? $document['subject']['subject_name'] : 'N/A';
                $nestedData['type'] = $document['attachment_type'] ? $document['attachment_type']['name'] : 'N/A';
                $nestedData['uploaded_by'] = $document['user']['name'];
                $nestedData['remarks'] = $document->remarks ? $document->remarks : 'N/A';
                $nestedData['status'] = $document->status == 'active' ? '<span class="badge bg-success">Active</span>' : ($document->status == 'inactive' ? '<span class="badge bg-danger">In Active</span>' : $document->status);
                $nestedData['created_at'] = date_format($document['created_at'], 'd-m-Y');
                $nestedData['action'] = view($view_action, ['row' => $document, 'request' => $request])->render();

                $data[] = $nestedData;
            }
        }

        return [
            'data' => $data,
            'totalData' => $totalData,
            'totalFiltered' => $totalFiltered,
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'document_name' => 'required|max:255',
            'attachment_type_id' => 'required',
            'document_type' => 'required',
            'file' => 'required_if:document_type,file|file|max:10000',
            'url' => 'required_if:document_type,url|max:65535',
            'remarks' => 'max:16777215'
        ]);

        $input = $request->all();
        $input['uploaded_by'] = auth()->user()->id;
        if ($request->document_type == 'url') {
            $input['file_name'] = $request->url;
        } else if ($request->hasfile('file')) {

            $extension = $request->file->extension();
            $file_basename = basename($request->file->getClientOriginalName(), '.' . $extension);
            $filename = $request->file->getClientOriginalName();
            $filenameCount = GeneralDocument::where('file_name', $filename)->count();
            $filename = $filenameCount > 0 ? $file_basename . ' (' . $filenameCount . ').' . $extension : $filename;

            $input['file_name'] = $filename;
            $filepath = 'general_documents/' . $request->attachment_type_id . '/' . $filename;
            Storage::disk('s3')->put($filepath, file_get_contents($request->file));
        } else {
            return redirect()->back()->with('error', 'Something went wrong !');
        }

        if (isset($request->student_id)) {
            $student = Student::find($request->student_id);
            $input['branch_id'] = $student->branch_id;
            $input['status'] = 'active';
            $student->general_documents()->create($input);
        } else {
            GeneralDocument::create($input);
        }

        return redirect()->back()->with('success', 'Form submitted successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GeneralDocument  $generalDocument
     * @return \Illuminate\Http\Response
     */
    public function show(GeneralDocument $generalDocument)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GeneralDocument  $generalDocument
     * @return \Illuminate\Http\Response
     */
    public function edit(GeneralDocument $generalDocument)
    {
        $data['branches'] = Branch::all();
        $data['attachment_types'] = AttachmentType::where('module', 'attachment')->get();
        $data['classes'] = ComClass::all();
        $data['states'] = State::all();
        $data['academic_years'] = AcademicYear::all();
        $data['general_document'] = $generalDocument;
        $data['subjects'] = listClassSubjects($generalDocument->com_class_id);

        return view('general_document.index', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GeneralDocument  $generalDocument
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GeneralDocument $generalDocument)
    {
        $request->validate([
            'document_name' => 'required|max:255',
            'attachment_type_id' => 'required',
            'document_type' => 'required',
            //'file' => 'required_if:document_type,file|file|max:10000',
            'url' => 'required_if:document_type,url|max:65535',
            'remarks' => 'max:16777215'
        ]);
        $input = $request->all();
        $input['uploaded_by'] = auth()->user()->id;

        if ($request->document_type == 'url') {
            $input['file_name'] = $request->url;
        } else if ($request->hasfile('file')) {

            $aws_previous_path = 'general_documents/' . $generalDocument->attachment_type_id . '/' . $generalDocument->file_name;
            if (Storage::disk('s3')->exists($aws_previous_path))
                Storage::disk('s3')->delete($aws_previous_path);

            $extension = $request->file->extension();
            $file_basename = basename($request->file->getClientOriginalName(), '.' . $extension);
            $filename = $request->file->getClientOriginalName();
            $filenameCount = GeneralDocument::where('file_name', $filename)->count();
            $filename = $filenameCount > 0 ? $file_basename . ' (' . $filenameCount . ').' . $extension : $filename;

            $input['file_name'] = $filename;
            $filepath = 'general_documents/' . $request->attachment_type_id . '/' . $filename;
            Storage::disk('s3')->put($filepath, file_get_contents($request->file));
        }

        $generalDocument->update($input);

        if (isset($request->student_id))
            return redirect(route('students.edit', $request->student_id) . '?tab=student_image')->with('success', 'Form updated successfully');
        else
            return redirect()->route('general-document.index')->with('success', 'Form updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GeneralDocument  $generalDocument
     * @return \Illuminate\Http\Response
     */
    public function destroy(GeneralDocument $generalDocument)
    {
        try {
            return $generalDocument->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function class_timetable(Request $request)
    {
        if ($request->ajax()) {

            $class_timetable = GeneralDocument::whereHas(
                'attachment_type',
                function ($q) {
                    $q->where('slug', 'class_timetable');
                }
            )->where('status', 'active');

            $class_timetable = GeneralDocument::filteration($request, $class_timetable);

            $class_timetable = $class_timetable->get();

            return DataTables::of($class_timetable)
                ->addIndexColumn()
                ->addColumn('type', function ($row) {
                    return $row['attachment_type']['name'];
                })
                ->addColumn('branch_name', function ($row) {
                    return $row['branch'] ? $row['branch']['br_name'] : 'N/A';
                })
                ->addColumn('uploaded_by', function ($row) {
                    return $row['user']['name'];
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 'active' ? '<span class="badge bg-success">Active</span>' : ($row->status == 'inactive' ? '<span class="badge bg-danger">In Active</span>' : $row->status);
                })
                ->addColumn('remarks', function ($row) {
                    return $row->remarks ? $row->remarks : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return view('general_document.class_timetable.action', ['row' => $row]);
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('general_document.class_timetable.index');
    }

    public function subject_teacher_timetable(Request $request)
    {
        if ($request->ajax()) {

            $subject_teacher_timetable = GeneralDocument::whereHas(
                'attachment_type',
                function ($q) {
                    $q->where('slug', 'datesheet');
                }
            )->where('status', 'active');

            $subject_teacher_timetable = GeneralDocument::filteration($request, $subject_teacher_timetable);

            $subject_teacher_timetable = $subject_teacher_timetable->get();

            return DataTables::of($subject_teacher_timetable)
                ->addIndexColumn()
                ->addColumn('type', function ($row) {
                    return $row['attachment_type']['name'];
                })
                ->addColumn('branch_name', function ($row) {
                    return $row['branch'] ? $row['branch']['br_name'] : 'N/A';
                })
                ->addColumn('academic_year', function ($row) {
                    return $row['academic_year'] ? $row['academic_year']['title'] : 'N/A';
                })
                ->addColumn('class_name', function ($row) {
                    return $row['com_class'] ? $row['com_class']['class_name'] : 'N/A';
                })
                ->addColumn('uploaded_by', function ($row) {
                    return $row['user']['name'];
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 'active' ? '<span class="badge bg-success">Active</span>' : ($row->status == 'inactive' ? '<span class="badge bg-danger">In Active</span>' : $row->status);
                })
                ->addColumn('remarks', function ($row) {
                    return $row->remarks ? $row->remarks : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return view('general_document.subject_teacher_timetable.action', ['row' => $row]);
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $data = GeneralDocument::filterationDropdownData();
        return view('general_document.subject_teacher_timetable.index', $data);
    }

    public function school_manual(Request $request)
    {
        if ($request->ajax()) {

            $school_manuals = GeneralDocument::whereHas(
                'attachment_type',
                function ($q) {
                    $q->where('slug', 'school_manuals');
                }
            )->where('status', 'active');

            $school_manuals = GeneralDocument::filteration($request, $school_manuals);
            $data['view_action_route'] = 'general_document.school_manual.action';
            $school_manuals = $this->paginateData($school_manuals, $request, $data);

            $json_data = array(
                "draw"            => intval($request->input('draw')),
                "recordsTotal"    => intval($school_manuals['totalData']),
                "recordsFiltered" => intval($school_manuals['totalFiltered']),
                "data"            => $school_manuals['data']
            );

            return response()->json($json_data);
        }

        $data['branches'] = Branch::all();
        if (isSuperAdmin() || isHeadOfficeEmp())
            $data['states'] = State::all();
        else
            $data['states'] = State::where('id', get_branch_state_id())->get();

        return view('general_document.school_manual.index', $data);
    }

    public function infinity_teacher_guide(Request $request)
    {
        if ($request->ajax()) {

            $infinity_teacher_guide = GeneralDocument::whereHas(
                'attachment_type',
                function ($q) {
                    $q->where('slug', 'infinity_teacher_guide');
                }
            )->where('status', 'active');

            $infinity_teacher_guide = GeneralDocument::filteration($request, $infinity_teacher_guide);
            $data['view_action_route'] = 'general_document.infinity_teacher_guide.action';
            $infinity_teacher_guide = $this->paginateData($infinity_teacher_guide, $request, $data);

            $json_data = array(
                "draw"            => intval($request->input('draw')),
                "recordsTotal"    => intval($infinity_teacher_guide['totalData']),
                "recordsFiltered" => intval($infinity_teacher_guide['totalFiltered']),
                "data"            => $infinity_teacher_guide['data']
            );

            return response()->json($json_data);
        }

        $data['branches'] = Branch::all();
        $data['states'] = State::all();

        return view('general_document.infinity_teacher_guide.index', $data);
    }

    public function support_staff_uniform(Request $request)
    {
        if ($request->ajax()) {

            $infinity_teacher_guide = GeneralDocument::whereHas(
                'attachment_type',
                function ($q) {
                    $q->where('slug', 'support_staff_uniform');
                }
            )->where('status', 'active');

            $infinity_teacher_guide = GeneralDocument::filteration($request, $infinity_teacher_guide);
            $data['view_action_route'] = 'general_document.infinity_teacher_guide.action';
            $infinity_teacher_guide = $this->paginateData($infinity_teacher_guide, $request, $data);

            $json_data = array(
                "draw"            => intval($request->input('draw')),
                "recordsTotal"    => intval($infinity_teacher_guide['totalData']),
                "recordsFiltered" => intval($infinity_teacher_guide['totalFiltered']),
                "data"            => $infinity_teacher_guide['data']
            );

            return response()->json($json_data);
        }

        $data['branches'] = Branch::all();
        $data['states'] = State::all();

        return view('general_document.support_staff_uniform.index', $data);
    }
    public function syllabus(Request $request)
    {
        if ($request->ajax()) {

            $subject_teacher_timetable = GeneralDocument::whereHas(
                'attachment_type',
                function ($q) {
                    $q->where('slug', 'syllabus');
                }
            )->where('status', 'active');

            $subject_teacher_timetable = GeneralDocument::filteration($request, $subject_teacher_timetable);

            $subject_teacher_timetable = $subject_teacher_timetable->get();

            return DataTables::of($subject_teacher_timetable)
                ->addIndexColumn()
                ->addColumn('type', function ($row) {
                    return $row['attachment_type']['name'];
                })
                ->addColumn('branch_name', function ($row) {
                    return $row['branch'] ? $row['branch']['br_name'] : 'N/A';
                })
                ->addColumn('class_name', function ($row) {
                    return $row['com_class'] ? $row['com_class']['class_name'] : 'N/A';
                })
                ->addColumn('academic_year', function ($row) {
                    return $row['academic_year'] ? $row['academic_year']['title'] : 'N/A';
                })
                ->addColumn('uploaded_by', function ($row) {
                    return $row['user']['name'];
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 'active' ? '<span class="badge bg-success">Active</span>' : ($row->status == 'inactive' ? '<span class="badge bg-danger">In Active</span>' : $row->status);
                })
                ->addColumn('remarks', function ($row) {
                    return $row->remarks ? $row->remarks : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return view('general_document.syllabus.action', ['row' => $row]);
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $data = GeneralDocument::filterationDropdownData();

        return view('general_document.syllabus.index', $data);
    }
    public function assessment_paper(Request $request)
    {
        if ($request->ajax()) {

            $subject_teacher_timetable = GeneralDocument::whereHas(
                'attachment_type',
                function ($q) {
                    $q->whereIn('slug', ['assessment_paper_student_copy', 'assessment_paper_marking_key']);
                }
            )->where('status', 'active');

            $subject_teacher_timetable = GeneralDocument::filteration($request, $subject_teacher_timetable);

            $subject_teacher_timetable = $subject_teacher_timetable->get();

            return DataTables::of($subject_teacher_timetable)
                ->addIndexColumn()
                ->addColumn('type', function ($row) {
                    return $row['attachment_type']['name'];
                })
                ->addColumn('branch_name', function ($row) {
                    return $row['branch'] ? $row['branch']['br_name'] : 'N/A';
                })
                ->addColumn('academic_year', function ($row) {
                    return $row['academic_year'] ? $row['academic_year']['title'] : 'N/A';
                })
                ->addColumn('state_name', function ($row) {
                    return $row['state'] ? $row['state']['state_name'] : 'N/A';
                })
                ->addColumn('class_name', function ($row) {
                    return $row['com_class'] ? $row['com_class']['class_name'] : 'N/A';
                })
                ->addColumn('subject_name', function ($row) {
                    return $row['subject'] ? $row['subject']['subject_name'] : 'N/A';
                })
                ->addColumn('uploaded_by', function ($row) {
                    return $row['user']['name'];
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 'active' ? '<span class="badge bg-success">Active</span>' : ($row->status == 'inactive' ? '<span class="badge bg-danger">In Active</span>' : $row->status);
                })
                ->addColumn('remarks', function ($row) {
                    return $row->remarks ? $row->remarks : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return view('general_document.assessment_paper.action', ['row' => $row]);
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $data = GeneralDocument::filterationDropdownData();
        $data['attachment_types'] = AttachmentType::whereIn('slug', ['assessment_paper_student_copy', 'assessment_paper_marking_key'])->get();

        return view('general_document.assessment_paper.index', $data);
    }
    public function winter_resource_pack(Request $request)
    {
        if ($request->ajax()) {

            $subject_teacher_timetable = GeneralDocument::whereHas(
                'attachment_type',
                function ($q) {
                    $q->whereIn('slug', ['winter_resource_pack']);
                }
            )->where('status', 'active');

            $subject_teacher_timetable = GeneralDocument::filteration($request, $subject_teacher_timetable);

            $subject_teacher_timetable = $subject_teacher_timetable->get();

            return DataTables::of($subject_teacher_timetable)
                ->addIndexColumn()
                ->addColumn('type', function ($row) {
                    return $row['attachment_type']['name'];
                })
                ->addColumn('branch_name', function ($row) {
                    return $row['branch'] ? $row['branch']['br_name'] : 'N/A';
                })
                ->addColumn('academic_year', function ($row) {
                    return $row['academic_year'] ? $row['academic_year']['title'] : 'N/A';
                })
                ->addColumn('state_name', function ($row) {
                    return $row['state'] ? $row['state']['state_name'] : 'N/A';
                })
                ->addColumn('class_name', function ($row) {
                    return $row['com_class'] ? $row['com_class']['class_name'] : 'N/A';
                })
                ->addColumn('subject_name', function ($row) {
                    return $row['subject'] ? $row['subject']['subject_name'] : 'N/A';
                })
                ->addColumn('uploaded_by', function ($row) {
                    return $row['user']['name'];
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 'active' ? '<span class="badge bg-success">Active</span>' : ($row->status == 'inactive' ? '<span class="badge bg-danger">In Active</span>' : $row->status);
                })
                ->addColumn('remarks', function ($row) {
                    return $row->remarks ? $row->remarks : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return view('general_document.winter_resource_pack.action', ['row' => $row]);
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $data = GeneralDocument::filterationDropdownData();
        $data['attachment_types'] = AttachmentType::whereIn('slug', ['winter_resource_pack'])->get();

        return view('general_document.winter_resource_pack.index', $data);
    }

    public function summer_resource_pack(Request $request)
    {
        if ($request->ajax()) {

            $subject_teacher_timetable = GeneralDocument::whereHas(
                'attachment_type',
                function ($q) {
                    $q->whereIn('slug', ['summer_resource_pack']);
                }
            )->where('status', 'active');

            $subject_teacher_timetable = GeneralDocument::filteration($request, $subject_teacher_timetable);

            $subject_teacher_timetable = $subject_teacher_timetable->get();

            return DataTables::of($subject_teacher_timetable)
                ->addIndexColumn()
                ->addColumn('type', function ($row) {
                    return $row['attachment_type']['name'];
                })
                ->addColumn('branch_name', function ($row) {
                    return $row['branch'] ? $row['branch']['br_name'] : 'N/A';
                })
                ->addColumn('academic_year', function ($row) {
                    return $row['academic_year'] ? $row['academic_year']['title'] : 'N/A';
                })
                ->addColumn('state_name', function ($row) {
                    return $row['state'] ? $row['state']['state_name'] : 'N/A';
                })
                ->addColumn('class_name', function ($row) {
                    return $row['com_class'] ? $row['com_class']['class_name'] : 'N/A';
                })
                ->addColumn('subject_name', function ($row) {
                    return $row['subject'] ? $row['subject']['subject_name'] : 'N/A';
                })
                ->addColumn('uploaded_by', function ($row) {
                    return $row['user']['name'];
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 'active' ? '<span class="badge bg-success">Active</span>' : ($row->status == 'inactive' ? '<span class="badge bg-danger">In Active</span>' : $row->status);
                })
                ->addColumn('remarks', function ($row) {
                    return $row->remarks ? $row->remarks : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return view('general_document.summer_resource_pack.action', ['row' => $row]);
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $data = GeneralDocument::filterationDropdownData();
        $data['attachment_types'] = AttachmentType::whereIn('slug', ['summer_resource_pack'])->get();

        return view('general_document.summer_resource_pack.index', $data);
    }

    public function admission_test(Request $request)
    {

        if ($request->ajax()){

            $admissiontest = GeneralDocument::whereHas(
                'attachment_type' , function($q){
                $q->where('slug','admission_test');
            })->where('status','active');

            $admissiontest = GeneralDocument::filteration($request,$admissiontest);

            $admissiontest = $admissiontest->get();
            return DataTables::of($admissiontest)
                ->addIndexColumn()
                ->addColumn('class_name', function ($row) {
                    return $row['com_class'] ? $row['com_class']['class_name'] : '-';
                })
                ->addColumn('state_name', function ($row) {
                    return $row['state'] ? $row['state']['state_name'] : '-';
                })
                ->addColumn('branch_name', function ($row) {
                    return $row['branch'] ? $row['branch']['br_name'] : 'N/A';
                })
                ->addColumn('subject_name', function ($row) {
                    return $row['subject'] ? $row['subject']['subject_name'] : 'N/A';
                })
                ->addColumn('uploaded_by', function ($row) {
                    return $row['user']['name'];
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 'active' ? '<span class="badge bg-success">Active</span>' : ($row->status == 'inactive' ? '<span class="badge bg-danger">In Active</span>' : $row->status);
                })
                ->addColumn('remarks', function ($row) {
                    return $row->remarks ? $row->remarks : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return view('general_document.admission_test.action', ['row' => $row]);
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }

        $data = GeneralDocument::filterationDropdownData();

        return view('general_document.admission_test.index',$data);
    }
    public function certificates(Request $request)
    {
        if ($request->ajax()) {

            $certificates = GeneralDocument::whereHas(
                'attachment_type',
                function ($q) {
                    $q->where('slug', 'certificates');
                }
            )->where('status', 'active');

            $certificates = GeneralDocument::filteration($request, $certificates);
            $data['view_action_route'] = 'general_document.certificates.action';
            $certificates = $this->paginateData($certificates, $request, $data);

            $json_data = array(
                "draw"            => intval($request->input('draw')),
                "recordsTotal"    => intval($certificates['totalData']),
                "recordsFiltered" => intval($certificates['totalFiltered']),
                "data"            => $certificates['data']
            );

            return response()->json($json_data);
        }

        $data['branches'] = Branch::all();
        if (isSuperAdmin() || isHeadOfficeEmp())
            $data['states'] = State::all();
        else
            $data['states'] = State::where('id', get_branch_state_id())->get();

        return view('general_document.certificates.index', $data);
    }
}
