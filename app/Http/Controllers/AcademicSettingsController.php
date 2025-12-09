<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\AcademicYear;
use App\Models\BranchAcademicYear;
use App\Models\StudentPreviousSchool;
use App\Models\Branch;
use App\Services\AcademicSettingsService;
use App\Http\Requests\AcademicSettings\StoreLanguageRequest;
use App\Http\Requests\AcademicSettings\UpdateLanguageRequest;
use App\Http\Requests\AcademicSettings\StoreAcademicYearRequest;
use App\Http\Requests\AcademicSettings\UpdateAcademicYearRequest;
use App\Http\Requests\AcademicSettings\StoreBranchAcademicYearRequest;
use App\Http\Requests\AcademicSettings\UpdateBranchAcademicYearRequest;
use App\Http\Requests\AcademicSettings\StoreStudentPreviousSchoolRequest;
use App\Http\Requests\AcademicSettings\UpdateStudentPreviousSchoolRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class AcademicSettingsController extends Controller
{
    public function __construct(
        private readonly AcademicSettingsService $service
    ) {}

    /**
     * Display the unified academic settings page
     */
    public function index(): View
    {
        $academicYears = $this->service->getAcademicYears();
        $branches = $this->service->getBranches();
        
        return view('settings.academic.index', compact('academicYears', 'branches'));
    }

    /**
     * Get languages data for DataTable
     */
    public function getLanguages(Request $request): JsonResponse
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $data = Language::query();
        
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', fn($row) => $this->service->generateModalActionButtons($row->id, 'Language'))
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Get academic years data for DataTable
     */
    public function getAcademicYears(Request $request): JsonResponse
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $data = AcademicYear::query();
        
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('active_status', fn($row) => $row->active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>')
            ->addColumn('action', fn($row) => $this->service->generateModalActionButtons($row->id, 'AcademicYear'))
            ->rawColumns(['action', 'active_status'])
            ->make(true);
    }

    /**
     * Get branch academic years data for DataTable
     */
    public function getBranchAcademicYears(Request $request): JsonResponse
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $data = BranchAcademicYear::with(['branch', 'academic_year']);
        
        if ($request->branch_id && $request->branch_id > 0) {
            $data = $data->where('branch_id', $request->branch_id);
        }
        
        if ($request->academic_year_id && $request->academic_year_id > 0) {
            $data = $data->where('academic_year_id', $request->academic_year_id);
        }
        
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('br_name', fn($row) => $row->branch?->br_name ?? 'N/A')
            ->addColumn('academic_year_title', fn($row) => $row->academic_year?->title ?? 'N/A')
            ->addColumn('start_date_formatted', function($row) {
                if (!$row->start_date) return 'N/A';
                return is_string($row->start_date) 
                    ? Carbon::parse($row->start_date)->format('Y-m-d')
                    : $row->start_date->format('Y-m-d');
            })
            ->addColumn('end_date_formatted', function($row) {
                if (!$row->end_date) return 'N/A';
                return is_string($row->end_date) 
                    ? Carbon::parse($row->end_date)->format('Y-m-d')
                    : $row->end_date->format('Y-m-d');
            })
            ->addColumn('action', fn($row) => $this->service->generateModalActionButtons($row->id, 'BranchAcademicYear'))
            ->filterColumn('branch_name', function($query, $keyword) {
                $query->whereHas('branch', function($q) use ($keyword) {
                    $q->where('br_name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('academic_year_title', function($query, $keyword) {
                $query->whereHas('academic_year', function($q) use ($keyword) {
                    $q->where('title', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('start_date', function($query, $keyword) {
                $query->whereDate('start_date', 'like', "%{$keyword}%");
            })
            ->filterColumn('end_date', function($query, $keyword) {
                $query->whereDate('end_date', 'like', "%{$keyword}%");
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Get student previous schools data for DataTable
     */
    public function getStudentPreviousSchools(Request $request): JsonResponse
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $data = StudentPreviousSchool::query();
        
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', fn($row) => $this->service->generateModalActionButtons($row->id, 'StudentPreviousSchool'))
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Store a new language
     */
    public function storeLanguage(StoreLanguageRequest $request): JsonResponse
    {
        try {
            $language = $this->service->createLanguage($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Language created successfully.',
                'data' => $language
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create language', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create language. Please try again.'
            ], 500);
        }
    }

    /**
     * Update a language
     */
    public function updateLanguage(UpdateLanguageRequest $request, Language $language): JsonResponse
    {
        try {
            $language = $this->service->updateLanguage($language, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Language updated successfully.',
                'data' => $language
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update language', [
                'language_id' => $language->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update language. Please try again.'
            ], 500);
        }
    }

    /**
     * Delete a language
     */
    public function destroyLanguage(Language $language): JsonResponse
    {
        try {
            $deleted = $this->service->deleteLanguage($language);
            
            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete language.'
                ], 422);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Language deleted successfully.'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete language. It may be in use.'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to delete language', [
                'language_id' => $language->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete language. Please try again.'
            ], 500);
        }
    }

    /**
     * Store a new academic year
     */
    public function storeAcademicYear(StoreAcademicYearRequest $request): JsonResponse
    {
        try {
            $academicYear = $this->service->createAcademicYear($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Academic year created successfully.',
                'data' => $academicYear
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create academic year', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create academic year. Please try again.'
            ], 500);
        }
    }

    /**
     * Update an academic year
     */
    public function updateAcademicYear(UpdateAcademicYearRequest $request, AcademicYear $academicYear): JsonResponse
    {
        try {
            $academicYear = $this->service->updateAcademicYear($academicYear, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Academic year updated successfully.',
                'data' => $academicYear
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update academic year', [
                'academic_year_id' => $academicYear->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update academic year. Please try again.'
            ], 500);
        }
    }

    /**
     * Delete an academic year
     */
    public function destroyAcademicYear(AcademicYear $academicYear): JsonResponse
    {
        try {
            $deleted = $this->service->deleteAcademicYear($academicYear);
            
            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete academic year.'
                ], 422);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Academic year deleted successfully.'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete academic year. It may be in use.'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to delete academic year', [
                'academic_year_id' => $academicYear->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete academic year. Please try again.'
            ], 500);
        }
    }

    /**
     * Store a new branch academic year
     */
    public function storeBranchAcademicYear(StoreBranchAcademicYearRequest $request): JsonResponse
    {
        try {
            $branchAcademicYear = $this->service->createBranchAcademicYear($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Branch academic year created successfully.',
                'data' => $branchAcademicYear
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create branch academic year', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create branch academic year. Please try again.'
            ], 500);
        }
    }

    /**
     * Update a branch academic year
     */
    public function updateBranchAcademicYear(UpdateBranchAcademicYearRequest $request, BranchAcademicYear $branchAcademicYear): JsonResponse
    {
        try {
            $branchAcademicYear = $this->service->updateBranchAcademicYear($branchAcademicYear, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Branch academic year updated successfully.',
                'data' => $branchAcademicYear
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update branch academic year', [
                'branch_academic_year_id' => $branchAcademicYear->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update branch academic year. Please try again.'
            ], 500);
        }
    }

    /**
     * Delete a branch academic year
     */
    public function destroyBranchAcademicYear(BranchAcademicYear $branchAcademicYear): JsonResponse
    {
        try {
            $deleted = $this->service->deleteBranchAcademicYear($branchAcademicYear);
            
            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete branch academic year.'
                ], 422);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Branch academic year deleted successfully.'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete branch academic year. It may be in use.'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to delete branch academic year', [
                'branch_academic_year_id' => $branchAcademicYear->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete branch academic year. Please try again.'
            ], 500);
        }
    }

    /**
     * Store a new student previous school
     */
    public function storeStudentPreviousSchool(StoreStudentPreviousSchoolRequest $request): JsonResponse
    {
        try {
            $studentPreviousSchool = $this->service->createStudentPreviousSchool($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Student previous school created successfully.',
                'data' => $studentPreviousSchool
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create student previous school', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create student previous school. Please try again.'
            ], 500);
        }
    }

    /**
     * Update a student previous school
     */
    public function updateStudentPreviousSchool(UpdateStudentPreviousSchoolRequest $request, StudentPreviousSchool $studentPreviousSchool): JsonResponse
    {
        try {
            $studentPreviousSchool = $this->service->updateStudentPreviousSchool($studentPreviousSchool, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Student previous school updated successfully.',
                'data' => $studentPreviousSchool
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update student previous school', [
                'student_previous_school_id' => $studentPreviousSchool->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update student previous school. Please try again.'
            ], 500);
        }
    }

    /**
     * Delete a student previous school
     */
    public function destroyStudentPreviousSchool(StudentPreviousSchool $studentPreviousSchool): JsonResponse
    {
        try {
            $deleted = $this->service->deleteStudentPreviousSchool($studentPreviousSchool);
            
            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete student previous school.'
                ], 422);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Student previous school deleted successfully.'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete student previous school. It may be in use.'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to delete student previous school', [
                'student_previous_school_id' => $studentPreviousSchool->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete student previous school. Please try again.'
            ], 500);
        }
    }

    /**
     * Get single language for editing
     */
    public function getLanguage(Language $language): JsonResponse
    {
        return response()->json($language);
    }

    /**
     * Get single academic year for editing
     */
    public function getAcademicYear(AcademicYear $academicYear): JsonResponse
    {
        return response()->json($academicYear);
    }

    /**
     * Get single branch academic year for editing
     */
    public function getBranchAcademicYear(BranchAcademicYear $branchAcademicYear): JsonResponse
    {
        $branchAcademicYear = $branchAcademicYear->load(['branch', 'academic_year']);
        
        // Format dates for frontend
        $data = $branchAcademicYear->toArray();
        if ($branchAcademicYear->start_date instanceof \Carbon\Carbon) {
            $data['start_date'] = $branchAcademicYear->start_date->format('Y-m-d');
        } elseif ($branchAcademicYear->start_date && is_string($branchAcademicYear->start_date)) {
            try {
                $data['start_date'] = Carbon::parse($branchAcademicYear->start_date)->format('Y-m-d');
            } catch (\Exception $e) {
                // Keep original if parsing fails
            }
        }
        
        if ($branchAcademicYear->end_date instanceof \Carbon\Carbon) {
            $data['end_date'] = $branchAcademicYear->end_date->format('Y-m-d');
        } elseif ($branchAcademicYear->end_date && is_string($branchAcademicYear->end_date)) {
            try {
                $data['end_date'] = Carbon::parse($branchAcademicYear->end_date)->format('Y-m-d');
            } catch (\Exception $e) {
                // Keep original if parsing fails
            }
        }
        
        return response()->json($data);
    }

    /**
     * Get single student previous school for editing
     */
    public function getStudentPreviousSchool(StudentPreviousSchool $studentPreviousSchool): JsonResponse
    {
        return response()->json($studentPreviousSchool);
    }

    /**
     * Get school description for AJAX requests
     */
    public function getSchoolDescription(Request $request)
    {
        if ($request->ajax()) {
            $school = StudentPreviousSchool::find($request->previous_school_id);
            return $school ? $school->description : '';
        }
    }
}

