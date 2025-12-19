<?php

namespace App\Http\Controllers;

use App\Services\RecycleBinService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class RecycleBinController extends Controller
{
    public function __construct(
        private readonly RecycleBinService $service
    ) {
    }

    /**
     * Display the recycle bin page
     */
    public function index(): View
    {
        $filterData = $this->service->getFilterData();

        return view('recycle-bin.index', [
            'models' => $filterData['models'],
        ]);
    }

    /**
     * Get deleted records data for DataTable
     */
    #[\NoDiscard]
    public function indexData(Request $request): JsonResponse
    {
        if (! $request->ajax()) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        try {
            $records = $this->service->buildDeletedRecordsQuery($request);

            Log::info('RecycleBin Controller: Records received', [
                'count' => $records->count(),
                'first_record' => $records->first() ? [
                    'id' => $records->first()->id ?? null,
                    'model_type' => $records->first()->model_type ?? null,
                    'deleted_at' => $records->first()->deleted_at ?? null,
                ] : null
            ]);

            // Convert to array for DataTables (client-side processing)
            $data = $records->map(function ($record) {
                try {
                    $formatted = $this->service->formatRecordForDataTable($record);

                    // Generate action buttons
                    $restoreUrl = route('recycle-bin.restore', [
                        'model_type' => base64_encode($formatted['model_type']),
                        'id' => $formatted['id']
                    ]);
                    $deleteUrl = route('recycle-bin.force-delete', [
                        'model_type' => base64_encode($formatted['model_type']),
                        'id' => $formatted['id']
                    ]);

                    $formatted['action'] = view('recycle-bin.actions', [
                        'restoreUrl' => $restoreUrl,
                        'deleteUrl' => $deleteUrl,
                        'id' => $formatted['id'],
                        'modelType' => $formatted['model_type'],
                    ])->render();

                    return $formatted;
                } catch (\Throwable $e) {
                    Log::error('RecycleBin: Error formatting record', [
                        'record_id' => $record->id ?? 'unknown',
                        'error' => $e->getMessage()
                    ]);
                    return null;
                }
            })->filter()->values()->toArray();

            Log::info('RecycleBin: Returning data', [
                'count' => count($data),
                'filters' => [
                    'model_type' => $request->input('model_type'),
                    'date_from' => $request->input('date_from'),
                    'date_to' => $request->input('date_to'),
                    'search' => $request->input('search.value'),
                ]
            ]);

            return response()->json([
                'data' => $data
            ]);
        } catch (\Throwable $e) {
            Log::error('RecycleBin: Error fetching data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'data' => [],
                'error' => 'An error occurred while fetching data. Please try again.'
            ], 500);
        }
    }

    /**
     * Restore a deleted record
     */
    public function restore(Request $request, string $modelType, int $id): JsonResponse
    {
        try {
            $decodedModelType = base64_decode($modelType, true);
            if ($decodedModelType === false) {
                throw new \InvalidArgumentException('Invalid model type.');
            }

            $model = $this->service->restoreRecord($decodedModelType, $id);

            return response()->json([
                'success' => true,
                'message' => 'Record restored successfully.',
                'data' => [
                    'model_type' => $decodedModelType,
                    'id' => $model->id,
                ]
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found.'
            ], 404);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Failed to restore record from recycle bin', [
                'model_type' => $modelType,
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Failed to restore record. Please try again.'
            ], 500);
        }
    }

    /**
     * Permanently delete a record
     */
    public function forceDelete(Request $request, string $modelType, int $id): JsonResponse
    {
        try {
            $decodedModelType = base64_decode($modelType, true);
            if ($decodedModelType === false) {
                throw new \InvalidArgumentException('Invalid model type.');
            }

            $deleted = $this->service->forceDeleteRecord($decodedModelType, $id);

            if (! $deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to permanently delete record.'
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Record permanently deleted.',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found.'
            ], 404);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Failed to permanently delete record from recycle bin', [
                'model_type' => $modelType,
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Failed to permanently delete record. Please try again.'
            ], 500);
        }
    }
}
