<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ActivityLogService
{
    public function getFilterData(): array
    {
        return [
            'users' => User::select('id', 'name', 'email')
                ->orderBy('name')
                ->get(),
            'events' => ActivityLog::distinct()
                ->pluck('event')
                ->sort()
                ->values(),
            'modelTypes' => ActivityLog::distinct()
                ->whereNotNull('model_type')
                ->pluck('model_type')
                ->sort()
                ->values(),
        ];
    }

    public function buildFilteredQuery(Request $request): Builder
    {
        $query = ActivityLog::with('user')
            ->select('activity_logs.*');

        $request->whenFilled('user_id', fn($value) => $query->where('user_id', $value));
        $request->whenFilled('event', fn($value) => $query->where('event', $value));
        $request->whenFilled('model_type', fn($value) => $query->where('model_type', $value));
        $request->whenFilled('date_from', fn($value) => $query->whereDate('created_at', '>=', $value));
        $request->whenFilled('date_to', fn($value) => $query->whereDate('created_at', '<=', $value));

        if ($request->filled('search') && isset($request->search['value']) && $request->search['value']) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('event', 'like', "%{$search}%")
                    ->orWhere('model_type', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhere('url', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        return $query;
    }

    public function formatDataTableColumns(Builder $query)
    {
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('user_name', fn($row): string => $row->user?->name ?? 'System')
            ->addColumn('event_badge', fn($row): string => $this->getEventBadge($row->event))
            ->addColumn('model_display', fn($row): string => $this->getModelDisplay($row->model_type))
            ->addColumn('old_values_btn', fn($row): string => $this->getOldValuesButton($row))
            ->addColumn('new_values_btn', fn($row): string => $this->getNewValuesButton($row))
            ->addColumn('created_at_formatted', fn($row): string => $row->created_at->format('Y-m-d H:i:s'))
            ->rawColumns(['event_badge', 'old_values_btn', 'new_values_btn']);
    }

    public function getEventBadge(string $event): string
    {
        $badgeClass = match ($event) {
            'created' => 'success',
            'updated' => 'primary',
            'deleted' => 'danger',
            'login', 'logout' => 'info',
            'failed_login' => 'warning',
            'api_request' => 'warning',
            'command_ran' => 'secondary',
            'job_processed' => 'dark',
            default => 'secondary',
        };

        $eventLabel = ucfirst(str_replace('_', ' ', $event));

        return "<span class=\"badge bg-{$badgeClass}\">{$eventLabel}</span>";
    }

    public function getModelDisplay(?string $modelType): string
    {
        if (! $modelType) {
            return '-';
        }

        $parts = explode('\\', $modelType);
        return end($parts) ?: '-';
    }

    public function getOldValuesButton(ActivityLog $row): string
    {
        if (! $row->old_values) {
            return '<span class="text-muted">-</span>';
        }

        return sprintf(
            '<button type="button" class="btn btn-sm btn-outline-info" onclick="viewJsonData(%d, \'old\')">
                <i class="ri-eye-line"></i> View Old Values
            </button>',
            $row->id
        );
    }

    public function getNewValuesButton(ActivityLog $row): string
    {
        if (! $row->new_values) {
            return '<span class="text-muted">-</span>';
        }

        return sprintf(
            '<button type="button" class="btn btn-sm btn-outline-success" onclick="viewJsonData(%d, \'new\')">
                <i class="ri-eye-line"></i> View New Values
            </button>',
            $row->id
        );
    }

    public function getJsonData(int $id, string $type): array
    {
        $log = ActivityLog::findOrFail($id);
        $data = $type === 'old' ? $log->old_values : $log->new_values;

        return [
            'success' => true,
            'data' => $data,
            'type' => $type,
            'event' => $log->event,
            'model_type' => $log->model_type,
        ];
    }
}
