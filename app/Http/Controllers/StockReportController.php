<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockReportController extends Controller
{
    /**
     * Display the comprehensive asset report with filters and charts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index(Request $request)
    {
        // Base query with relationships
        $query = Asset::with(['category', 'currentBranch', 'currentDepartment', 'supplier']);
        
        // Apply filters if provided
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->filled('branch_id')) {
            $query->where('current_branch_id', $request->branch_id);
        }
        
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }
        
        // Get paginated assets for the table
        $assets = $query->paginate(20);
        
        // Get filter options
        $categories = AssetCategory::all();
        $branches = Branch::all();
        $suppliers = Supplier::all();
        
        // Get status options
        $statusOptions = [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'maintenance' => 'Maintenance',
            'retired' => 'Retired',
            'lost' => 'Lost',
            'stolen' => 'Stolen'
        ];
        
        // Get condition options
        $conditionOptions = [
            'new' => 'New',
            'good' => 'Good',
            'fair' => 'Fair',
            'poor' => 'Poor',
            'damaged' => 'Damaged'
        ];
        
        // Create a filtered query builder for statistics
        $filteredQuery = Asset::query();
        
        // Apply the same filters to the statistics query
        if ($request->filled('category_id')) {
            $filteredQuery->where('category_id', $request->category_id);
        }
        
        if ($request->filled('branch_id')) {
            $filteredQuery->where('current_branch_id', $request->branch_id);
        }
        
        if ($request->filled('supplier_id')) {
            $filteredQuery->where('supplier_id', $request->supplier_id);
        }
        
        if ($request->filled('condition')) {
            $filteredQuery->where('condition', $request->condition);
        }
        
        // Get summary statistics based on filters
        $totalAssets = (clone $filteredQuery)->count();
        $totalValue = (clone $filteredQuery)->sum('purchase_price');
        
        // Get status counts for all statuses
        $statusCounts = (clone $filteredQuery)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) use ($statusOptions) {
                $label = $statusOptions[$item->status] ?? ucfirst($item->status);
                return [$label => $item->count];
            });
            
        // For backward compatibility
        $activeAssets = $statusCounts['Active'] ?? 0;
        $inactiveAssets = $totalAssets - $activeAssets;
        
        // Get asset counts by category for chart with filters
        $categoryQuery = "SELECT ac.id, ac.name, COUNT(CASE WHEN a.id IS NOT NULL THEN 1 END) as assets_count 
                         FROM asset_categories ac 
                         LEFT JOIN assets a ON ac.id = a.category_id";
        
        $categoryWhereConditions = [];
        $categoryBindings = [];
        
        // If category is selected, only show that category
        if ($request->filled('category_id')) {
            $categoryWhereConditions[] = "ac.id = ?";
            $categoryBindings[] = $request->category_id;
        }
        
        if ($request->filled('branch_id')) {
            $categoryWhereConditions[] = "a.current_branch_id = ?";
            $categoryBindings[] = $request->branch_id;
        }
        
        if ($request->filled('supplier_id')) {
            $categoryWhereConditions[] = "a.supplier_id = ?";
            $categoryBindings[] = $request->supplier_id;
        }
        
        if ($request->filled('status')) {
            $categoryWhereConditions[] = "a.status = ?";
            $categoryBindings[] = $request->status;
        }
        
        if ($request->filled('condition')) {
            $categoryWhereConditions[] = "a.condition = ?";
            $categoryBindings[] = $request->condition;
        }
        
        if (!empty($categoryWhereConditions)) {
            $categoryQuery .= " WHERE " . implode(" AND ", $categoryWhereConditions);
        }
        
        $categoryQuery .= " GROUP BY ac.id, ac.name ORDER BY assets_count DESC";
        
        $assetsByCategory = DB::select($categoryQuery, $categoryBindings);
        $assetsByCategory = collect($assetsByCategory);
        
        // Get asset counts by branch for chart with filters
        $branchQuery = "SELECT b.id, b.br_name as name, COUNT(CASE WHEN a.id IS NOT NULL THEN 1 END) as assets_count 
                       FROM branches b 
                       LEFT JOIN assets a ON b.id = a.current_branch_id";
        
        $branchWhereConditions = [];
        $branchBindings = [];
        
        if ($request->filled('branch_id')) {
            $branchWhereConditions[] = "b.id = ?";
            $branchBindings[] = $request->branch_id;
        }
        
        if ($request->filled('category_id')) {
            $branchWhereConditions[] = "a.category_id = ?";
            $branchBindings[] = $request->category_id;
        }
        
        if ($request->filled('supplier_id')) {
            $branchWhereConditions[] = "a.supplier_id = ?";
            $branchBindings[] = $request->supplier_id;
        }
        
        if ($request->filled('status')) {
            $branchWhereConditions[] = "a.status = ?";
            $branchBindings[] = $request->status;
        }
        
        if ($request->filled('condition')) {
            $branchWhereConditions[] = "a.condition = ?";
            $branchBindings[] = $request->condition;
        }
        
        if (!empty($branchWhereConditions)) {
            $branchQuery .= " WHERE " . implode(" AND ", $branchWhereConditions);
        }
        
        $branchQuery .= " GROUP BY b.id, b.br_name ORDER BY assets_count DESC";
        
        $assetsByBranch = DB::select($branchQuery, $branchBindings);
        $assetsByBranch = collect($assetsByBranch);
        
        // Get asset counts by status for chart
        $assetsByStatus = (clone $filteredQuery)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) use ($statusOptions) {
                $label = $statusOptions[$item->status] ?? ucfirst($item->status);
                return [$label => $item->count];
            });
            
        // Get asset counts by condition for chart
        $assetsByCondition = (clone $filteredQuery)
            ->select('condition', DB::raw('COUNT(*) as count'))
            ->groupBy('condition')
            ->get()
            ->mapWithKeys(function ($item) use ($conditionOptions) {
                $label = $conditionOptions[$item->condition] ?? ucfirst($item->condition);
                return [$label => $item->count];
            });
            
        // Get total value by category for chart with filters
        $valueCategoryQuery = "SELECT ac.name, COALESCE(SUM(a.purchase_price), 0) as total_value 
                              FROM asset_categories ac 
                              LEFT JOIN assets a ON ac.id = a.category_id";
        
        // Reuse the same where conditions from category query
        if (!empty($categoryWhereConditions)) {
            $valueCategoryQuery .= " WHERE " . implode(" AND ", $categoryWhereConditions);
        }
        
        $valueCategoryQuery .= " GROUP BY ac.id, ac.name ORDER BY total_value DESC";
        
        $valueByCategory = DB::select($valueCategoryQuery, $categoryBindings);
        $valueByCategory = collect($valueByCategory);
        
        // Get total value by branch for chart with filters
        $valueBranchQuery = "SELECT b.br_name as name, COALESCE(SUM(a.purchase_price), 0) as total_value 
                            FROM branches b 
                            LEFT JOIN assets a ON b.id = a.current_branch_id";
        
        if (!empty($branchWhereConditions)) {
            $valueBranchQuery .= " WHERE " . implode(" AND ", $branchWhereConditions);
        }
        
        $valueBranchQuery .= " GROUP BY b.id, b.br_name ORDER BY total_value DESC";
        
        $valueByBranch = DB::select($valueBranchQuery, $branchBindings);
        $valueByBranch = collect($valueByBranch);
            
        // Filter out categories and branches with zero assets
        $filteredAssetsByCategory = $assetsByCategory->filter(function($item) {
            return $item->assets_count > 0;
        });
        
        $filteredAssetsByBranch = $assetsByBranch->filter(function($item) {
            return $item->assets_count > 0;
        });
        
        $filteredValueByCategory = $valueByCategory->filter(function($item) {
            return $item->total_value > 0;
        });
        
        $filteredValueByBranch = $valueByBranch->filter(function($item) {
            return $item->total_value > 0;
        });
        
        // Prepare chart data
        $chartData = [
            'categoryLabels' => $filteredAssetsByCategory->pluck('name'),
            'categoryData' => $filteredAssetsByCategory->pluck('assets_count'),
            'branchLabels' => $filteredAssetsByBranch->pluck('name'),
            'branchData' => $filteredAssetsByBranch->pluck('assets_count'),
            'statusLabels' => $assetsByStatus->keys(),
            'statusData' => $assetsByStatus->values(),
            'conditionLabels' => $assetsByCondition->keys(),
            'conditionData' => $assetsByCondition->values(),
            'valueCategoryLabels' => $filteredValueByCategory->pluck('name'),
            'valueCategoryData' => $filteredValueByCategory->pluck('total_value'),
            'valueBranchLabels' => $filteredValueByBranch->pluck('name'),
            'valueBranchData' => $filteredValueByBranch->pluck('total_value'),
        ];
        
        return view('fixed-assets.stock-reports.index', compact(
            'assets',
            'categories',
            'branches',
            'suppliers',
            'statusOptions',
            'conditionOptions',
            'totalAssets',
            'totalValue',
            'activeAssets',
            'inactiveAssets',
            'statusCounts',
            'chartData'
        ));
    }
}