<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassSubjectSettings\StoreClassRequest;
use App\Http\Requests\ClassSubjectSettings\StoreClassSubjectRequest;
use App\Http\Requests\ClassSubjectSettings\UpdateClassRequest;
use App\Http\Requests\ClassSubjectSettings\UpdateClassSubjectRequest;
use App\Http\Requests\SubjectSettings\StoreSubjectGroupRequest;
use App\Http\Requests\SubjectSettings\StoreSubjectRequest;
use App\Http\Requests\SubjectSettings\UpdateSubjectGroupRequest;
use App\Http\Requests\SubjectSettings\UpdateSubjectRequest;
use App\Models\ClassSubject;
use App\Models\ComClass;
use App\Models\Subject;
use App\Models\SubjectGroup;
use App\Services\ClassSubjectSettingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ClassSubjectSettingsController extends Controller
{
    public function __construct(
        private readonly ClassSubjectSettingsService $service
    ) {}

    /**
     * Display the unified class and subject settings page
     */
    public function index(): View
    {
        $classes = $this->service->getClasses();
        $subjects = $this->service->getSubjects();
        $subjectGroups = $this->service->getSubjectGroups();
        $languages = $this->service->getLanguages();
        $branches = $this->service->getBranches();
        $states = $this->service->getStates();
        $attendanceTypes = $this->service->getAttendanceTypes();

        return view('settings.class-subject.index', compact(
            'classes',
            'subjects',
            'subjectGroups',
            'languages',
            'branches',
            'states',
            'attendanceTypes'
        ));
    }

    /**
     * Get classes data for DataTable
     */
    public function getClasses(Request $request): JsonResponse
    {
        if (! $request->ajax()) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $data = ComClass::with('attendance_type');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('attendance_type_name', fn ($row) => $row->attendance_type?->name ?? 'N/A')
            ->addColumn('action', fn ($row) => $this->service->generateModalActionButtons($row->id, 'Class'))
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Get class subjects data for DataTable
     */
    public function getClassSubjects(Request $request): JsonResponse
    {
        if (! $request->ajax()) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $data = ClassSubject::with(['branch', 'class', 'subject', 'state']);

        if ($request->class_id && $request->class_id > 0) {
            $data = $data->where('class_id', $request->class_id);
        }

        if ($request->subject_id && $request->subject_id > 0) {
            $data = $data->where('subject_id', $request->subject_id);
        }

        if ($request->branch_id && $request->branch_id > 0) {
            $data = $data->where('branch_id', $request->branch_id);
        }

        if ($request->state_id && $request->state_id > 0) {
            $data = $data->where('state_id', $request->state_id);
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('class_name', fn ($row) => $row->class?->class_name ?? 'N/A')
            ->addColumn('subject_name', fn ($row) => $row->subject?->subject_name ?? 'N/A')
            ->addColumn('branch_name', fn ($row) => $row->branch?->br_name ?? 'N/A')
            ->addColumn('state_name', fn ($row) => $row->state?->state_name ?? 'N/A')
            ->addColumn('action', fn ($row) => $this->service->generateModalActionButtons($row->id, 'ClassSubject'))
            ->rawColumns(['action'])
            ->make(true);
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

        if ($request->subject_group_id && $request->subject_group_id > 0) {
            $data = $data->where('subject_group_id', $request->subject_group_id);
        }

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
     * Store a new class
     */
    public function storeClass(StoreClassRequest $request): JsonResponse
    {
        try {
            $class = $this->service->createClass($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Class created successfully.',
                'data' => $class,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create class', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create class. Please try again.',
            ], 500);
        }
    }

    /**
     * Update a class
     */
    public function updateClass(UpdateClassRequest $request, ComClass $class): JsonResponse
    {
        try {
            $class = $this->service->updateClass($class, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Class updated successfully.',
                'data' => $class,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update class', [
                'class_id' => $class->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update class. Please try again.',
            ], 500);
        }
    }

    /**
     * Delete a class
     */
    public function destroyClass(ComClass $class): JsonResponse
    {
        try {
            $deleted = $this->service->deleteClass($class);

            if (! $deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete class.',
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Class deleted successfully.',
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete class. It may be in use.',
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to delete class', [
                'class_id' => $class->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete class. Please try again.',
            ], 500);
        }
    }

    /**
     * Store a new class subject
     */
    public function storeClassSubject(StoreClassSubjectRequest $request): JsonResponse
    {
        try {
            $classSubject = $this->service->createClassSubject($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Class subject created successfully.',
                'data' => $classSubject,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create class subject', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create class subject. Please try again.',
            ], 500);
        }
    }

    /**
     * Update a class subject
     */
    public function updateClassSubject(UpdateClassSubjectRequest $request, ClassSubject $classSubject): JsonResponse
    {
        try {
            $classSubject = $this->service->updateClassSubject($classSubject, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Class subject updated successfully.',
                'data' => $classSubject,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update class subject', [
                'class_subject_id' => $classSubject->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update class subject. Please try again.',
            ], 500);
        }
    }

    /**
     * Delete a class subject
     */
    public function destroyClassSubject(ClassSubject $classSubject): JsonResponse
    {
        try {
            $deleted = $this->service->deleteClassSubject($classSubject);

            if (! $deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete class subject.',
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Class subject deleted successfully.',
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete class subject. It may be in use.',
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to delete class subject', [
                'class_subject_id' => $classSubject->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete class subject. Please try again.',
            ], 500);
        }
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
     * Get single class for editing
     */
    public function getClass(ComClass $class): JsonResponse
    {
        return response()->json($class->load('attendance_type'));
    }

    /**
     * Get single class subject for editing
     */
    public function getClassSubject(ClassSubject $classSubject): JsonResponse
    {
        return response()->json($classSubject->load(['branch', 'class', 'subject', 'state']));
    }

    /**
     * Get single subject for editing
     */
    public function getSubject(Subject $subject): JsonResponse
    {
        return response()->json($subject->load(['subject_group', 'language']));
    }

    /**
     * Get single subject group for editing
     */
    public function getSubjectGroup(SubjectGroup $subjectGroup): JsonResponse
    {
        return response()->json($subjectGroup);
    }
}
