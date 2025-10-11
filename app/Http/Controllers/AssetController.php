<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Supplier;
use App\Models\Branch;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use App\Jobs\ProcessAssetImport;
use App\Imports\ImportAsset;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetTemplateExport;

class AssetController extends Controller
{
    /**
     * Display a listing of assets.
     */
    public function index(Request $request)
    {
        $query = Asset::with(['category', 'supplier', 'currentBranch', 'currentDepartment', 'assignedTo'])
            ->orderBy('created_at', 'desc');
            
        // Apply filters
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('asset_tag', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('status') && $request->input('status')) {
            $query->where('status', $request->input('status'));
        }
        
        if ($request->has('category') && $request->input('category')) {
            $query->where('category_id', $request->input('category'));
        }
        
        $assets = $query->paginate(15);
        
        // Append query parameters to pagination links
        $assets->appends($request->all());

        // Get data for filters and edit modal
        $categories = AssetCategory::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $branches = Branch::orderBy('br_name')->get();
        $departments = Department::orderBy('department_name')->get();
        $users = User::with(['employee.department', 'employee.branch'])
            ->whereHas('employee', function ($query) {
                $query->whereNotNull('department_id')
                    ->whereNotNull('branch_id');
            })
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name']);

        $statuses = [
            ['value' => 'active', 'label' => 'Active'],
            ['value' => 'inactive', 'label' => 'Inactive'],
            ['value' => 'maintenance', 'label' => 'Maintenance'],
            ['value' => 'retired', 'label' => 'Retired'],
            ['value' => 'lost', 'label' => 'Lost'],
            ['value' => 'stolen', 'label' => 'Stolen']
        ];

        return view('fixed-assets.assets.index', compact('assets', 'categories', 'suppliers', 'branches', 'departments', 'users', 'statuses'));
    }

    /**
     * Show the form for creating a new asset.
     */
    public function create()
    {
        $categories = $this->getCategoriesData();
        $suppliers = $this->getSuppliersData();
        $branches = $this->getBranchesData();
        $departments = $this->getDepartmentsData();
        $users = $this->getUsersData();
        $conditions = $this->getConditionsData();
        $statuses = $this->getStatusesData();

        return view('fixed-assets.assets.create', compact(
            'categories',
            'suppliers',
            'branches',
            'departments',
            'users',
            'conditions',
            'statuses'
        ));
    }

    /**
     * Export assets to CSV.
     */
    public function export()
    {
        $assets = Asset::with(['category', 'supplier', 'currentBranch', 'currentDepartment', 'assignedTo'])
            ->orderBy('name')
            ->get();

        $filename = 'assets_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($assets) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'Name',
                'Description',
                'Category',
                'Supplier',
                'Serial Number',
                'Model',
                'Brand',
                'Purchase Date',
                'Purchase Price',
                'Warranty End Date',
                'Condition',
                'Status',
                'Current Branch',
                'Current Department',
                'Assigned To',
            ]);

            foreach ($assets as $asset) {
                fputcsv($file, [
                    $asset->name,
                    $asset->description,
                    $asset->category->name ?? 'N/A',
                    $asset->supplier->name ?? 'N/A',
                    $asset->serial_number,
                    $asset->model,
                    $asset->brand,
                    $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : '',
                    $asset->purchase_price,
                    $asset->warranty_end_date ? $asset->warranty_end_date->format('Y-m-d') : '',
                    ucfirst($asset->condition),
                    ucfirst($asset->status),
                    $asset->currentBranch->br_name ?? 'N/A',
                    $asset->currentDepartment->department_name ?? 'N/A',
                    $asset->assignedTo ? ($asset->assignedTo->first_name . ' ' . $asset->assignedTo->last_name) : 'N/A',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function edit($id)
    {
        $asset = Asset::with(['category', 'supplier', 'currentBranch', 'currentDepartment', 'assignedTo'])
            ->findOrFail($id);

        // Get data for dropdowns
        $categories = $this->getCategoriesData();
        $suppliers = $this->getSuppliersData();
        $branches = $this->getBranchesData();
        $departments = $this->getDepartmentsData();
        $users = $this->getUsersData();
        $conditions = $this->getConditionsData();
        $statuses = $this->getStatusesData();

        return view('fixed-assets.assets.edit', compact(
            'asset',
            'categories',
            'suppliers',
            'branches',
            'departments',
            'users',
            'conditions',
            'statuses'
        ));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'asset_tag' => 'required|string|max:50|unique:assets,asset_tag',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:asset_categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'serial_number' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'warranty_end_date' => 'nullable|date|after_or_equal:purchase_date',
            'condition' => ['required', function ($attribute, $value, $fail) {
                $validConditions = ['new', 'good', 'fair', 'poor', 'damaged', 'New', 'Good', 'Fair', 'Poor', 'Damaged'];
                if (!in_array($value, $validConditions)) {
                    $fail('Condition must be new, good, fair, poor, or damaged (case insensitive).');
                }
            }],
            'status' => ['required', function ($attribute, $value, $fail) {
                $validStatuses = ['active', 'inactive', 'maintenance', 'retired', 'lost', 'stolen', 'Active', 'Inactive', 'Maintenance', 'Retired', 'Lost', 'Stolen'];
                if (!in_array($value, $validStatuses)) {
                    $fail('Status must be active, inactive, maintenance, retired, lost, or stolen (case insensitive).');
                }
            }],
            'current_branch_id' => 'required|exists:branches,id',
            'current_department_id' => 'nullable|exists:departments,id',
            'assigned_to_user_id' => 'nullable|exists:users,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB max per image
        ]);

        try {
            DB::beginTransaction();

            // Handle image uploads
            $imageUrls = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('assets/images', $filename, 'public');
                    $imageUrls[] = Storage::url($path); // ✅ correct public path
                }
            }

            $asset = Asset::create([
                'name' => $request->name,
                'asset_tag' => $request->asset_tag,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'supplier_id' => $request->supplier_id,
                'serial_number' => $request->serial_number,
                'model' => $request->model,
                'brand' => $request->brand,
                'purchase_date' => $request->purchase_date,
                'purchase_price' => $request->purchase_price,
                'warranty_end_date' => $request->warranty_end_date,
                'condition' => $request->condition,
                'status' => $request->status,
                'current_branch_id' => $request->current_branch_id,
                'current_department_id' => $request->current_department_id,
                'assigned_to_user_id' => $request->assigned_to_user_id,
                'image_url' => !empty($imageUrls) ? $imageUrls : null, // ✅ stored as JSON
                'qr_code' => 'ASSET-' . $request->asset_tag
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Asset created successfully',
                'data' => $asset->load(['category', 'supplier', 'currentBranch', 'currentDepartment', 'assignedTo'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the asset: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified asset.
     */
    public function update(Request $request, $id)
    {
        try {
            $asset = Asset::findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:255',
                'asset_tag' => 'required|string|max:50|unique:assets,asset_tag,' . $id,
                'description' => 'nullable|string',
                'category_id' => 'required|exists:asset_categories,id',
                'supplier_id' => 'nullable|exists:suppliers,id',
                'serial_number' => 'nullable|string|max:255',
                'model' => 'nullable|string|max:255',
                'brand' => 'nullable|string|max:255',
                'purchase_date' => 'nullable|date',
                'purchase_price' => 'nullable|numeric|min:0',
                'warranty_end_date' => 'nullable|date|after_or_equal:purchase_date',
                'condition' => ['required', function ($attribute, $value, $fail) {
                    $validConditions = ['new', 'excellent', 'good', 'fair', 'poor', 'damaged', 'New', 'Excellent', 'Good', 'Fair', 'Poor', 'Damaged'];
                    if (!in_array($value, $validConditions)) {
                        $fail('Condition must be new, excellent, good, fair, poor, or damaged (case insensitive).');
                    }
                }],
                'status' => ['required', function ($attribute, $value, $fail) {
                    $validStatuses = ['active', 'inactive', 'maintenance', 'retired', 'lost', 'stolen', 'Active', 'Inactive', 'Maintenance', 'Retired', 'Lost', 'Stolen'];
                    if (!in_array($value, $validStatuses)) {
                        $fail('Status must be active, inactive, maintenance, retired, lost, or stolen (case insensitive).');
                    }
                }],
                'current_branch_id' => 'required|exists:branches,id',
                'current_department_id' => 'required|exists:departments,id',
                'assigned_to_user_id' => 'nullable|exists:users,id',
                'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120'
            ]);

            DB::beginTransaction();

            // Get current images (array or JSON string)
            $currentImages = [];
            if ($asset->image_url) {
                if (is_string($asset->image_url)) {
                    $currentImages = json_decode($asset->image_url, true) ?? [];
                } elseif (is_array($asset->image_url)) {
                    $currentImages = $asset->image_url;
                }
            }

            // Process new uploaded images
            if ($request->hasFile('new_images')) {
                foreach ($request->file('new_images') as $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('assets/images', $filename, 'public');
                    $currentImages[] = Storage::url($path); // ✅ Correct public path (/storage/...)
                }
            }

            // Handle removed images
            if ($request->filled('removed_images')) {
                $removedImages = json_decode($request->removed_images, true);
                if (is_array($removedImages)) {
                    // Remove from array
                    $currentImages = array_values(array_diff($currentImages, $removedImages));

                    // Delete physically from storage
                    foreach ($removedImages as $img) {
                        $relativePath = str_replace('/storage/', '', $img);
                        Storage::disk('public')->delete($relativePath);
                    }
                }
            }

            // Update asset
            $asset->update([
                'name' => $request->name,
                'asset_tag' => $request->asset_tag,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'supplier_id' => $request->supplier_id,
                'serial_number' => $request->serial_number,
                'model' => $request->model,
                'brand' => $request->brand,
                'purchase_date' => $request->purchase_date,
                'purchase_price' => $request->purchase_price,
                'warranty_end_date' => $request->warranty_end_date,
                'condition' => $request->condition,
                'status' => $request->status,
                'current_branch_id' => $request->current_branch_id,
                'current_department_id' => $request->current_department_id,
                'assigned_to_user_id' => $request->assigned_to_user_id,
                'image_url' => $currentImages // ✅ Stored as JSON array (with casts)
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Asset updated successfully!',
                'asset' => $asset->load(['category', 'supplier', 'currentBranch', 'currentDepartment', 'assignedTo'])
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the asset: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified asset.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $asset = Asset::findOrFail($id);

            // Delete associated images from storage
            if ($asset->image_url) {
                $imageUrls = [];
                if (is_string($asset->image_url)) {
                    $imageUrls = json_decode($asset->image_url, true) ?? [];
                } elseif (is_array($asset->image_url)) {
                    $imageUrls = $asset->image_url;
                }

                foreach ($imageUrls as $imagePath) {
                    // Remove 'storage/' prefix to get the actual file path
                    $filePath = str_replace('storage/', '', $imagePath);
                    if (Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                    }
                }
            }

            // Delete the asset
            $asset->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Asset deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the asset: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate a random asset tag.
     */
    public function generateAssetTag()
    {
        $tag = Asset::generateAssetTag();

        return response()->json([
            'asset_tag' => $tag
        ]);
    }

    /**
     * Get asset categories for dropdown selection.
     */
    private function getCategoriesData()
    {
        return AssetCategory::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);
    }

    /**
     * Get suppliers for dropdown selection.
     */
    private function getSuppliersData()
    {
        return Supplier::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);
    }

    /**
     * Get branches for dropdown selection.
     */
    private function getBranchesData()
    {
        return Branch::orderBy('br_name')
            ->get(['id', 'br_name', 'branch_code']);
    }

    /**
     * Get departments for dropdown selection.
     */
    private function getDepartmentsData()
    {
        return Department::orderBy('department_name')
            ->get(['id', 'department_name', 'abbreviation']);
    }

    /**
     * Get users for dropdown selection based on selected department and branch.
     */
    public function getUsersByDepartmentBranch(Request $request)
    {
        $departmentId = $request->get('department_id');
        $branchId = $request->get('branch_id');

        $query = User::with(['employee.department', 'employee.branch'])
            ->whereHas('employee', function ($query) use ($departmentId, $branchId) {
                $query->whereNotNull('department_id')
                    ->whereNotNull('branch_id');

                if ($departmentId) {
                    $query->where('department_id', $departmentId);
                }

                if ($branchId) {
                    $query->where('branch_id', $branchId);
                }
            })
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name']);

        return response()->json($query);
    }

    /**
     * Get users for dropdown selection.
     */
    private function getUsersData()
    {
        return User::with(['employee.department', 'employee.branch'])
            ->whereHas('employee', function ($query) {
                $query->whereNotNull('department_id')
                    ->whereNotNull('branch_id');
            })
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name']);
    }

    /**
     * Get asset conditions for dropdown selection.
     */
    private function getConditionsData()
    {
        return [
            ['value' => 'new', 'label' => 'New'],
            ['value' => 'good', 'label' => 'Good'],
            ['value' => 'fair', 'label' => 'Fair'],
            ['value' => 'poor', 'label' => 'Poor'],
            ['value' => 'damaged', 'label' => 'Damaged']
        ];
    }

    /**
     * Get asset statuses for dropdown selection.
     */
    private function getStatusesData()
    {
        return [
            ['value' => 'active', 'label' => 'Active'],
            ['value' => 'inactive', 'label' => 'Inactive'],
            ['value' => 'maintenance', 'label' => 'Maintenance'],
            ['value' => 'retired', 'label' => 'Retired'],
            ['value' => 'lost', 'label' => 'Lost'],
            ['value' => 'stolen', 'label' => 'Stolen']
        ];
    }

    /**
     * Show the import modal
     */
    public function openImportModal()
    {
        return view('fixed-assets.assets.asset_import_modal', []);
    }

    /**
     * Handle asset import
     */
    public function importAssets(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv|max:10240', // 10MB max
        ]);

        try {
            // Clear previous import logs if they exist
            $logPath = storage_path('logs/asset_import.log');
            if (File::exists($logPath)) {
                // Don't delete the file, just clear its contents
                File::put($logPath, '');
            }
            
            // Generate unique import ID with timestamp for better traceability
            $importId = 'asset_import_' . time() . '_' . uniqid();
            
            // Clear any existing specific log file for this import
            $specificLogPath = storage_path("logs/asset_import_{$importId}.log");
            if (File::exists($specificLogPath)) {
                File::put($specificLogPath, '');
            }
            
            // Store the file temporarily
            $filePath = $request->file('file')->store('temp/asset-imports');
            
            // Log the start of the import process
            Log::info('Asset import initiated', [
                'import_id' => $importId,
                'user_id' => Auth::id(),
                'file_name' => $request->file('file')->getClientOriginalName(),
                'file_size' => $request->file('file')->getSize(),
                'timestamp' => now()->toDateTimeString()
            ]);
            
            // Dispatch the import job to the queue with a unique ID
            ProcessAssetImport::dispatch($filePath, Auth::id(), $importId);
            
            return response()->json([
                'success' => true,
                'message' => 'Asset import has been queued and will be processed in the background.',
                'queued' => true,
                'import_id' => $importId,
                'file_name' => $request->file('file')->getClientOriginalName()
            ]);

        } catch (\Exception $e) {
            Log::error('Asset import request failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'file_name' => $request->file('file') ? $request->file('file')->getClientOriginalName() : 'unknown'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to queue asset import: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check import status
     */
    public function checkImportStatus(Request $request)
    {
        $importId = $request->get('import_id');
        
        if (!$importId) {
            return response()->json([
                'success' => false,
                'message' => 'Import ID is required'
            ], 400);
        }

        $cacheKey = 'asset_import_' . $importId;
        $results = Cache::get($cacheKey);

        if (!$results) {
            return response()->json([
                'success' => false,
                'message' => 'Import results not found. The import may still be processing or the results have expired.',
                'status' => 'not_found'
            ]);
        }

        return response()->json([
            'success' => true,
            'results' => $results
        ]);
    }

    /**
     * Download asset import template
     */
    public function downloadTemplate()
    {
        try {
            return Excel::download(new AssetTemplateExport(), 'asset_import_template.xlsx');
        } catch (\Exception $e) {
            Log::error('Failed to download asset import template', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View import logs
     */
    public function viewImportLogs(Request $request)
    {
        try {
            // Get specific import ID if provided
            $importId = $request->get('import_id');
            $logPath = storage_path('logs/asset_import.log');
            $specificLogPath = null;
            
            // Check if we should look for a specific import log file
            if ($importId) {
                $specificLogPath = storage_path("logs/asset_import_{$importId}.log");
                if (File::exists($specificLogPath)) {
                    $logPath = $specificLogPath;
                }
            }
            
            if (!File::exists($logPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Import log file not found. No imports have been processed yet.'
                ], 404);
            }

            // Get file size to determine if we should paginate
            $fileSize = File::size($logPath);
            $isLargeFile = $fileSize > 5 * 1024 * 1024; // 5MB threshold
            
            // For large files, read only the last portion to avoid memory issues
            if ($isLargeFile && !$request->has('full')) {
                // Read last 1000 lines or 2MB, whichever is smaller
                $logContent = $this->readLastLinesFromFile($logPath, 1000);
            } else {
                $logContent = File::get($logPath);
            }
            
            $logs = [];

            if (!empty($logContent)) {
                $lines = explode(PHP_EOL, trim($logContent));
                
                foreach ($lines as $line) {
                    if (!empty(trim($line))) {
                        try {
                            $logEntry = json_decode($line, true);
                            if ($logEntry && is_array($logEntry)) {
                                // Filter by import ID if specified
                                if ($importId && isset($logEntry['import_id']) && $logEntry['import_id'] !== $importId) {
                                    continue;
                                }
                                $logs[] = $logEntry;
                            }
                        } catch (\Exception $e) {
                            // Skip invalid JSON lines
                            continue;
                        }
                    }
                }
            }

            // Sort logs by timestamp (newest first)
            usort($logs, function($a, $b) {
                $timeA = strtotime($a['timestamp'] ?? '');
                $timeB = strtotime($b['timestamp'] ?? '');
                return $timeB - $timeA;
            });

            // Apply pagination if requested
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 100);
            $totalLogs = count($logs);
            
            if ($request->has('page')) {
                $offset = ($page - 1) * $perPage;
                $logs = array_slice($logs, $offset, $perPage);
            }

            return response()->json([
                'success' => true,
                'logs' => $logs,
                'total_entries' => $totalLogs,
                'is_large_file' => $isLargeFile,
                'file_size' => $this->formatBytes($fileSize),
                'import_id' => $importId ?: null
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to read import logs', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to read import logs: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Read last N lines from a file efficiently
     */
    private function readLastLinesFromFile($filePath, $lines = 1000)
    {
        $handle = fopen($filePath, "r");
        $lineCounter = 0;
        $pos = -2;
        $beginning = false;
        $text = [];

        while ($lineCounter < $lines && !$beginning) {
            $t = " ";
            while ($t != "\n") {
                if(fseek($handle, $pos, SEEK_END) == -1) {
                    $beginning = true;
                    break;
                }
                $t = fgetc($handle);
                $pos--;
            }
            $lineCounter++;
            $line = fgets($handle);
            if ($line !== false) {
                $text[] = $line;
            }
        }
        fclose($handle);
        return implode("", array_reverse($text));
    }
    
    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Download import error log
     */
    public function downloadImportLog(Request $request)
    {
        $importId = $request->get('import_id');
        
        $logPath = storage_path('logs/asset_import.log');
        
        if (!File::exists($logPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Import log file not found. No imports have been processed yet.'
            ], 404);
        }

        // If import ID is provided, use it in filename, otherwise use general name
        if ($importId) {
            $filename = 'asset_import_log_' . $importId . '_' . date('Y-m-d_H-i-s') . '.txt';
        } else {
            $filename = 'asset_import_log_' . date('Y-m-d_H-i-s') . '.txt';
        }
        
        return response()->download($logPath, $filename, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
