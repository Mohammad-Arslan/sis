<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use App\Models\Supplier;
use App\Models\Branch;
use App\Models\Department;
use App\Models\User;
use App\Models\Employee;
use App\Models\AssetCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchaseRequests = PurchaseRequest::with([
            'supplier',
            'requestedBy',
            'branch',
            'department',
            'user'
        ])->latest()->paginate(10);

        return view('fixed-assets.purchase-requests.index', compact('purchaseRequests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $branches = Branch::orderBy('br_name')->get();
        $departments = Department::orderBy('department_name')->get();
        $categories = AssetCategory::where('is_active', true)->orderBy('name')->get();

        return view('fixed-assets.purchase-requests.create', compact('suppliers', 'branches', 'departments', 'categories'));
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
            'supplier_id' => 'required|exists:suppliers,id',
            'branch_id' => 'required|exists:branches,id',
            'department_id' => 'required|exists:departments,id',
            'user_id' => 'required|exists:users,id',
            'required_date' => 'required|date|after_or_equal:today',
            'budget_code' => 'nullable|string|max:50',
            'justification' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.category_id' => 'required|exists:asset_categories,id',
        ]);

        DB::beginTransaction();
        try {
            // Calculate total cost
            $totalCost = 0;
            foreach ($request->items as $item) {
                $totalCost += $item['quantity'] * $item['unit_price'];
            }

            $purchaseRequest = PurchaseRequest::create([
                'supplier_id' => $request->supplier_id,
                'requested_by' => Auth::id(),
                'branch_id' => $request->branch_id,
                'department_id' => $request->department_id,
                'user_id' => $request->user_id,
                'status' => 'pending',
                'total_cost' => $totalCost,
                'items' => json_encode($request->items),
                'remarks' => $request->remarks,
                'required_date' => $request->required_date,
                'budget_code' => $request->budget_code,
                'justification' => $request->justification,
            ]);

            DB::commit();

            session()->flash('success', 'Purchase request created successfully.');
            return redirect()->route('fixed-assets.purchase-requests.index');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to create purchase request. ' . $e->getMessage());
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
        $purchaseRequest = PurchaseRequest::with([
            'supplier',
            'requestedBy',
            'branch',
            'department',
            'user',
            'approvedBy',
            'rejectedBy'
        ])->findOrFail($id);

        $categories = AssetCategory::all();

        return view('fixed-assets.purchase-requests.show', compact('purchaseRequest', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $purchaseRequest = PurchaseRequest::findOrFail($id);
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $branches = Branch::orderBy('br_name')->get();
        $departments = Department::orderBy('department_name')->get();
        $categories = AssetCategory::where('is_active', true)->orderBy('name')->get();

        return view('fixed-assets.purchase-requests.edit', compact('purchaseRequest', 'suppliers', 'branches', 'departments', 'categories'));
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
        $purchaseRequest = PurchaseRequest::findOrFail($id);

        if ($purchaseRequest->status !== 'pending') {
            return back()->with('error', 'Only pending purchase requests can be updated.');
        }

        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'branch_id' => 'required|exists:branches,id',
            'department_id' => 'required|exists:departments,id',
            'user_id' => 'required|exists:users,id',
            'required_date' => 'required|date|after_or_equal:today',
            'budget_code' => 'nullable|string|max:50',
            'justification' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.category_id' => 'required|exists:asset_categories,id',
        ]);

        DB::beginTransaction();
        try {
            // Calculate total cost
            $totalCost = 0;
            foreach ($request->items as $item) {
                $totalCost += $item['quantity'] * $item['unit_price'];
            }

            $purchaseRequest->update([
                'supplier_id' => $request->supplier_id,
                'branch_id' => $request->branch_id,
                'department_id' => $request->department_id,
                'user_id' => $request->user_id,
                'total_cost' => $totalCost,
                'items' => json_encode($request->items),
                'remarks' => $request->remarks,
                'required_date' => $request->required_date,
                'budget_code' => $request->budget_code,
                'justification' => $request->justification,
            ]);

            DB::commit();

            return redirect()->route('fixed-assets.purchase-requests.index')
                ->with('success', 'Purchase request updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to update purchase request. ' . $e->getMessage());
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
        $purchaseRequest = PurchaseRequest::findOrFail($id);

        if ($purchaseRequest->status !== 'pending') {
            return back()->with('error', 'Only pending purchase requests can be deleted.');
        }

        DB::beginTransaction();
        try {
            $purchaseRequest->delete();

            DB::commit();
            return redirect()->route('fixed-assets.purchase-requests.index')
                ->with('success', 'Purchase request deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to delete purchase request. ' . $e->getMessage());
        }
    }

    /**
     * Approve the purchase request
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve($id)
    {
        $purchaseRequest = PurchaseRequest::findOrFail($id);

        if ($purchaseRequest->status !== 'pending') {
            return back()->with('error', 'Only pending purchase requests can be approved.');
        }

        $purchaseRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now()
        ]);

        return redirect()->route('fixed-assets.purchase-requests.show', $id)
            ->with('success', 'Purchase request approved successfully.');
    }

    /**
     * Reject the purchase request
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject($id)
    {
        $purchaseRequest = PurchaseRequest::findOrFail($id);

        if ($purchaseRequest->status !== 'pending') {
            return back()->with('error', 'Only pending purchase requests can be rejected.');
        }

        $purchaseRequest->update([
            'status' => 'rejected',
            'rejected_by' => Auth::id(),
            'rejected_at' => now()
        ]);

        return redirect()->route('fixed-assets.purchase-requests.show', $id)
            ->with('success', 'Purchase request rejected successfully.');
    }

    /**
     * Get users for a specific branch and department
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBranchUsers(Request $request)
    {
        $users = Employee::where('branch_id', $request->branch_id)
            ->when($request->department_id, function ($query) use ($request) {
                $query->where('department_id', $request->department_id);
            })
            ->get()
            ->map(function ($employee) {
                // If preferred_name exists, use it
                if (! empty($employee->preferred_name)) {
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
                ], function ($part) {
                    return ! empty($part);
                });

                return [
                    'id' => $employee->user_id,
                    'name' => ! empty($nameParts) ? implode(' ', $nameParts) : 'Unknown User'
                ];
            });

        return response()->json([
            'users' => $users
        ]);
    }
}
