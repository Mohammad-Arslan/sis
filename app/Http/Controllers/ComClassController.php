<?php

namespace App\Http\Controllers;

use App\Exports\ExportClass;
use App\Models\AttendanceType;
use App\Models\ComClass;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Imports\ImportClass;
use Excel;

class ComClassController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ComClass::with(['attendance_type'])->get();
            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('attendance_type', function ($row) {
                    return isset($row['attendance_type']) ? $row['attendance_type']['name'] : '-';
                })
                ->addColumn('action', function ($row) {
                    return view('settings.classes.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $data['attendance_types'] = AttendanceType::all();

        return view('settings.classes.classes',$data);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'class_name' => 'required|unique:com_classes,class_name',
            'abbreviation' => 'required',
            'attendance_type_id' => 'required',
            'sort' => 'required|integer|min:1|unique:com_classes,sort',
        ]);

        ComClass::create($request->all());

        return redirect()->route('classes.index')
            ->with('success', 'Class created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\ComClass $comClass
     * @return \Illuminate\Http\Response
     */
    public function show(ComClass $comClass)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\ComClass $comClass
     * @return \Illuminate\Http\Response
     */
    public function edit(ComClass $class)
    {
//        dd($class->toArray());
        $data['attendance_types'] = AttendanceType::all();
        $data['comClass'] = $class;

        return view('settings.classes.classes', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ComClass $comClass
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ComClass $class)
    {
        $request->validate([
            'class_name' => 'required|unique:com_classes,class_name,' . $class->id,
            'abbreviation' => 'required',
            'attendance_type_id' => 'required',
            'sort' => 'required|integer|min:1|unique:com_classes,sort,' . $class->id,
        ]);

        $class->update($request->all());

        return redirect()->route('classes.index')
            ->with('success', 'Class updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\ComClass $comClass
     * @return \Illuminate\Http\Response
     */
    public function destroy(ComClass $class)
    {
        try {
            return $class->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function importClass(Request $request)
    {
        Excel::import(new ImportClass, $request->file('file')->store('files'));
        return redirect()->back();
    }

    public function exportClass(Request $request)
    {
        return Excel::download(new ExportClass, 'classes.xlsx');
    }




}
