<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Branch;
use App\Models\BranchClass;
use App\Models\BranchClassSection;
use App\Models\ClassStudent;
use App\Models\ComClass;
use App\Models\ExtraCurriculum;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Log;

class ExtraCurriculumController extends Controller
{
    public function index()
    {
        return view('students.extra_curriculum.extra_curriculum_info');
    }

    public function getExtraCurriculumInfo($student_id)
    {
        if (request()->ajax()) {
            $extra_curriculum_info = ExtraCurriculum::where('student_id', $student_id)
                ->select([
                    'id',
                    'activity_name',
                    'activity_date',
                    'activity_type',
                    'marks_type',
                    'grade',
                    'total_marks',
                    'obtained_marks',
                    'remarks',
                    'attachment',
                    'created_at'
                ])
                ->latest();

            return datatables()->of($extra_curriculum_info)
                ->addIndexColumn()
                ->addColumn('marks', function ($row) {
                    if ($row->marks_type === 'grade') {
                        return $row->grade ?? '-';
                    } elseif ($row->marks_type === 'marks') {
                        return $row->obtained_marks . ' / ' . $row->total_marks;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('attachment', function ($row) {
                    if ($row->attachment) {
                        $url = asset('storage/' . $row->attachment);
                        return '<a href="' . $url . '" target="_blank"><img src="' . $url . '" style="height: 40px; border-radius: 4px;" alt="Attachment"></a>';
                    }
                    return '<span class="text-muted">N/A</span>';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="d-flex gap-2">
                    <a href="javascript:void(0)" class="edit btn btn-primary btn-sm" data-id="' . $row->id . '">Edit</a>
                    <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-id="' . $row->id . '">Delete</a>
                </div>';
                })
                ->rawColumns(['action', 'attachment']) // allow HTML rendering
                ->make(true);
        }
    }

    public function create()
    {
        return view('students.extra_curriculum.extra_curriculum_info_form');
    }

    public function edit($id)
    {
        $extra_curriculum = ExtraCurriculum::findOrFail($id);
        return response()->json($extra_curriculum);
    }

    public function update(Request $request, $id)
    {
        $baseRules = [
            'activity_name' => 'required|string|max:255',
            'activity_date' => 'required|date',
            'activity_type' => 'required|string|in:sports,cultural,academic,other',
            'remarks' => 'nullable|string|max:1000',
            'marks_type' => 'required|in:grade,marks',
            'custom_activity_name' => 'nullable|string|max:255',
            'grade' => 'nullable|string|in:A+,A,B,C,D',
            'total_marks' => 'nullable|numeric|min:0',
            'obtained_marks' => 'nullable|numeric|min:0',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $validated = $request->validate($baseRules);

        if ($validated['activity_type'] === 'other') {
            $request->validate([
                'custom_activity_name' => 'required|string|max:255',
            ]);
            $validated['activity_type'] = $validated['custom_activity_name'];
        }

        if ($validated['marks_type'] === 'grade') {
            $request->validate([
                'grade' => 'required|string|in:A+,A,B,C,D',
            ]);
            $validated['grade'] = $request->grade;
            $validated['total_marks'] = null;
            $validated['obtained_marks'] = null;
        } elseif ($validated['marks_type'] === 'marks') {
            $request->validate([
                'total_marks' => 'required|numeric|min:0',
                'obtained_marks' => 'required|numeric|min:0|lte:total_marks',
            ]);
            $validated['grade'] = null;
        }

        $validated['activity_date'] = date('Y-m-d', strtotime($validated['activity_date']));
        $validated['student_id'] = $id;

        // Handle attachment
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('extra_curriculam_attachments', $fileName, 'public');
            $validated['attachment'] = $filePath;
        }

        $existing = ExtraCurriculum::where('student_id', $id)
            ->where('activity_date', $validated['activity_date'])
            ->where('id', '!=', $request->id)
            ->first();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['activity_date' => 'An extra curriculum record already exists for this date.']);
        }

        ExtraCurriculum::updateOrCreate(['id' => $request->id], $validated);

        return redirect()->back()->with('success', 'Extra curriculum info updated successfully.');
    }


    public function destroy($id)
    {
        ExtraCurriculum::find($id)->delete();
        return response()->json(['message' => 'Extra Curriculum info deleted successfully']);
    }

    public function eca_index()
    {
        if (! isSuperAdmin() && ! isHeadOfficeEmp() || auth()->user()->hasRole('teacher')) {
            $branch_id = get_branch_id();
            $branches = Branch::where('id', $branch_id)->get();
        } else {
            $branches = Branch::all();
        }
        return view('students.extra_curriculum.eca_index', compact('branches'));
    }

    public function getStudentClassSection(Request $request)
    {
        if ($request->has('branch_id') && ! $request->has('class_id')) {
            // Get classes for selected branch
            $classes = BranchClass::with('com_classes')
                ->where('branch_id', $request->branch_id)
                ->get()
                ->pluck('com_classes')
                ->unique('id')
                ->values();

            return response()->json(['classes' => $classes]);
        }

        if ($request->has('branch_id') && $request->has('class_id') && ! $request->has('section_id')) {
            // Get sections for selected branch and class
            $sections = BranchClassSection::with('sections')
                ->where('branch_id', $request->branch_id)
                ->where('class_id', $request->class_id)
                ->get()
                ->pluck('sections')
                ->unique('id')
                ->values();

            return response()->json(['sections' => $sections]);
        }

        if ($request->has('branch_id') && $request->has('class_id') && $request->has('section_id')) {
            // Get students for selected branch, class, and section
            $students = ClassStudent::with('students')
                ->whereHas('branch_class_sections', function ($q) use ($request) {
                    $q->where('branch_id', $request->branch_id)
                        ->where('class_id', $request->class_id)
                        ->where('section_id', $request->section_id);
                })
                ->get()
                ->pluck('students');

            return response()->json(['students' => $students]);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }

    public function storeEca(Request $request)
    {
        $validated = $request->validate([
            'activity_name' => 'required|string|max:255',
            'activity_date' => 'required|date',
            'activity_type' => 'required|string|in:sports,cultural,academic,other',
            'remarks' => 'nullable|string|max:1000',
            'marks_type' => 'required|in:grade,marks',
            'custom_activity_name' => 'nullable|string|max:255',
            'grade' => 'nullable|string|in:A+,A,B,C,D',
            'total_marks' => 'nullable|numeric|min:0',
            'obtained_marks' => 'nullable|numeric|min:0|lte:total_marks',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'branch_id' => 'required|exists:branches,id',
            'class_id' => 'required|exists:branch_classes,id',
            'section_id' => 'required|exists:branch_class_sections,id',
            'student_id' => 'required|exists:students,id',
        ]);

        if ($validated['activity_type'] === 'other') {
            $request->validate([
                'custom_activity_name' => 'required|string|max:255',
            ]);
            $validated['activity_type'] = $validated['custom_activity_name'];
        }

        if ($validated['marks_type'] === 'grade') {
            $request->validate([
                'grade' => 'required|string|in:A+,A,B,C,D',
            ]);
            $validated['grade'] = $request->grade;
            $validated['total_marks'] = null;
            $validated['obtained_marks'] = null;
        } elseif ($validated['marks_type'] === 'marks') {
            $request->validate([
                'total_marks' => 'required|numeric|min:0',
                'obtained_marks' => 'required|numeric|min:0|lte:total_marks',
            ]);
            $validated['grade'] = null;
        }

        $validated['activity_date'] = date('Y-m-d', strtotime($validated['activity_date']));
        $validated['student_id'] = $request->student_id;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('extra_curriculam_attachments', $fileName, 'public');
            $validated['attachment'] = $filePath;
        }

        $existing = ExtraCurriculum::where('student_id', $request->student_id)
            ->where('activity_date', operator: $validated['activity_date'])
            ->first();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['activity_date' => 'An extra curriculum record already exists for this date.']);
        }

        ExtraCurriculum::create($validated);

        return redirect()->back()->with('success', 'Extra curriculum info created successfully.');
    }

    public function getEcaInfo(Request $request)
    {
        $eca = ExtraCurriculum::with([
            'student:id,first_name,middle_name,last_name,branch_id',
            'student.active_class:id,student_id,branch_class_section_id',
            'student.active_class.branch_class_sections:id,branch_id,class_id,section_id',
            'student.active_class.branch_class_sections.com_classes:id,class_name',
            'student.active_class.branch_class_sections.sections:id,section_name',
        ])->select([
                    'id',
                    'activity_name',
                    'activity_date',
                    'activity_type',
                    'marks_type',
                    'grade',
                    'total_marks',
                    'obtained_marks',
                    'remarks',
                    'attachment',
                    'created_at',
                    'student_id',
                ]);


        return datatables()->of($eca)
            ->addIndexColumn()
            ->addColumn('student_name', function ($row) {
                $first = $row->student->first_name ?? '';
                $middle = $row->student->middle_name ?? '';
                $last = $row->student->last_name ?? '';
                return trim("{$first} {$middle} {$last}");
            })
            ->addColumn('branch_name', function ($row) {
                return $row->student->branch->br_name ?? 'N/A';
            })

            ->addColumn('class', function ($row) {
                return $row->student->active_class->branch_class_sections->com_classes->class_name ?? 'N/A';
            })

            ->addColumn('section', function ($row) {
                return $row->student->active_class->branch_class_sections->sections->section_name ?? 'N/A';
            })

            ->addColumn('marks', function ($row) {
                return $row->marks_type === 'grade'
                    ? $row->grade
                    : ($row->obtained_marks . ' / ' . $row->total_marks);
            })

            ->addColumn('attachment', function ($row) {
                if ($row->attachment) {
                    $url = asset('storage/' . $row->attachment);
                    return '<a href="' . $url . '" target="_blank"><img src="' . $url . '" style="height: 40px; border-radius: 4px;" alt="Attachment"></a>';
                }
                return '<span class="text-muted">N/A</span>';
            })

            ->addColumn('action', function ($row) {
                return '<div class="d-flex gap-2">
                    <a href="' . route('extra-curriculum.getEcaInfoForEdit', $row->id) . '" class="edit btn btn-primary btn-sm">Edit</a>
                    <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-id="' . $row->id . '">Delete</a>
                </div>';
            })

            ->rawColumns(['action', 'attachment'])
            ->make(true);
    }

    public function getEcaInfoForEdit($id)
    {
        $eca = ExtraCurriculum::with([
            'student:id,first_name,middle_name,last_name,branch_id',
            'student.active_class:id,student_id,branch_class_section_id',
            'student.active_class.branch_class_sections:id,branch_id,class_id,section_id',
            'student.active_class.branch_class_sections.com_classes:id,class_name',
            'student.active_class.branch_class_sections.sections:id,section_name',
        ])->select([
                    'id',
                    'activity_name',
                    'activity_date',
                    'activity_type',
                    'marks_type',
                    'grade',
                    'total_marks',
                    'obtained_marks',
                    'remarks',
                    'attachment',
                    'created_at',
                    'student_id',
                ])->find($id);
        if (! isSuperAdmin() && ! isHeadOfficeEmp() || auth()->user()->hasRole('teacher')) {
            $branch_id = get_branch_id();
            $branches = Branch::where('id', $branch_id)->get();
        } else {
            $branches = Branch::all();
        }
        $classes = ComClass::all();
        $sections = Section::all();
        $class_students = ClassStudent::all();
        $eca_students = Student::all();
        //dd($eca->toArray());
        return view('students.extra_curriculum.edit_eca', compact('eca', 'branches', 'classes', 'sections', 'class_students', 'eca_students'));
    }

    public function updateEca(Request $request, $id)
    {
        $eca = ExtraCurriculum::findOrFail($id);

        $validated = $request->validate([
            'activity_name' => 'required|string|max:255',
            'activity_date' => 'required|date',
            'activity_type' => 'required|string|in:sports,cultural,academic,other',
            'remarks' => 'nullable|string|max:1000',
            'marks_type' => 'required|in:grade,marks',
            'custom_activity_name' => 'nullable|string|max:255',
            'grade' => 'nullable|string|in:A+,A,B,C,D',
            'total_marks' => 'nullable|numeric|min:0',
            'obtained_marks' => 'nullable|numeric|min:0|lte:total_marks',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'branch_id' => 'required|exists:branches,id',
            'class_id' => 'required|exists:branch_classes,id',
            'section_id' => 'required|exists:branch_class_sections,id',
            'student_id' => 'required|exists:students,id',
        ]);

        if ($validated['activity_type'] === 'other') {
            $request->validate([
                'custom_activity_name' => 'required|string|max:255',
            ]);
            $validated['activity_type'] = $validated['custom_activity_name'];
        }

        if ($validated['marks_type'] === 'grade') {
            $request->validate([
                'grade' => 'required|string|in:A+,A,B,C,D',
            ]);
            $validated['grade'] = $request->grade;
            $validated['total_marks'] = null;
            $validated['obtained_marks'] = null;
        } elseif ($validated['marks_type'] === 'marks') {
            $request->validate([
                'total_marks' => 'required|numeric|min:0',
                'obtained_marks' => 'required|numeric|min:0|lte:total_marks',
            ]);
            $validated['grade'] = null;
        }

        $validated['activity_date'] = date('Y-m-d', strtotime($validated['activity_date']));

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('extra_curriculam_attachments', $fileName, 'public');
            $validated['attachment'] = $filePath;
        }

        // Check for duplicates excluding current record
        $existing = ExtraCurriculum::where('student_id', $validated['student_id'])
            ->where('activity_date', $validated['activity_date'])
            ->where('id', '!=', $eca->id)
            ->first();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['activity_date' => 'An extra curriculum record already exists for this date.']);
        }

        $eca->update($validated);

        return redirect()->back()->with('success', 'Extra curriculum info updated successfully.');
    }
}
