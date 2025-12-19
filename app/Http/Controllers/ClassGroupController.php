<?php

namespace App\Http\Controllers;

use App\Models\ClassGroup;
use App\Models\ClassGroupClass;
use App\Models\ComClass;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use DataTables;

class ClassGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ClassGroup::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.class_groups.actions', ['row' => $row]);
                })
                ->rawColumns(['action', 'add'])
                ->make(true);
        }

        return view('settings.class_groups.class_groups');
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
            'name' => 'required|unique:class_groups,name',
        ]);

        ClassGroup::create($request->all());

        return redirect()->route('class-groups.index')
            ->with('success', 'Class group created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\ClassGroup $classGroup
     * @return \Illuminate\Http\Response
     */
    public function show(ClassGroup $classGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\ClassGroup $classGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(ClassGroup $classGroup)
    {
        //        dd($classGroup->toArray());
        return view('settings.class_groups.class_groups', ['classGroup' => $classGroup]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ClassGroup $classGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClassGroup $classGroup)
    {
        $request->validate([
            'name' => 'required|unique:class_groups,name,' . $classGroup->id,
        ]);

        $classGroup->update($request->all());

        return redirect()->route('class-groups.index')
            ->with('success', 'Class Group updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\ClassGroup $classGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClassGroup $classGroup)
    {
        try {
            return $classGroup->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function showClasses(Request $request)
    {
        $id = $request->id;
        $classes = ComClass::get();
        $class_groups_classes = ClassGroupClass::where('class_group_id', '=', $id)
            ->where('status', '=', '1')
            ->pluck('class_id')->toArray();
        return view('settings.class_groups.classes_modal', [
            'classes' => $classes,
            'class_group_classes' => $class_groups_classes,
            'class_group_id' => $id
        ]);
    }

    public function addGroupClasses(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'class_group_id' => 'required',
        ]);
        $data = ClassGroupClass::where('class_id', '=', $request->class_id)
            ->where('class_group_id', '=', $request->class_group_id)->first();
        if ($data !== null) {
            if ($data->status == '0') {
                $data->status = '1';
            } else {
                $data->status = '0';
            }
            $data->save();
        } else {
            ClassGroupClass::create([
                'class_id' => $request->class_id,
                'class_group_id' => $request->class_group_id,
                'status' => '1'
            ]);
        }
    }
}
