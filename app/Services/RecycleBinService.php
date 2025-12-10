<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RecycleBinService
{
    /**
     * Get list of models that use SoftDeletes
     */
    public function getAvailableModels(): array
    {
        $models = [
            'App\Models\Student' => 'Student',
            'App\Models\Country' => 'Country',
            'App\Models\State' => 'State',
            'App\Models\City' => 'City',
            'App\Models\Town' => 'Town',
            'App\Models\Region' => 'Region',
            'App\Models\BuildingType' => 'Building Type',
            'App\Models\StudentPreviousSchool' => 'Student Previous School',
            'App\Models\BranchAcademicYear' => 'Branch Academic Year',
            'App\Models\Source' => 'Source',
            'App\Models\Category' => 'Category',
            'App\Models\Department' => 'Department',
            'App\Models\Task' => 'Task',
            
        ];

        // Filter to only include models that actually use SoftDeletes
        return array_filter($models, function ($modelClass) {
            if (!class_exists($modelClass)) {
                return false;
            }
            
            $reflection = new \ReflectionClass($modelClass);
            return $reflection->hasMethod('bootSoftDeletes') || 
                   in_array(\Illuminate\Database\Eloquent\SoftDeletes::class, class_uses_recursive($modelClass));
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Get filter data for the recycle bin
     */
    public function getFilterData(): array
    {
        return [
            'models' => $this->getAvailableModels(),
        ];
    }

    /**
     * Build query for deleted records based on filters
     */
    public function buildDeletedRecordsQuery(Request $request): Collection
    {
        $modelType = $request->input('model_type');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $search = $request->input('search.value');

        $availableModels = $this->getAvailableModels();
        $results = collect();

        // If model type is specified, only query that model
        $modelsToQuery = $modelType ? [$modelType] : array_keys($availableModels);

        foreach ($modelsToQuery as $modelClass) {
            if (!class_exists($modelClass)) {
                continue;
            }

            try {
                $query = $modelClass::onlyTrashed();

                // Apply date filters
                if ($dateFrom) {
                    $query->whereDate('deleted_at', '>=', $dateFrom);
                }
                if ($dateTo) {
                    $query->whereDate('deleted_at', '<=', $dateTo);
                }

                // Apply search
                if ($search) {
                    $query->where(function ($q) use ($search, $modelClass) {
                        // Try to search in common fields
                        $fillable = (new $modelClass)->getFillable();
                        foreach ($fillable as $field) {
                            $q->orWhere($field, 'like', "%{$search}%");
                        }
                        // Also search by ID
                        if (is_numeric($search)) {
                            $q->orWhere('id', $search);
                        }
                    });
                }

                $records = $query->get();

                // Add metadata to each record
                foreach ($records as $record) {
                    $record->model_type = $modelClass;
                    $record->model_display_name = $availableModels[$modelClass] ?? class_basename($modelClass);
                    $record->record_id = $record->id;
                }

                $results = $results->merge($records);
            } catch (\Exception $e) {
                Log::warning('Failed to query deleted records for model', [
                    'model' => $modelClass,
                    'error' => $e->getMessage()
                ]);
                continue;
            }
        }

        // Sort by deleted_at descending
        return $results->sortByDesc('deleted_at')->values();
    }

    /**
     * Restore a deleted record
     */
    #[\NoDiscard]
    public function restoreRecord(string $modelType, int $id): Model
    {
        if (!class_exists($modelType)) {
            throw new \InvalidArgumentException("Model class {$modelType} does not exist.");
        }

        return DB::transaction(function () use ($modelType, $id) {
            $model = $modelType::withTrashed()->findOrFail($id);

            if (!$model->trashed()) {
                throw new \Exception('Record is not deleted.');
            }

            $model->restore();

            // Clear relevant cache if model has cache tags
            $this->clearModelCache($modelType);

            return $model->fresh();
        });
    }

    /**
     * Permanently delete a record
     */
    #[\NoDiscard]
    public function forceDeleteRecord(string $modelType, int $id): bool
    {
        if (!class_exists($modelType)) {
            throw new \InvalidArgumentException("Model class {$modelType} does not exist.");
        }

        return DB::transaction(function () use ($modelType, $id) {
            $model = $modelType::withTrashed()->findOrFail($id);

            if (!$model->trashed()) {
                throw new \Exception('Record is not deleted.');
            }

            $deleted = $model->forceDelete();

            // Clear relevant cache if model has cache tags
            $this->clearModelCache($modelType);

            return $deleted;
        });
    }

    /**
     * Get model display name
     */
    public function getModelDisplayName(string $modelType): string
    {
        $availableModels = $this->getAvailableModels();
        return $availableModels[$modelType] ?? class_basename($modelType);
    }

    /**
     * Clear cache for a specific model type
     */
    private function clearModelCache(string $modelType): void
    {
        $modelName = Str::snake(class_basename($modelType));
        
        // Clear common cache patterns
        $cacheKeys = [
            "{$modelName}.*",
            strtolower($modelName) . ".*",
        ];

        foreach ($cacheKeys as $pattern) {
            // If using cache tags (Redis/Memcached)
            try {
                \Illuminate\Support\Facades\Cache::tags([$modelName])->flush();
            } catch (\Exception $e) {
                // Cache tags not supported, try individual keys
            }
        }
    }

    /**
     * Format record for DataTable
     */
    public function formatRecordForDataTable($record): array
    {
        $modelDisplayName = $this->getModelDisplayName($record->model_type);
        
        // Try to get a display name for the record
        $displayName = $this->getRecordDisplayName($record);

        return [
            'id' => $record->record_id ?? $record->id,
            'model_type' => $record->model_type,
            'model_display' => $modelDisplayName,
            'record_name' => $displayName,
            'deleted_at' => $record->deleted_at?->format('Y-m-d H:i:s'),
            'deleted_at_raw' => $record->deleted_at?->toDateTimeString(),
        ];
    }

    /**
     * Get a human-readable name for a record
     */
    private function getRecordDisplayName($record): string
    {
        // Get all attributes from the record
        $attributes = $record->getAttributes();
        
        // Priority order for name fields
        $priorityFields = [
            'name',
            'title',
            'first_name',
            'email',
            'code',
            'number',
        ];
        
        // First, try priority fields
        foreach ($priorityFields as $field) {
            if (isset($attributes[$field]) && !empty($attributes[$field])) {
                return (string) $attributes[$field];
            }
        }
        
        // Then, try to find any field ending with '_name' (e.g., country_name, state_name, city_name)
        foreach ($attributes as $key => $value) {
            if (str_ends_with($key, '_name') && !empty($value)) {
                return (string) $value;
            }
        }
        
        // Try fields containing 'name' (e.g., br_name, type_name, region_name)
        foreach ($attributes as $key => $value) {
            if (str_contains($key, 'name') && !empty($value)) {
                return (string) $value;
            }
        }
        
        // Try concatenating first_name and last_name
        if (isset($attributes['first_name']) || isset($attributes['last_name'])) {
            $firstName = $attributes['first_name'] ?? '';
            $lastName = $attributes['last_name'] ?? '';
            $fullName = trim($firstName . ' ' . $lastName);
            if (!empty($fullName)) {
                return $fullName;
            }
        }
        
        // Try other common display fields
        $otherFields = ['abbreviation', 'code', 'number', 'email', 'phone', 'mobile'];
        foreach ($otherFields as $field) {
            if (isset($attributes[$field]) && !empty($attributes[$field])) {
                return (string) $attributes[$field];
            }
        }
        
        // Fallback to ID
        return "Record #{$record->id}";
    }
}

