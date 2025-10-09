<?php

namespace App\Http\Controllers;

use App\Exports\ExportTown;
use App\Imports\ImportTown;
use App\Models\AcademicYear;
use App\Models\Branch;
use App\Models\BranchClass;
use App\Models\City;
use App\Models\ClassSubject;
use App\Models\ComClass;
use App\Models\Curriculum;
use App\Models\CurriculumAttainmentTarget;
use App\Models\CurriculumCategory;
use App\Models\CurriculumType;
use App\Models\Region;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Town;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use DataTables;
use Excel;

class CurriculumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query = Curriculum::with(['curriculum_class', 'curriculum_subject']);
            // Check if class_id is present in the query string and filter accordingly
            if ($request->class_id && $request->class_id > 0) {
                $query = $query->where('class_id', $request->class_id);
            }

            // Check if subject_id is present in the query string and filter accordingly
            if ($request->subject_id && $request->subject_id > 0) {
                $query = $query->where('subject_id', $request->subject_id);
            }

            // Check if curriculum_type_id is present in the query string and filter accordingly
            if ($request->curriculum_type_id && $request->curriculum_type_id > 0) {
                $query = $query->where('curriculum_type_id', $request->curriculum_type_id);
            }

            $data = $query->get();
            //dd($data);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.curriculum.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $classes = ComClass::all();
        $subjects = Subject::all();
        $curriculum_types = CurriculumType::all();

        return view('settings.curriculum.index',
            [
                'classes' => $classes,
                'subjects' => $subjects,
                'curriculum_types' => $curriculum_types,
                //'branches' => $branches,
                //'academic_years' => $academic_years,
                //'regions' => $regions,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $classes = ComClass::all();
        $subjects = Subject::all();
        $curriculum_types = CurriculumType::all();

        return view('settings.curriculum.create',
            [
                'classes' => $classes,
                'subjects' => $subjects,
                'curriculum_types' => $curriculum_types,
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'class_id' => 'required',
            'subject_id' => 'required',
            'curriculum_category_id' => 'required',
            'description' => 'required_without:targets',
            'targets' => 'required_without:description',
        ]);

        //dd($request->all());

        // Define the conditions to find the existing record
        $conditions = [
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'curriculum_category_id' => $request->curriculum_category_id,
        ];

        // Define the data to insert or update
        if ($request->has('targets')) {
            $data = [
                'title' => '',
                'description' => '',
            ];
        }else{
            $data = [
                'title' => $request->title,
                'description' => $request->description,
            ];
        }

        // Attempt to update or insert the record
        $curriculumRecord = Curriculum::updateOrCreate($conditions, $data);

        if ($request->has('targets')) {

            //Update curriculum is_target flag to true
            $curriculumRecord->is_targets = 1;
            $curriculumRecord->save();

            //Remove existing targets, to add new
            CurriculumAttainmentTarget::where('curriculum_id', $curriculumRecord->id)->delete();

            // Handle the case where "targets" are submitted
            // Assuming you have a new model named "CurriculumTarget" for the new table
            foreach ($request->targets as $targetText) {
                // Store each target in the "CurriculumTarget" table
                //Skip if its empty
                if(!empty($targetText)) {
                    CurriculumAttainmentTarget::create([
                        'curriculum_id' => $curriculumRecord->id,
                        'target' => $targetText,
                    ]);
                }
            }
        }

        $selectedClass = $request->class_id;
        $selectedSubject = $request->subject_id;
        $selectedType = $request->type;
        $selectedCategory = $request->curriculum_category_id;

        return redirect()->route('show-curriculum', [
            'class' => $selectedClass,
            'subject' => $selectedSubject,
            'type' => $selectedType,
            'category' => $selectedCategory,
        ])->with('success', 'Curriculum Updated successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Curriculum $curriculum
     * @return \Illuminate\Http\Response
     */
    public function edit(Curriculum $curriculum)
    {
        $curriculum = Curriculum::get();
        return view('settings.curriculum.edit', ['curriculum' => $curriculum, 'cities' => $curriculum]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Curriculum $curriculum
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Curriculum $curriculum)
    {
        /*$request->validate([
            'town_name' => 'required|unique:towns,town_name,' . $curriculum->id,
            'city_id' => 'required',
        ]);

        $curriculum->update($request->all());

        return redirect()->route('curriculum.index')
            ->with('success', 'Curriculum updated successfully.');*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Curriculum $curriculum
     * @return \Illuminate\Http\Response
     */
    public function destroy(Curriculum $curriculum)
    {
        try {
            return $curriculum->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Curriculum $curriculum
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //$classSubjects = ClassSubject::with(['class', 'subject'])->get();
        //$groupedClassSubjects = ClassSubject::with(['class', 'subject'])->groupBy('class_id')->get();
        $selectedClass = $selectedSubject = $selectedType = $selectedCategory =
        $curriculumCategories = $curriculumDetails = $currentCurriculumCategory =
        $attainmentTargets = $currentClass = $currentSubject = $currentCurriculumType = null;
        $curriculumDescription = '';

        $curriculumTypes = CurriculumType::all();
        $classes = ComClass::all();
        $groupedClassSubjects = collect();

        foreach ($classes as $class) {
            $classSubjects = ClassSubject::with('subject')->where('class_id', $class->id)->get();

                $groupedClassSubjects->push([
                    'class' => $class,
                    'subjects' => $classSubjects,
                ]);
        }
        //dd($groupedClassSubjects);

        if($request->has('class')){
            $selectedClass = $request->class;
            $currentClass = ComClass::find($selectedClass);
        }
        if($request->has('subject')){
            $selectedSubject = $request->subject;
            $currentSubject = Subject::find($selectedSubject);
        }
        if($request->has('type')){
            $selectedType = $request->type;
            $curriculumCategories = CurriculumCategory::where('curriculum_type_id', $selectedType)->get();
            $currentCurriculumType = CurriculumType::find($selectedType);
        }
        if($request->has('category')){
            $selectedCategory = $request->category;
            $curriculumDetails = Curriculum::where('class_id', $selectedClass)
            ->where('subject_id', $selectedSubject)
            ->where('curriculum_category_id', $selectedCategory)
                ->first();
            if($curriculumDetails)
                $curriculumDescription = $curriculumDetails->description;
            else
                $curriculumDescription = '';
            //Get current selected category details
            $currentCurriculumCategory = CurriculumCategory::findOrFail($selectedCategory);

            //Get attainment targets, if curriculum contains targets
            if( isset($curriculumDetails->is_targets) && ($curriculumDetails->is_targets == 1) ){
                $attainmentTargets = CurriculumAttainmentTarget::where('curriculum_id', $curriculumDetails->id)->get();
            }
        }else{
            $curriculumDescription = 'Welcome to Online Curriculum Centre. Please select category from left to show curriculum.';
        }

        //dd($groupedClassSubjects);

        return view('curriculum.view',
            [
                'groupedClassSubjects' => $groupedClassSubjects,
                'curriculumTypes' => $curriculumTypes,
                //'selectedType' => $selectedType,
                //'selectedClass' => $selectedClass,
                //'selectedSubject' => $selectedSubject,
                //'selectedCategory' => $selectedCategory,
                'curriculumCategories' => $curriculumCategories,
                'curriculumDetails' => $curriculumDetails,
                'curriculumDescription' => $curriculumDescription,
                'currentCurriculumCategory' => $currentCurriculumCategory,
                'attainmentTargets' => $attainmentTargets,

                'currentClass' => $currentClass,
                'currentSubject' => $currentSubject,
                'currentCurriculumType' => $currentCurriculumType,
            ]
        );
    }
}
