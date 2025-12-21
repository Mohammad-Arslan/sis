<?php

namespace App\Http\Controllers;

use App\Services\GlobalSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function __construct(
        public GlobalSearchService $searchService
    ) {}

    /**
     * Handle global search request
     */
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => 'required|string|max:255',
            'limit' => 'sometimes|integer|min:1|max:20',
        ]);

        $query = trim($validated['q']);
        $limit = $validated['limit'] ?? null;

        if (empty($query)) {
            return response()->json([
                'success' => true,
                'data' => $this->formatEmptyResponse(),
            ]);
        }

        $results = $this->searchService->search($query, $limit);

        return response()->json([
            'success' => true,
            'data' => $this->formatResponse($results),
        ]);
    }

    /**
     * Format search results for frontend consumption
     */
    private function formatResponse(array $results): array
    {
        $formatted = [];
        $totalCount = 0;

        if (! empty($results['students'])) {
            $formatted[] = [
                'category' => 'Students',
                'icon' => 'ri-user-line',
                'items' => $results['students'],
            ];
            $totalCount += count($results['students']);
        }

        if (! empty($results['employees'])) {
            $formatted[] = [
                'category' => 'Employees',
                'icon' => 'ri-briefcase-line',
                'items' => $results['employees'],
            ];
            $totalCount += count($results['employees']);
        }

        if (! empty($results['branches'])) {
            $formatted[] = [
                'category' => 'Branches',
                'icon' => 'ri-building-line',
                'items' => $results['branches'],
            ];
            $totalCount += count($results['branches']);
        }

        if (! empty($results['classes'])) {
            $formatted[] = [
                'category' => 'Classes',
                'icon' => 'ri-book-open-line',
                'items' => $results['classes'],
            ];
            $totalCount += count($results['classes']);
        }

        if (! empty($results['subjects'])) {
            $formatted[] = [
                'category' => 'Subjects',
                'icon' => 'ri-book-2-line',
                'items' => $results['subjects'],
            ];
            $totalCount += count($results['subjects']);
        }

        if (! empty($results['users'])) {
            $formatted[] = [
                'category' => 'Users',
                'icon' => 'ri-account-circle-line',
                'items' => $results['users'],
            ];
            $totalCount += count($results['users']);
        }

        if (! empty($results['assets'])) {
            $formatted[] = [
                'category' => 'Fixed Assets',
                'icon' => 'ri-folder-line',
                'items' => $results['assets'],
            ];
            $totalCount += count($results['assets']);
        }

        return [
            'results' => $formatted,
            'total' => $totalCount,
        ];
    }

    /**
     * Format empty response structure
     */
    private function formatEmptyResponse(): array
    {
        return [
            'results' => [],
            'total' => 0,
        ];
    }
}
