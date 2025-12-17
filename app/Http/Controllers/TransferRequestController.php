<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use App\Models\TransferRequest;
use App\Models\TransferItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransferRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transferRequests = TransferRequest::with([
            'sourceBranch',
            'destinationBranch',
            'sourceDepartment',
            'destinationDepartment',
            'requestedByUser',
            'approvedByUser',
            'receivedByUser',
            'transferItems.asset'
        ])->latest()->paginate(10);

        $branches = Branch::all();

        return view('fixed-assets.transfer-requests.index', compact('transferRequests', 'branches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branches = Branch::all();
        $departments = Department::all();
        $assets = Asset::all();
        $users = User::all();

        return view('fixed-assets.transfer-requests.create', compact('branches', 'departments', 'assets', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'source_branch_id' => 'required|exists:branches,id',
            'destination_branch_id' => 'required|exists:branches,id|different:source_branch_id',
            'source_department_id' => 'nullable|exists:departments,id',
            'destination_department_id' => 'nullable|exists:departments,id',
            'description' => 'nullable|string',
            'reason' => 'required|string',
            'assets' => 'required|array|min:1',
            'assets.*.asset_id' => 'required|exists:assets,id',
            'assets.*.quantity' => 'required|integer|min:1',
            'assets.*.condition' => 'nullable|string',
            'assets.*.notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $transferRequest = TransferRequest::create([
                'transfer_number' => 'TR-' . date('YmdHis'),
                'title' => $request->title,
                'description' => $request->description,
                'source_branch_id' => $request->source_branch_id,
                'destination_branch_id' => $request->destination_branch_id,
                'source_department_id' => $request->source_department_id,
                'destination_department_id' => $request->destination_department_id,
                'assigned_to_user_id' => $request->assigned_to_user_id,
                'requested_by' => Auth::id(),
                'reason' => $request->reason,
                'status' => 'pending'
            ]);

            foreach ($request->assets as $asset) {
                TransferItem::create([
                    'transfer_request_id' => $transferRequest->id,
                    'asset_id' => $asset['asset_id'],
                    'quantity' => $asset['quantity'],
                    'condition' => $asset['condition'] ?? null,
                    'notes' => $asset['notes'] ?? null
                ]);
            }

            DB::commit();
            
            session()->flash('success', 'Transfer request created successfully.');
            return redirect()->route('fixed-assets.transfer-requests.index');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to create transfer request. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transferRequest = TransferRequest::with([
            'sourceBranch',
            'destinationBranch',
            'sourceDepartment',
            'destinationDepartment',
            'requestedByUser',
            'approvedByUser',
            'receivedByUser',
            'transferItems.asset'
        ])->findOrFail($id);

        return view('fixed-assets.transfer-requests.show', compact('transferRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $transferRequest = TransferRequest::with(['transferItems.asset'])->findOrFail($id);
        $branches = Branch::all();
        $departments = Department::all();
        $assets = Asset::with(['assignedTo' => function($query) {
                $query->withTrashed();
            }])
            ->where('status', 'available')
            ->orWhereHas('transferItems', function($query) use ($id) {
                $query->where('transfer_request_id', $id);
            })->get();

        return view('fixed-assets.transfer-requests.edit', compact('transferRequest', 'branches', 'departments', 'assets'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $transferRequest = TransferRequest::findOrFail($id);

        if ($transferRequest->status !== 'pending') {
            return back()->with('error', 'Only pending transfer requests can be updated.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'source_branch_id' => 'required|exists:branches,id',
            'destination_branch_id' => 'required|exists:branches,id|different:source_branch_id',
            'source_department_id' => 'nullable|exists:departments,id',
            'destination_department_id' => 'nullable|exists:departments,id',
            'description' => 'nullable|string',
            'reason' => 'required|string',
            'assets' => 'required|array|min:1',
            'assets.*.asset_id' => 'required|exists:assets,id',
            'assets.*.quantity' => 'required|integer|min:1',
            'assets.*.condition' => 'nullable|string',
            'assets.*.notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $transferRequest->update([
                'title' => $request->title,
                'description' => $request->description,
                'source_branch_id' => $request->source_branch_id,
                'destination_branch_id' => $request->destination_branch_id,
                'source_department_id' => $request->source_department_id,
                'destination_department_id' => $request->destination_department_id,
                'reason' => $request->reason
            ]);

            // Delete existing items
            $transferRequest->transferItems()->delete();

            // Create new items
            foreach ($request->assets as $asset) {
                TransferItem::create([
                    'transfer_request_id' => $transferRequest->id,
                    'asset_id' => $asset['asset_id'],
                    'quantity' => $asset['quantity'],
                    'condition' => $asset['condition'] ?? null,
                    'notes' => $asset['notes'] ?? null
                ]);
            }

            DB::commit();
            return redirect()->route('fixed-assets.transfer-requests.index')
                ->with('success', 'Transfer request updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to update transfer request. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transferRequest = TransferRequest::findOrFail($id);

        if ($transferRequest->status !== 'pending') {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending transfer requests can be deleted.'
                ], 400);
            }
            return back()->with('error', 'Only pending transfer requests can be deleted.');
        }

        DB::beginTransaction();
        try {
            $transferRequest->transferItems()->delete();
            $transferRequest->delete();

            DB::commit();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transfer request deleted successfully.'
                ]);
            }
            
            return redirect()->route('fixed-assets.transfer-requests.index')
                ->with('success', 'Transfer request deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete transfer request. ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to delete transfer request. ' . $e->getMessage());
        }
    }

    /**
     * Approve the transfer request
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve($id)
    {
        $transferRequest = TransferRequest::findOrFail($id);

        if ($transferRequest->status !== 'pending') {
            return back()->with('error', 'Only pending transfer requests can be approved.');
        }

        $transferRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'transfer_date' => now()
        ]);

        return redirect()->route('fixed-assets.transfer-requests.show', $id)
            ->with('success', 'Transfer request approved successfully.');
    }

    /**
     * Reject the transfer request
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject($id)
    {
        $transferRequest = TransferRequest::findOrFail($id);

        if ($transferRequest->status !== 'pending') {
            return back()->with('error', 'Only pending transfer requests can be rejected.');
        }

        $transferRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now()
        ]);

        return redirect()->route('fixed-assets.transfer-requests.show', $id)
            ->with('success', 'Transfer request rejected successfully.');
    }

    /**
     * Mark the transfer request as received
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Get assets for a specific branch and department
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Get users for a specific branch and department
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBranchUsers(Request $request)
    {
        $users = Employee::where('branch_id', $request->branch_id)
            ->when($request->department_id, function($query) use ($request) {
                $query->where('department_id', $request->department_id);
            })
            ->get()
            ->map(function($employee) {
                // If preferred_name exists, use it
                if (!empty($employee->preferred_name)) {
                    return [
                        'id' => $employee->user_id,
                        'name' => $employee->preferred_name
                    ];
                }
                
                // Otherwise concatenate first, middle, last names with null safety
                $nameParts = array_filter([
                    $employee->first_name,
                    $employee->middle_name,
                    $employee->last_name
                ], function($part) {
                    return !empty($part);
                });
                
                return [
                    'id' => $employee->user_id,
                    'name' => !empty($nameParts) ? implode(' ', $nameParts) : 'Unknown User'
                ];
            });

        return response()->json([
            'users' => $users
        ]);
    }

    public function getBranchAssets(Request $request)
    {
        $query = Asset::with(['assignedTo' => function($query) {
                $query->withTrashed();
            }])
            ->where('current_branch_id', $request->branch_id)
            ->when($request->department_id, function($query) use ($request) {
                $query->where('current_department_id', $request->department_id);
            });

        $assets = $query->get()->map(function($asset) {
            $assignedUser = $asset->assignedTo;
            $userName = 'Not Assigned';
            
            if ($assignedUser) {
                if (!empty($assignedUser->preferred_name)) {
                    $userName = $assignedUser->preferred_name;
                } else {
                    $nameParts = array_filter([
                        $assignedUser->first_name,
                        $assignedUser->middle_name,
                        $assignedUser->last_name
                    ]);
                    $userName = !empty($nameParts) ? implode(' ', $nameParts) : 'Unknown User';
                }
            }

            return [
                'id' => $asset->id,
                'name' => $asset->name,
                'asset_tag' => $asset->asset_tag,
                'status' => $asset->status,
                'condition' => $asset->condition,
                'assigned_to_user_id' => $asset->assigned_to_user_id,
                'assigned_user_name' => $userName
            ];
        });

        return response()->json([
            'assets' => $assets
        ]);
    }

    public function receive($id)
    {
        $transferRequest = TransferRequest::findOrFail($id);

        if ($transferRequest->status !== 'approved') {
            return back()->with('error', 'Only approved transfer requests can be marked as received.');
        }

        DB::beginTransaction();
        try {
            // Update asset locations
            foreach ($transferRequest->transferItems as $item) {
                $asset = $item->asset;
                $asset->update([
                    'current_branch_id' => $transferRequest->destination_branch_id,
                    'current_department_id' => $transferRequest->destination_department_id,
                    'assigned_to_user_id' => $transferRequest->assigned_to_user_id,
                    'status' => 'active'
                ]);
            }

            $transferRequest->update([
                'status' => 'completed',
                'received_by' => Auth::id(),
                'received_at' => now()
            ]);

            DB::commit();
            return redirect()->route('fixed-assets.transfer-requests.show', $id)
                ->with('success', 'Transfer request marked as received successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to mark transfer request as received. ' . $e->getMessage());
        }
    }
}
