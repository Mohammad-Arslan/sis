<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function __construct(
        private readonly ActivityLogService $service
    ) {
    }

    public function index(): View
    {
        $filterData = $this->service->getFilterData();

        return view('activity-logs.index', $filterData);
    }

    #[\NoDiscard]
    public function indexData(Request $request): JsonResponse
    {
        $query = $this->service->buildFilteredQuery($request);

        return $this->service->formatDataTableColumns($query)->make(true);
    }

    #[\NoDiscard]
    public function getJsonData(int $id, string $type): JsonResponse
    {
        if (! in_array($type, ['old', 'new'], true)) {
            return response()->json(['success' => false, 'message' => 'Invalid type'], 400);
        }

        $data = $this->service->getJsonData($id, $type);

        return response()->json($data);
    }
}
