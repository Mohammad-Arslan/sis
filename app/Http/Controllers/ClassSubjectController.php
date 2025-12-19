<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\ClassStudent;
use App\Models\ClassSubject;
use App\Models\ComClass;
use App\Models\State;
use App\Models\Subject;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ClassSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ClassSubject::with(['branch', 'class', 'subject', 'state'])->orderBy('id', 'desc');
            //dd($data->get()->toArray());
            if ($request->class_id && $request->class_id > 0) {
                $data = $data->whereHas('class', function ($query) use ($request) {
                    $query->where('id', $request->class_id);
                });
            }

            if ($request->subject_id && $request->subject_id > 0) {
                $data = $data->whereHas('subject', function ($query) use ($request) {
                    $query->where('id', $request->subject_id);
                });
            }


            if ($request->state_id && $request->state_id > 0) {
                $data = $data->whereHas('state', function ($query) use ($request) {
                    $query->where('id', $request->state_id);
                });
            }
            if ($request->searchName && $request->searchName != null) {
                //dd($request->searchName);

                $data = $data->orWhereHas('branch', function ($query) use ($request) {
                    $query->where('br_name', 'like', '' . $request->searchName . '%');
                });

                $data = $data->orWhereHas('class', function ($query) use ($request) {
                    $query->where('class_name', 'like', '' . $request->searchName . '%');
                });

                $data = $data->orWhereHas('subject', function ($query) use ($request) {
                    $query->where('subject_name', 'like', '' . $request->searchName . '%');
                });

                $data = $data->orWhereHas('state', function ($query) use ($request) {
                     $query->where('state_name', 'like', '' . $request->searchName . '%');
                });
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('branch', function ($row) {
                    return isset($row->branch) ? $row->branch->br_name : '';
                })
                ->addColumn('state', function ($row) {
                    return isset($row->state) ? $row->state->state_name : '';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('class-subjects.edit', $row->id) . ' " class="btn btn-sm btn-success btn-icon waves-effect waves-light">
                                <i class="mdi mdi-lead-pencil"></i>
                            </a>
                            <a href="' . route('class-subjects.destroy', $row->id) . '" class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record" data-table="class-subject-datatable" data-id = "' . $row->id . '">
                                <i class="ri-delete-bin-line"></i>
                            </a>';
                    return $btn;
                })
                ->rawColumns(['action','state','branch'])
                ->make(true);
        }

        $branches = Branch::all();
        $com_classes = ComClass::all();
        $subjects = Subject::all();
        $states = State::all();

        $data = [
            'branches' => $branches,
            'com_classes' => $com_classes,
            'subjects' => $subjects,
            'states' => $states
        ];

        return view('class_subjects.class_subjects', $data);
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
        //dd($request->all());
        // Validate the request data
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'class_id' => 'required|exists:com_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'state_id' => 'required|exists:states,id'
        ], [
            'branch_id.required' => 'Branch is required.',
            'branch_id.exists' => 'Selected branch does not exist.',
            'class_id.required' => 'Class is required.',
            'class_id.exists' => 'Selected class does not exist.',
            'subject_id.required' => 'Subject is required.',
            'subject_id.exists' => 'Selected subject does not exist.',
            'state_id.required' => 'State is required.',
            'state_id.exists' => 'Selected state does not exist.'
        ]);

        try {
            ClassSubject::create($request->all());
            return redirect()->back()->with('success', 'Class subject added successfully');
        } catch (QueryException $e) {
            // Handle foreign key constraint violations
            if ($e->errorInfo[1] == 1452) {
                $errorMessage = 'Cannot create class subject: ';
                if (strpos($e->getMessage(), 'class_id_foreign') !== false) {
                    $errorMessage .= 'Selected class does not exist.';
                } elseif (strpos($e->getMessage(), 'branch_id_foreign') !== false) {
                    $errorMessage .= 'Selected branch does not exist.';
                } elseif (strpos($e->getMessage(), 'subject_id_foreign') !== false) {
                    $errorMessage .= 'Selected subject does not exist.';
                } elseif (strpos($e->getMessage(), 'state_id_foreign') !== false) {
                    $errorMessage .= 'Selected state does not exist.';
                } else {
                    $errorMessage .= 'Invalid reference to related record.';
                }
                return redirect()->back()->with('error', $errorMessage)->withInput();
            }

            // Handle other database errors
            return redirect()->back()->with('error', 'An error occurred while creating the class subject. Please try again.')->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred. Please try again.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClassSubject  $classSubject
     * @return \Illuminate\Http\Response
     */
    public function show(ClassSubject $classSubject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ClassSubject  $classSubject
     * @return \Illuminate\Http\Response
     */
    public function edit(ClassSubject $classSubject)
    {

        $branches = Branch::all();
        $com_classes = ComClass::all();
        $subjects = Subject::all();
        $states = State::all();

        $data = [
            'branches' => $branches,
            'com_classes' => $com_classes,
            'subjects' => $subjects,
            'states' => $states,
            'classSubject' => $classSubject,
        ];

        return view('class_subjects.class_subjects', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClassSubject  $classSubject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClassSubject $classSubject)
    {
        // Validate the request data
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'class_id' => 'required|exists:com_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'state_id' => 'required|exists:states,id'
        ], [
            'branch_id.required' => 'Branch is required.',
            'branch_id.exists' => 'Selected branch does not exist.',
            'class_id.required' => 'Class is required.',
            'class_id.exists' => 'Selected class does not exist.',
            'subject_id.required' => 'Subject is required.',
            'subject_id.exists' => 'Selected subject does not exist.',
            'state_id.required' => 'State is required.',
            'state_id.exists' => 'Selected state does not exist.'
        ]);

        try {
            $classSubject->update($request->all());
            return redirect()->back()->with('success', 'Class subject updated successfully');
        } catch (QueryException $e) {
            // Handle foreign key constraint violations
            if ($e->errorInfo[1] == 1452) {
                $errorMessage = 'Cannot update class subject: ';
                if (strpos($e->getMessage(), 'class_id_foreign') !== false) {
                    $errorMessage .= 'Selected class does not exist.';
                } elseif (strpos($e->getMessage(), 'branch_id_foreign') !== false) {
                    $errorMessage .= 'Selected branch does not exist.';
                } elseif (strpos($e->getMessage(), 'subject_id_foreign') !== false) {
                    $errorMessage .= 'Selected subject does not exist.';
                } elseif (strpos($e->getMessage(), 'state_id_foreign') !== false) {
                    $errorMessage .= 'Selected state does not exist.';
                } else {
                    $errorMessage .= 'Invalid reference to related record.';
                }
                return redirect()->back()->with('error', $errorMessage)->withInput();
            }

            // Handle other database errors
            return redirect()->back()->with('error', 'An error occurred while updating the class subject. Please try again.')->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred. Please try again.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClassSubject  $classSubject
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClassSubject $classSubject)
    {
        try {
            $classSubject->delete();
            return response()->json(['success' => true, 'message' => 'Class subject deleted successfully']);
        } catch (QueryException $e) {
            // Handle foreign key constraint violations (e.g., if this class subject is referenced elsewhere)
            if ($e->errorInfo[1] == 1451) {
                return response()->json(['success' => false, 'message' => 'Cannot delete class subject: It is being used by other records.'], 400);
            }

            // Handle other database errors
            return response()->json(['success' => false, 'message' => 'An error occurred while deleting the class subject.'], 500);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An unexpected error occurred.'], 500);
        }
    }
}
