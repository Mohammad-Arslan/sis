<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use App\Models\TaxType;
use App\Models\State;
use App\Services\TaxSettingsService;
use App\Http\Requests\TaxSettings\StoreTaxRequest;
use App\Http\Requests\TaxSettings\UpdateTaxRequest;
use App\Http\Requests\TaxSettings\StoreTaxTypeRequest;
use App\Http\Requests\TaxSettings\UpdateTaxTypeRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class TaxSettingsController extends Controller
{
    public function __construct(
        private readonly TaxSettingsService $service
    ) {}

    /**
     * Display the unified tax settings page
     */
    public function index(): View
    {
        $taxTypes = $this->service->getTaxTypes();
        $states = $this->service->getStates();
        
        return view('settings.tax.index', compact('taxTypes', 'states'));
    }

    /**
     * Get tax types data for DataTable
     */
    public function getTaxTypes(Request $request): JsonResponse
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $data = TaxType::query();
        
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', fn($row) => $this->service->generateModalActionButtons($row->id, 'TaxType'))
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Get taxes data for DataTable
     */
    public function getTaxes(Request $request): JsonResponse
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $data = Tax::with(['tax_type', 'state']);
        
        if ($request->tax_type_id && $request->tax_type_id > 0) {
            $data = $data->where('tax_type_id', $request->tax_type_id);
        }
        
        if ($request->state_id && $request->state_id > 0) {
            $data = $data->where('state_id', $request->state_id);
        }
        
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('tax_type_name', fn($row) => $row->tax_type?->name ?? 'N/A')
            ->addColumn('state_name', fn($row) => $row->state?->state_name ?? 'N/A')
            ->addColumn('tax_percentage_formatted', fn($row) => number_format($row->tax_percentage, 2) . '%')
            ->addColumn('active_from_formatted', function($row) {
                if (!$row->active_from) return 'N/A';
                return is_string($row->active_from) 
                    ? Carbon::parse($row->active_from)->format('Y-m-d')
                    : $row->active_from->format('Y-m-d');
            })
            ->addColumn('active_till_formatted', function($row) {
                if (!$row->active_till) return 'N/A';
                return is_string($row->active_till) 
                    ? Carbon::parse($row->active_till)->format('Y-m-d')
                    : $row->active_till->format('Y-m-d');
            })
            ->addColumn('action', fn($row) => $this->service->generateModalActionButtons($row->id, 'Tax'))
            ->filterColumn('tax_type_name', function($query, $keyword) {
                $query->whereHas('tax_type', function($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('state_name', function($query, $keyword) {
                $query->whereHas('state', function($q) use ($keyword) {
                    $q->where('state_name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('tax_percentage', function($query, $keyword) {
                // Remove % sign and search on numeric value
                $keyword = str_replace('%', '', $keyword);
                if (is_numeric($keyword)) {
                    $query->where('tax_percentage', 'like', "%{$keyword}%");
                }
            })
            ->filterColumn('active_from', function($query, $keyword) {
                $query->whereDate('active_from', 'like', "%{$keyword}%");
            })
            ->filterColumn('active_till', function($query, $keyword) {
                $query->whereDate('active_till', 'like', "%{$keyword}%");
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Store a new tax type
     */
    public function storeTaxType(StoreTaxTypeRequest $request): JsonResponse
    {
        try {
            $taxType = $this->service->createTaxType($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Tax type created successfully.',
                'data' => $taxType
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create tax type', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create tax type. Please try again.'
            ], 500);
        }
    }

    /**
     * Update a tax type
     */
    public function updateTaxType(UpdateTaxTypeRequest $request, TaxType $taxType): JsonResponse
    {
        try {
            $taxType = $this->service->updateTaxType($taxType, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Tax type updated successfully.',
                'data' => $taxType
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update tax type', [
                'tax_type_id' => $taxType->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update tax type. Please try again.'
            ], 500);
        }
    }

    /**
     * Delete a tax type
     */
    public function destroyTaxType(TaxType $taxType): JsonResponse
    {
        try {
            $deleted = $this->service->deleteTaxType($taxType);
            
            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete tax type.'
                ], 422);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Tax type deleted successfully.'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete tax type. It may be in use.'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to delete tax type', [
                'tax_type_id' => $taxType->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete tax type. Please try again.'
            ], 500);
        }
    }

    /**
     * Store a new tax
     */
    public function storeTax(StoreTaxRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['created_by'] = auth()->id();
            
            $tax = $this->service->createTax($data);

            return response()->json([
                'success' => true,
                'message' => 'Tax created successfully.',
                'data' => $tax
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create tax', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create tax. Please try again.'
            ], 500);
        }
    }

    /**
     * Update a tax
     */
    public function updateTax(UpdateTaxRequest $request, Tax $tax): JsonResponse
    {
        try {
            $tax = $this->service->updateTax($tax, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Tax updated successfully.',
                'data' => $tax
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update tax', [
                'tax_id' => $tax->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update tax. Please try again.'
            ], 500);
        }
    }

    /**
     * Delete a tax
     */
    public function destroyTax(Tax $tax): JsonResponse
    {
        try {
            $deleted = $this->service->deleteTax($tax);
            
            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete tax.'
                ], 422);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Tax deleted successfully.'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete tax. It may be in use.'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to delete tax', [
                'tax_id' => $tax->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete tax. Please try again.'
            ], 500);
        }
    }

    /**
     * Get single tax type for editing
     */
    public function getTaxType(TaxType $taxType): JsonResponse
    {
        return response()->json($taxType);
    }

    /**
     * Get single tax for editing
     */
    public function getTax(Tax $tax): JsonResponse
    {
        $tax = $tax->load(['tax_type', 'state']);
        
        // Format dates for frontend (Y-m-d format for date inputs)
        $data = $tax->toArray();
        if ($tax->active_from instanceof \Carbon\Carbon) {
            $data['active_from'] = $tax->active_from->format('Y-m-d');
        } elseif ($tax->active_from && is_string($tax->active_from)) {
            // If it's a string, try to parse and format it
            try {
                $data['active_from'] = Carbon::parse($tax->active_from)->format('Y-m-d');
            } catch (\Exception $e) {
                // Keep original if parsing fails
            }
        }
        
        if ($tax->active_till instanceof \Carbon\Carbon) {
            $data['active_till'] = $tax->active_till->format('Y-m-d');
        } elseif ($tax->active_till && is_string($tax->active_till)) {
            // If it's a string, try to parse and format it
            try {
                $data['active_till'] = Carbon::parse($tax->active_till)->format('Y-m-d');
            } catch (\Exception $e) {
                // Keep original if parsing fails
            }
        }
        
        return response()->json($data);
    }
}

