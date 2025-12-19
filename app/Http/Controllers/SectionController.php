<?php

namespace App\Http\Controllers;

use App\Exports\ExportSection;
use App\Imports\ImportSection;
use App\Models\Section;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use DataTables;
use Excel;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Section::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.sections.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('settings.sections.sections');
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
            'section_name' => 'required|unique:sections,section_name',
            'abbreviation' => 'required',
        ]);

        Section::create($request->all());

        return redirect()->route('sections.index')
            ->with('success', 'Section created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Section $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Section $section
     * @return \Illuminate\Http\Response
     */
    public function edit(Section $section)
    {
        return view('settings.sections.sections', ['section' => $section]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Section $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Section $section)
    {
        $request->validate([
            'section_name' => 'required|unique:sections,section_name,' . $section->id,
            'abbreviation' => 'required',
        ]);

        $section->update($request->all());

        return redirect()->route('sections.index')
            ->with('success', 'Section updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Section $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section $section)
    {
        try {
            return $section->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function importSection(Request $request)
    {
        Excel::import(new ImportSection(), $request->file('file')->store('files'));
        return redirect()->back();
    }

    public function exportSection(Request $request)
    {
        return Excel::download(new ExportSection(), 'sections.xlsx');
    }
}
