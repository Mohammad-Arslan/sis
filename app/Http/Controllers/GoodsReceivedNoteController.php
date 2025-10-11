<?php

namespace App\Http\Controllers;

use App\Models\GoodsReceivedNote;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\AssetCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GoodsReceivedNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grns = GoodsReceivedNote::with(['supplier', 'branch', 'department', 'user', 'receivedBy', 'verifiedBy', 'rejectedBy', 'purchaseOrder'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('fixed-assets.grn.index', compact('grns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $purchaseOrders = PurchaseOrder::where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();
        $suppliers = Supplier::all();
        $branches = Branch::all();
        $departments = Department::all();
        $categories = AssetCategory::all();
        
        // Generate a unique GRN number
        $latestGrn = GoodsReceivedNote::orderBy('created_at', 'desc')->first();
        $grnNumber = 'GRN-' . date('Ymd') . '-' . sprintf('%03d', $latestGrn ? intval(substr($latestGrn->grn_number, -3)) + 1 : 1);
        
        return view('fixed-assets.grn.create', compact('purchaseOrders', 'suppliers', 'branches', 'departments', 'categories', 'grnNumber'));
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
            'grn_number' => 'required|unique:goods_received_notes',
            'received_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'branch_id' => 'required|exists:branches,id',
            'department_id' => 'required|exists:departments,id',
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'delivery_note_number' => 'nullable|string',
            'invoice_number' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $totalCost = 0;
            foreach ($request->items as $item) {
                $totalCost += $item['quantity'] * $item['unit_price'];
            }

            $grn = GoodsReceivedNote::create([
                'purchase_order_id' => $request->purchase_order_id,
                'grn_number' => $request->grn_number,
                'received_date' => $request->received_date,
                'supplier_id' => $request->supplier_id,
                'branch_id' => $request->branch_id,
                'department_id' => $request->department_id,
                'user_id' => $request->user_id,
                'received_by' => Auth::id(),
                'items' => json_encode($request->items),
                'total_cost' => $totalCost,
                'status' => 'pending',
                'remarks' => $request->remarks,
                'delivery_note_number' => $request->delivery_note_number,
                'invoice_number' => $request->invoice_number,
            ]);

            // If this GRN is linked to a purchase order, update the PO status
            if ($request->purchase_order_id) {
                $purchaseOrder = PurchaseOrder::find($request->purchase_order_id);
                if ($purchaseOrder) {
                    $purchaseOrder->status = 'completed';
                    $purchaseOrder->save();
                }
            }

            DB::commit();
            session()->flash('success', 'Goods Received Note created successfully.');
            return redirect()->route('fixed-assets.grn.index');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to create Goods Received Note. ' . $e->getMessage());
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
        $grn = GoodsReceivedNote::with(['supplier', 'branch', 'department', 'user', 'receivedBy', 'verifiedBy', 'rejectedBy', 'purchaseOrder'])
            ->findOrFail($id);
        $categories = AssetCategory::all();
        
        return view('fixed-assets.grn.show', compact('grn', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $grn = GoodsReceivedNote::findOrFail($id);
        
        // Only allow editing if the GRN is still pending
        if ($grn->status !== 'pending') {
            return redirect()->route('fixed-assets.grn.show', $id)
                ->with('error', 'Cannot edit a verified or rejected GRN.');
        }
        
        $purchaseOrders = PurchaseOrder::where('status', 'approved')
            ->orWhere('id', $grn->purchase_order_id)
            ->orderBy('created_at', 'desc')
            ->get();
        $suppliers = Supplier::all();
        $branches = Branch::all();
        $departments = Department::all();
        $categories = AssetCategory::all();
        
        return view('fixed-assets.grn.edit', compact('grn', 'purchaseOrders', 'suppliers', 'branches', 'departments', 'categories'));
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
        $grn = GoodsReceivedNote::findOrFail($id);
        
        // Only allow updating if the GRN is still pending
        if ($grn->status !== 'pending') {
            return redirect()->route('fixed-assets.grn.show', $id)
                ->with('error', 'Cannot update a verified or rejected GRN.');
        }
        
        $request->validate([
            'grn_number' => 'required|unique:goods_received_notes,grn_number,' . $id,
            'received_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'branch_id' => 'required|exists:branches,id',
            'department_id' => 'required|exists:departments,id',
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'delivery_note_number' => 'nullable|string',
            'invoice_number' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $totalCost = 0;
            foreach ($request->items as $item) {
                $totalCost += $item['quantity'] * $item['unit_price'];
            }

            // If purchase order ID has changed, update the old PO status
            if ($grn->purchase_order_id && $grn->purchase_order_id != $request->purchase_order_id) {
                $oldPO = PurchaseOrder::find($grn->purchase_order_id);
                if ($oldPO) {
                    $oldPO->status = 'approved';
                    $oldPO->save();
                }
            }

            $grn->update([
                'purchase_order_id' => $request->purchase_order_id,
                'grn_number' => $request->grn_number,
                'received_date' => $request->received_date,
                'supplier_id' => $request->supplier_id,
                'branch_id' => $request->branch_id,
                'department_id' => $request->department_id,
                'user_id' => $request->user_id,
                'items' => json_encode($request->items),
                'total_cost' => $totalCost,
                'remarks' => $request->remarks,
                'delivery_note_number' => $request->delivery_note_number,
                'invoice_number' => $request->invoice_number,
            ]);

            // If this GRN is linked to a purchase order, update the PO status
            if ($request->purchase_order_id) {
                $purchaseOrder = PurchaseOrder::find($request->purchase_order_id);
                if ($purchaseOrder) {
                    $purchaseOrder->status = 'completed';
                    $purchaseOrder->save();
                }
            }

            DB::commit();
            session()->flash('success', 'Goods Received Note updated successfully.');
            return redirect()->route('fixed-assets.grn.show', $id);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to update Goods Received Note. ' . $e->getMessage());
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
        $grn = GoodsReceivedNote::findOrFail($id);
        
        // Only allow deletion if the GRN is still pending
        if ($grn->status !== 'pending') {
            return redirect()->route('fixed-assets.grn.index')
                ->with('error', 'Cannot delete a verified or rejected GRN.');
        }
        
        DB::beginTransaction();
        try {
            // If this GRN is linked to a purchase order, update the PO status back to approved
            if ($grn->purchase_order_id) {
                $purchaseOrder = PurchaseOrder::find($grn->purchase_order_id);
                if ($purchaseOrder) {
                    $purchaseOrder->status = 'approved';
                    $purchaseOrder->save();
                }
            }
            
            $grn->delete();
            
            DB::commit();
            return redirect()->route('fixed-assets.grn.index')
                ->with('success', 'Goods Received Note deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('fixed-assets.grn.index')
                ->with('error', 'Failed to delete Goods Received Note. ' . $e->getMessage());
        }
    }

    /**
     * Verify the GRN.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function verify($id)
    {
        $grn = GoodsReceivedNote::findOrFail($id);
        
        // Only allow verification if the GRN is still pending
        if ($grn->status !== 'pending') {
            return redirect()->route('fixed-assets.grn.show', $id)
                ->with('error', 'This GRN has already been verified or rejected.');
        }
        
        DB::beginTransaction();
        try {
            $grn->update([
                'status' => 'verified',
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);
            
            // Here you could add code to create assets in the inventory based on the GRN items
            
            DB::commit();
            return redirect()->route('fixed-assets.grn.show', $id)
                ->with('success', 'Goods Received Note verified successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('fixed-assets.grn.show', $id)
                ->with('error', 'Failed to verify Goods Received Note. ' . $e->getMessage());
        }
    }

    /**
     * Reject the GRN.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);
        
        $grn = GoodsReceivedNote::findOrFail($id);
        
        // Only allow rejection if the GRN is still pending
        if ($grn->status !== 'pending') {
            return redirect()->route('fixed-assets.grn.show', $id)
                ->with('error', 'This GRN has already been verified or rejected.');
        }
        
        DB::beginTransaction();
        try {
            $grn->update([
                'status' => 'rejected',
                'rejected_by' => Auth::id(),
                'rejected_at' => now(),
                'rejection_reason' => $request->rejection_reason,
            ]);
            
            // If this GRN is linked to a purchase order, update the PO status back to approved
            if ($grn->purchase_order_id) {
                $purchaseOrder = PurchaseOrder::find($grn->purchase_order_id);
                if ($purchaseOrder) {
                    $purchaseOrder->status = 'approved';
                    $purchaseOrder->save();
                }
            }
            
            DB::commit();
            return redirect()->route('fixed-assets.grn.show', $id)
                ->with('success', 'Goods Received Note rejected successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('fixed-assets.grn.show', $id)
                ->with('error', 'Failed to reject Goods Received Note. ' . $e->getMessage());
        }
    }

    /**
     * Get purchase order details for AJAX request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPurchaseOrderDetails(Request $request)
    {
        $purchaseOrder = PurchaseOrder::with(['supplier', 'branch', 'department', 'user'])
            ->find($request->purchase_order_id);
        
        if (!$purchaseOrder) {
            return response()->json(['error' => 'Purchase order not found'], 404);
        }
        
        return response()->json([
            'purchaseOrder' => $purchaseOrder
        ]);
    }

    /**
     * Get users for a specific branch and department.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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

    /**
     * Print the GRN.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print($id)
    {
        $grn = GoodsReceivedNote::with(['supplier', 'branch', 'department', 'user', 'receivedBy', 'verifiedBy', 'purchaseOrder'])
            ->findOrFail($id);
        $categories = AssetCategory::all();
        
        return view('fixed-assets.grn.print', compact('grn', 'categories'));
    }
}
