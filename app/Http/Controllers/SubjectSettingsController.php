<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectSettings\StoreSubjectGroupRequest;
use App\Http\Requests\SubjectSettings\StoreSubjectRequest;
use App\Http\Requests\SubjectSettings\UpdateSubjectGroupRequest;
use App\Http\Requests\SubjectSettings\UpdateSubjectRequest;
use App\Models\Subject;
use App\Models\SubjectGroup;
use App\Services\SubjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class SubjectSettingsController extends Controller
{
    public function __construct(
        private readonly SubjectService $service
    ) {}

    /**
     * Display the unified subject settings page
     */
    public function index(): View
    {
        $subjectGroups = $this->service->getSubjectGroups();
        $languages = $this->service->getLanguages();

        return view('settings.subjects.index', compact('subjectGroups', 'languages'));
    }

    /**
     * Get subjects data for DataTable
     */
    public function getSubjects(Request $request): JsonResponse
    {
        if (! $request->ajax()) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $data = Subject::with(['subject_group', 'language']);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('is_academic_display', fn ($row) => $row->is_academic ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>')
            ->addColumn('language_name', fn ($row) => $row->language?->language_name ?? 'N/A')
            ->addColumn('subject_group_name', fn ($row) => $row->subject_group?->subject_group_name ?? 'N/A')
            ->addColumn('action', fn ($row) => $this->service->generateModalActionButtons($row->id, 'Subject'))
            ->rawColumns(['action', 'is_academic_display'])
            ->make(true);
    }

    /**
     * Get subject groups data for DataTable
     */
    public function getSubjectGroups(Request $request): JsonResponse
    {
        if (! $request->ajax()) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $data = SubjectGroup::query();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', fn ($row) => $this->service->generateModalActionButtons($row->id, 'SubjectGroup'))
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Store a new subject
     */
    public function storeSubject(StoreSubjectRequest $request): JsonResponse
    {
        try {
            $subject = $this->service->createSubject($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Subject created successfully.',
                'data' => $subject,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create subject', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create subject. Please try again.',
            ], 500);
        }
    }

    /**
     * Update a subject
     */
    public function updateSubject(UpdateSubjectRequest $request, Subject $subject): JsonResponse
    {
        try {
            $subject = $this->service->updateSubject($subject, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Subject updated successfully.',
                'data' => $subject,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update subject', [
                'subject_id' => $subject->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update subject. Please try again.',
            ], 500);
        }
    }

    /**
     * Delete a subject
     */
    public function destroySubject(Subject $subject): JsonResponse
    {
        try {
            $deleted = $this->service->deleteSubject($subject);

            if (! $deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete subject.',
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Subject deleted successfully.',
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete subject. It may be in use.',
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to delete subject', [
                'subject_id' => $subject->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete subject. Please try again.',
            ], 500);
        }
    }

    /**
     * Store a new subject group
     */
    public function storeSubjectGroup(StoreSubjectGroupRequest $request): JsonResponse
    {
        try {
            $subjectGroup = $this->service->createSubjectGroup($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Subject group created successfully.',
                'data' => $subjectGroup,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create subject group', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create subject group. Please try again.',
            ], 500);
        }
    }

    /**
     * Update a subject group
     */
    public function updateSubjectGroup(UpdateSubjectGroupRequest $request, SubjectGroup $subjectGroup): JsonResponse
    {
        try {
            $subjectGroup = $this->service->updateSubjectGroup($subjectGroup, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Subject group updated successfully.',
                'data' => $subjectGroup,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update subject group', [
                'subject_group_id' => $subjectGroup->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update subject group. Please try again.',
            ], 500);
        }
    }

    /**
     * Delete a subject group
     */
    public function destroySubjectGroup(SubjectGroup $subjectGroup): JsonResponse
    {
        try {
            $deleted = $this->service->deleteSubjectGroup($subjectGroup);

            if (! $deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete subject group.',
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Subject group deleted successfully.',
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete subject group. It may be in use.',
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to delete subject group', [
                'subject_group_id' => $subjectGroup->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete subject group. Please try again.',
            ], 500);
        }
    }

    /**
     * Get single subject for editing
     */
    public function getSubject(Subject $subject): JsonResponse
    {
        $subject = $subject->load(['subject_group', 'language']);

        return response()->json($subject);
    }

    /**
     * Get single subject group for editing
     */
    public function getSubjectGroup(SubjectGroup $subjectGroup): JsonResponse
    {
        return response()->json($subjectGroup);
    }
}
