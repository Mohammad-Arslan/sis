<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectSettings\StoreSubjectRequest;
use App\Http\Requests\SubjectSettings\UpdateSubjectRequest;
use App\Models\Subject;
use App\Services\SubjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class SubjectController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        public SubjectService $subjectService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $data = $this->subjectService->getSubjectsForDataTable();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return $this->subjectService->generateRouteActionButtons(
                        id: $row->id,
                        routePrefix: 'subjects',
                        options: [
                            'data_table' => 'subjects-data-table',
                        ]
                    );
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $subjectGroups = $this->subjectService->getSubjectGroups();
        $languages = $this->subjectService->getLanguages();

        return view('settings.subjects.subjects', [
            'subjectGroups' => $subjectGroups,
            'languages' => $languages,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $subjectGroups = $this->subjectService->getSubjectGroups();
        $languages = $this->subjectService->getLanguages();

        return view('settings.subjects.subjects', [
            'subjectGroups' => $subjectGroups,
            'languages' => $languages,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubjectRequest $request): RedirectResponse
    {
        $this->subjectService->createSubject($request->validated());

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject): View
    {
        $subject = $this->subjectService->getSubjectById($subject->id);

        return view('settings.subjects.show', [
            'subject' => $subject,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject): View
    {
        $subject = $this->subjectService->getSubjectById($subject->id);
        $subjectGroups = $this->subjectService->getSubjectGroups();
        $languages = $this->subjectService->getLanguages();

        return view('settings.subjects.subjects', [
            'subject' => $subject,
            'subjectGroups' => $subjectGroups,
            'languages' => $languages,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubjectRequest $request, Subject $subject): RedirectResponse
    {
        $this->subjectService->updateSubject($subject, $request->validated());

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject): JsonResponse|bool
    {
        try {
            $deleted = $this->subjectService->deleteSubject($subject);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Subject deleted successfully.',
                ]);
            }

            return $deleted;
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete subject.',
                    'error' => $e->getMessage(),
                ], 422);
            }

            throw $e;
        }
    }
}
