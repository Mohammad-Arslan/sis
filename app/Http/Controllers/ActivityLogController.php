<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ActivityLogController extends Controller
{
    public function index(): View
    {
        $users = User::select('id', 'name', 'email')->orderBy('name')->get();
        $events = ActivityLog::distinct()->pluck('event')->sort()->values();
        $modelTypes = ActivityLog::distinct()->whereNotNull('model_type')->pluck('model_type')->sort()->values();

        return view('activity-logs.index', compact('users', 'events', 'modelTypes'));
    }

    public function indexData(Request $request): JsonResponse
    {
        $query = ActivityLog::with('user')
            ->select('activity_logs.*');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search') && $request->search['value']) {
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

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : 'System';
            })
            ->addColumn('event_badge', function ($row) {
                $badgeClass = match ($row->event) {
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
                return '<span class="badge bg-' . $badgeClass . '">' . ucfirst(str_replace('_', ' ', $row->event)) . '</span>';
            })
            ->addColumn('model_display', function ($row) {
                if ($row->model_type) {
                    $parts = explode('\\', $row->model_type);
                    return end($parts);
                }
                return '-';
            })
            ->addColumn('old_values_btn', function ($row) {
                if ($row->old_values) {
                    return '<button type="button" class="btn btn-sm btn-outline-info" onclick="viewJsonData(' . $row->id . ', \'old\')">
                        <i class="ri-eye-line"></i> View Old Values
                    </button>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('new_values_btn', function ($row) {
                if ($row->new_values) {
                    return '<button type="button" class="btn btn-sm btn-outline-success" onclick="viewJsonData(' . $row->id . ', \'new\')">
                        <i class="ri-eye-line"></i> View New Values
                    </button>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('created_at_formatted', function ($row) {
                return $row->created_at->format('Y-m-d H:i:s');
            })
            ->rawColumns(['event_badge', 'old_values_btn', 'new_values_btn'])
            ->make(true);
    }

    public function getJsonData(Request $request, int $id, string $type): JsonResponse
    {
        if (!in_array($type, ['old', 'new'])) {
            return response()->json(['success' => false, 'message' => 'Invalid type'], 400);
        }

        $log = ActivityLog::findOrFail($id);

        $data = $type === 'old' ? $log->old_values : $log->new_values;

        return response()->json([
            'success' => true,
            'data' => $data,
            'type' => $type,
            'event' => $log->event,
            'model_type' => $log->model_type,
        ]);
    }
}

