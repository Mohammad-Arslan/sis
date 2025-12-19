<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RecycleBinService
{
    /**
     * Get list of models that use SoftDeletes
     * Automatically scans the Models directory to find all models with SoftDeletes trait
     * Uses file-based detection first to avoid memory issues
     * Results are cached for 1 hour to improve performance
     */
    public function getAvailableModels(): array
    {
        return Cache::remember('recycle-bin.available-models', 3600, function () {
            $models = [];
            $modelsPath = app_path('Models');

            if (! is_dir($modelsPath)) {
                return [];
            }

            // Get all PHP files in the Models directory
            $files = glob($modelsPath . '/*.php');

            foreach ($files as $file) {
                $fileName = basename($file, '.php');
                $className = 'App\\Models\\' . $fileName;

                // First, do a quick file-based check to see if SoftDeletes is mentioned
                // This avoids loading the class into memory unnecessarily
                $fileContent = file_get_contents($file);

                // Skip if file doesn't contain SoftDeletes (case-insensitive)
                if (stripos($fileContent, 'SoftDeletes') === false) {
                    continue;
                }

                // Skip if it's not a Model class (check for "extends Model" or "extends Authenticatable")
                if (stripos($fileContent, 'extends') === false) {
                    continue;
                }

                // Now check if class exists and can be loaded
                if (! class_exists($className, false)) {
                    // Try to load it
                    try {
                        if (! class_exists($className)) {
                            continue;
                        }
                    } catch (\Throwable $e) {
                        continue;
                    }
                }

                try {
                    // Use reflection without instantiating the class
                    $reflection = new \ReflectionClass($className);

                    // Skip if it's abstract or an interface
                    if ($reflection->isAbstract() || $reflection->isInterface()) {
                        continue;
                    }

                    // Skip if it's not a Model subclass
                    if (! $reflection->isSubclassOf(Model::class)) {
                        continue;
                    }

                    // Check if model uses SoftDeletes trait (more memory-efficient check)
                    $traits = $reflection->getTraitNames();
                    $usesSoftDeletes = in_array(\Illuminate\Database\Eloquent\SoftDeletes::class, $traits) ||
                                      $reflection->hasMethod('bootSoftDeletes');

                    if ($usesSoftDeletes) {
                        // Generate a human-readable display name
                        $displayName = $this->generateModelDisplayName($className);
                        $models[$className] = $displayName;
                    }

                    // Clear reflection to free memory
                    unset($reflection);
                } catch (\Throwable $e) {
                    // Skip models that can't be reflected
                    Log::debug('Skipped model in RecycleBin scan', [
                        'model' => $className,
                        'error' => $e->getMessage()
                    ]);
                    continue;
                }
            }

            // Sort models alphabetically by display name
            asort($models);

            return $models;
        });
    }

    /**
     * Generate a human-readable display name for a model class
     */
    private function generateModelDisplayName(string $className): string
    {
        $baseName = class_basename($className);

        // Convert PascalCase to readable format
        // e.g., "StudentPreviousSchool" -> "Student Previous School"
        $displayName = preg_replace('/(?<!^)(?=[A-Z])/', ' ', $baseName);

        return $displayName;
    }

    /**
     * Clear the cached list of available models
     * Useful when new models with SoftDeletes are added
     */
    public function clearModelsCache(): void
    {
        Cache::forget('recycle-bin.available-models');
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

        // Check if user wants to query all models (default: false for safety)
        $queryAllModels = $request->boolean('query_all_models', false);

        // Limit the number of models queried at once to prevent memory issues
        // Default: 20 models at a time for safety, but can be overridden
        $maxModelsPerRequest = $queryAllModels ? PHP_INT_MAX : 20;

        if (count($modelsToQuery) > $maxModelsPerRequest && ! $modelType && ! $queryAllModels) {
            // If querying all models and there are too many, limit to first N
            $modelsToQuery = array_slice($modelsToQuery, 0, $maxModelsPerRequest);
            Log::info('RecycleBin: Limiting to first ' . $maxModelsPerRequest . ' models to prevent memory issues. Use query_all_models=1 to query all models.');
        } else if ($queryAllModels) {
            Log::info('RecycleBin: Querying ALL models (user requested)', [
                'total_models' => count($modelsToQuery)
            ]);
        }

        Log::info('RecycleBin: Starting query', [
            'total_models_to_query' => count($modelsToQuery),
            'models' => array_slice($modelsToQuery, 0, 5), // Log first 5 models
            'filters' => [
                'model_type' => $modelType,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'search' => $search
            ]
        ]);

        $totalRecordsProcessed = 0;
        // No limit when specific model is selected - we'll use chunking
        // When querying all models, still cap it for safety
        $maxTotalRecords = $modelType ? PHP_INT_MAX : ($queryAllModels ? 5000 : 2000);

        foreach ($modelsToQuery as $index => $modelClass) {
            Log::info('RecycleBin: Processing model', [
                'index' => $index + 1,
                'total' => count($modelsToQuery),
                'model' => $modelClass
            ]);
            // Stop if we've reached the total record limit (only when querying multiple models)
            if (! $modelType && $totalRecordsProcessed >= $maxTotalRecords) {
                Log::info('RecycleBin: Reached maximum record limit of ' . $maxTotalRecords);
                break;
            }

            // Use autoload=true to ensure classes are loaded
            if (! class_exists($modelClass)) {
                Log::info('RecycleBin: Class does not exist', ['model' => $modelClass]);
                continue;
            }

            Log::info('RecycleBin: Class exists, proceeding', ['model' => $modelClass]);

            try {
                // Check if table exists first
                $tableName = (new $modelClass())->getTable();
                Log::info('RecycleBin: Got table name', ['model' => $modelClass, 'table' => $tableName]);

                if (! DB::getSchemaBuilder()->hasTable($tableName)) {
                    Log::info('RecycleBin: Table does not exist', ['table' => $tableName, 'model' => $modelClass]);
                    continue;
                }

                Log::info('RecycleBin: Table exists, proceeding with query', ['table' => $tableName, 'model' => $modelClass]);

                // Check total deleted count first (before filters) - use a fresh query
                $countQuery = $modelClass::onlyTrashed();
                $sql = $countQuery->toSql();
                $bindings = $countQuery->getBindings();
                Log::info('RecycleBin: Count query', [
                    'model' => $modelClass,
                    'table' => $tableName,
                    'sql' => $sql,
                    'bindings' => $bindings
                ]);

                $totalDeleted = $countQuery->count();
                Log::info('RecycleBin: Count result', [
                    'model' => $modelClass,
                    'total_deleted' => $totalDeleted
                ]);

                if ($totalDeleted === 0) {
                    Log::info('RecycleBin: No deleted records, skipping', ['model' => $modelClass]);
                    continue;
                }

                Log::info('RecycleBin: Found deleted records, proceeding', [
                    'model' => $modelClass,
                    'table' => $tableName,
                    'total_deleted' => $totalDeleted
                ]);

                // Create a fresh query for actual data retrieval
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
                        // Try to search in common fields (limit to avoid too many OR conditions)
                        try {
                            $modelInstance = new $modelClass();
                            $fillable = $modelInstance->getFillable();
                            $fillable = array_slice($fillable, 0, 5); // Limit to first 5 fillable fields

                            foreach ($fillable as $field) {
                                $q->orWhere($field, 'like', "%{$search}%");
                            }
                            unset($modelInstance);
                        } catch (\Throwable $e) {
                            // If we can't get fillable, just search by ID
                        }

                        // Also search by ID
                        if (is_numeric($search)) {
                            $q->orWhere('id', $search);
                        }
                    });
                }

                // Process records in chunks to avoid memory issues
                // When specific model is selected, process all records in chunks
                // When querying multiple models, use smaller chunks
                $chunkSize = $modelType ? 500 : ($queryAllModels ? 100 : 200);

                Log::info('RecycleBin: Processing records in chunks', [
                    'model' => $modelClass,
                    'chunk_size' => $chunkSize,
                    'total_deleted' => $totalDeleted
                ]);

                // Process records in chunks using cursor() for memory efficiency
                $modelRecords = collect();
                $chunkCount = 0;

                $query->chunk($chunkSize, function ($chunk) use ($modelClass, $availableModels, &$modelRecords, &$chunkCount, &$totalRecordsProcessed) {
                    $chunkCount++;

                    // Add metadata to each record in the chunk
                    foreach ($chunk as $record) {
                        $record->model_type = $modelClass;
                        $record->model_display_name = $availableModels[$modelClass] ?? class_basename($modelClass);
                        $record->record_id = $record->id;
                    }

                    $modelRecords = $modelRecords->merge($chunk);
                    $totalRecordsProcessed += $chunk->count();

                    // Free memory after each chunk
                    unset($chunk);

                    // Force garbage collection every 5 chunks
                    if ($chunkCount % 5 === 0) {
                        gc_collect_cycles();
                    }
                });

                Log::info('RecycleBin: Records retrieved after chunking', [
                    'model' => $modelClass,
                    'total_chunks' => $chunkCount,
                    'total_records' => $modelRecords->count(),
                    'first_record_id' => $modelRecords->first()?->id,
                    'first_record_deleted_at' => $modelRecords->first()?->deleted_at?->toDateTimeString()
                ]);

                $results = $results->merge($modelRecords);

                // Free memory
                unset($modelRecords, $query);
            } catch (\Illuminate\Database\QueryException $e) {
                // Skip models with database errors (e.g., missing tables)
                Log::debug('RecycleBin: Database error for model', [
                    'model' => $modelClass,
                    'error' => $e->getMessage()
                ]);
                continue;
            } catch (\Throwable $e) {
                Log::warning('RecycleBin: Failed to query deleted records for model', [
                    'model' => $modelClass,
                    'error' => $e->getMessage()
                ]);
                continue;
            }
        }

        // Sort by deleted_at descending
        $sorted = $results->sortByDesc(function ($record) {
            // Handle both Carbon instances and strings
            if ($record->deleted_at instanceof \Carbon\Carbon) {
                return $record->deleted_at->timestamp;
            }
            if (is_string($record->deleted_at)) {
                return strtotime($record->deleted_at);
            }
            return 0;
        })->values();

        Log::info('RecycleBin: Final results', [
            'total_before_sort' => $results->count(),
            'total_after_sort' => $sorted->count(),
            'models_queried' => count($modelsToQuery),
            'model_type_selected' => $modelType ? 'yes' : 'no',
            'query_all_models' => $queryAllModels
        ]);

        // Only limit when querying multiple models (not when specific model is selected)
        if ($modelType) {
            // Specific model selected - return all records (no limit)
            Log::info('RecycleBin: Returning all records for specific model', [
                'final_count' => $sorted->count()
            ]);
            return $sorted;
        }

        // Limit total results when querying multiple models to prevent memory issues
        $final = $sorted->take($maxTotalRecords);

        Log::info('RecycleBin: Final count after limit', [
            'final_count' => $final->count(),
            'max_total_records' => $maxTotalRecords
        ]);

        return $final;
    }

    /**
     * Restore a deleted record
     */
    #[\NoDiscard]
    public function restoreRecord(string $modelType, int $id): Model
    {
        if (! class_exists($modelType)) {
            throw new \InvalidArgumentException("Model class {$modelType} does not exist.");
        }

        return DB::transaction(function () use ($modelType, $id) {
            $model = $modelType::withTrashed()->findOrFail($id);

            if (! $model->trashed()) {
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
        if (! class_exists($modelType)) {
            throw new \InvalidArgumentException("Model class {$modelType} does not exist.");
        }

        return DB::transaction(function () use ($modelType, $id) {
            $model = $modelType::withTrashed()->findOrFail($id);

            if (! $model->trashed()) {
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
            if (isset($attributes[$field]) && ! empty($attributes[$field])) {
                return (string) $attributes[$field];
            }
        }

        // Then, try to find any field ending with '_name' (e.g., country_name, state_name, city_name)
        foreach ($attributes as $key => $value) {
            if (str_ends_with($key, '_name') && ! empty($value)) {
                return (string) $value;
            }
        }

        // Try fields containing 'name' (e.g., br_name, type_name, region_name)
        foreach ($attributes as $key => $value) {
            if (str_contains($key, 'name') && ! empty($value)) {
                return (string) $value;
            }
        }

        // Try concatenating first_name and last_name
        if (isset($attributes['first_name']) || isset($attributes['last_name'])) {
            $firstName = $attributes['first_name'] ?? '';
            $lastName = $attributes['last_name'] ?? '';
            $fullName = trim($firstName . ' ' . $lastName);
            if (! empty($fullName)) {
                return $fullName;
            }
        }

        // Try other common display fields
        $otherFields = ['abbreviation', 'code', 'number', 'email', 'phone', 'mobile'];
        foreach ($otherFields as $field) {
            if (isset($attributes[$field]) && ! empty($attributes[$field])) {
                return (string) $attributes[$field];
            }
        }

        // Fallback to ID
        return "Record #{$record->id}";
    }
}
