<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Models\Supplier;
use App\Models\Branch;
use App\Models\Department;
use App\Models\User;
use App\Models\AssetCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with([
            'supplier',
            'branch',
            'department',
            'createdBy',
            'purchaseRequest'
        ])->latest()->paginate(10);

        return view('fixed-assets.purchase-orders.index', compact('purchaseOrders'));
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
        $purchaseRequests = PurchaseRequest::where('status', 'approved')
            ->whereDoesntHave('purchaseOrder')
            ->latest()
            ->get();
        
        $poNumber = PurchaseOrder::generatePONumber();
        
        return view('fixed-assets.purchase-orders.create', compact(
            'suppliers', 
            'branches', 
            'departments', 
            'categories', 
            'purchaseRequests',
            'poNumber'
        ));
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
            'po_number' => 'required|string|max:255|unique:purchase_orders,po_number',
            'supplier_id' => 'required|exists:suppliers,id',
            'branch_id' => 'required|exists:branches,id',
            'department_id' => 'required|exists:departments,id',
            'user_id' => 'required|exists:users,id',
            'purchase_request_id' => 'nullable|exists:purchase_requests,id',
            'expected_delivery_date' => 'required|date|after_or_equal:today',
            'payment_terms' => 'nullable|string|max:255',
            'shipping_terms' => 'nullable|string|max:255',
            'delivery_address' => 'nullable|string',
            'billing_address' => 'nullable|string',
            'shipping_method' => 'nullable|string|max:255',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string',
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
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }
            
            $discountAmount = $request->discount_amount ?? 0;
            $taxAmount = $request->tax_amount ?? 0;
            $shippingCost = $request->shipping_cost ?? 0;
            
            $totalCost = $subtotal - $discountAmount + $taxAmount + $shippingCost;

            $purchaseOrder = PurchaseOrder::create([
                'po_number' => $request->po_number,
                'purchase_request_id' => $request->purchase_request_id,
                'supplier_id' => $request->supplier_id,
                'branch_id' => $request->branch_id,
                'department_id' => $request->department_id,
                'user_id' => $request->user_id,
                'created_by' => Auth::id(),
                'status' => 'pending',
                'total_cost' => $totalCost,
                'items' => json_encode($request->items),
                'remarks' => $request->remarks,
                'payment_terms' => $request->payment_terms,
                'shipping_terms' => $request->shipping_terms,
                'expected_delivery_date' => $request->expected_delivery_date,
                'delivery_address' => $request->delivery_address,
                'billing_address' => $request->billing_address,
                'shipping_method' => $request->shipping_method,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'shipping_cost' => $shippingCost,
            ]);

            DB::commit();
            
            session()->flash('success', 'Purchase order created successfully.');
            return redirect()->route('fixed-assets.purchase-orders.index');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to create purchase order. ' . $e->getMessage());
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
        $purchaseOrder = PurchaseOrder::with([
            'supplier',
            'branch',
            'department',
            'user',
            'createdBy',
            'approvedBy',
            'rejectedBy',
            'receivedBy',
            'purchaseRequest'
        ])->findOrFail($id);
        
        $categories = AssetCategory::all();

        return view('fixed-assets.purchase-orders.show', compact('purchaseOrder', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        
        if (!in_array($purchaseOrder->status, ['draft', 'pending'])) {
            return back()->with('error', 'Only draft or pending purchase orders can be edited.');
        }
        
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $branches = Branch::orderBy('br_name')->get();
        $departments = Department::orderBy('department_name')->get();
        $categories = AssetCategory::where('is_active', true)->orderBy('name')->get();
        $purchaseRequests = PurchaseRequest::where('status', 'approved')
            ->where(function($query) use ($purchaseOrder) {
                $query->whereDoesntHave('purchaseOrder')
                    ->orWhereHas('purchaseOrder', function($q) use ($purchaseOrder) {
                        $q->where('id', $purchaseOrder->id);
                    });
            })
            ->latest()
            ->get();
        
        return view('fixed-assets.purchase-orders.edit', compact(
            'purchaseOrder',
            'suppliers', 
            'branches', 
            'departments', 
            'categories', 
            'purchaseRequests'
        ));
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
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        
        if (!in_array($purchaseOrder->status, ['draft', 'pending'])) {
            return back()->with('error', 'Only draft or pending purchase orders can be updated.');
        }

        $request->validate([
            'po_number' => 'required|string|max:255|unique:purchase_orders,po_number,' . $id,
            'supplier_id' => 'required|exists:suppliers,id',
            'branch_id' => 'required|exists:branches,id',
            'department_id' => 'required|exists:departments,id',
            'user_id' => 'required|exists:users,id',
            'purchase_request_id' => 'nullable|exists:purchase_requests,id',
            'expected_delivery_date' => 'required|date|after_or_equal:today',
            'payment_terms' => 'nullable|string|max:255',
            'shipping_terms' => 'nullable|string|max:255',
            'delivery_address' => 'nullable|string',
            'billing_address' => 'nullable|string',
            'shipping_method' => 'nullable|string|max:255',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string',
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
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }
            
            $discountAmount = $request->discount_amount ?? 0;
            $taxAmount = $request->tax_amount ?? 0;
            $shippingCost = $request->shipping_cost ?? 0;
            
            $totalCost = $subtotal - $discountAmount + $taxAmount + $shippingCost;

            $purchaseOrder->update([
                'po_number' => $request->po_number,
                'purchase_request_id' => $request->purchase_request_id,
                'supplier_id' => $request->supplier_id,
                'branch_id' => $request->branch_id,
                'department_id' => $request->department_id,
                'user_id' => $request->user_id,
                'total_cost' => $totalCost,
                'items' => json_encode($request->items),
                'remarks' => $request->remarks,
                'payment_terms' => $request->payment_terms,
                'shipping_terms' => $request->shipping_terms,
                'expected_delivery_date' => $request->expected_delivery_date,
                'delivery_address' => $request->delivery_address,
                'billing_address' => $request->billing_address,
                'shipping_method' => $request->shipping_method,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'shipping_cost' => $shippingCost,
            ]);

            DB::commit();
            
            return redirect()->route('fixed-assets.purchase-orders.index')
                ->with('success', 'Purchase order updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to update purchase order. ' . $e->getMessage());
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
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        
        if (!in_array($purchaseOrder->status, ['draft', 'pending'])) {
            return back()->with('error', 'Only draft or pending purchase orders can be deleted.');
        }

        DB::beginTransaction();
        try {
            $purchaseOrder->delete();

            DB::commit();
            return redirect()->route('fixed-assets.purchase-orders.index')
                ->with('success', 'Purchase order deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to delete purchase order. ' . $e->getMessage());
        }
    }

    /**
     * Approve the purchase order
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        if ($purchaseOrder->status !== 'pending') {
            return back()->with('error', 'Only pending purchase orders can be approved.');
        }

        $purchaseOrder->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now()
        ]);

        return redirect()->route('fixed-assets.purchase-orders.show', $id)
            ->with('success', 'Purchase order approved successfully.');
    }

    /**
     * Reject the purchase order
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        if ($purchaseOrder->status !== 'pending') {
            return back()->with('error', 'Only pending purchase orders can be rejected.');
        }

        $purchaseOrder->update([
            'status' => 'rejected',
            'rejected_by' => Auth::id(),
            'rejected_at' => now()
        ]);

        return redirect()->route('fixed-assets.purchase-orders.show', $id)
            ->with('success', 'Purchase order rejected successfully.');
    }

    /**
     * Mark the purchase order as received
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function receive($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        if ($purchaseOrder->status !== 'approved') {
            return back()->with('error', 'Only approved purchase orders can be marked as received.');
        }

        $purchaseOrder->update([
            'status' => 'completed',
            'received_by' => Auth::id(),
            'completed_at' => now(),
            'delivery_date' => now()
        ]);

        return redirect()->route('fixed-assets.purchase-orders.show', $id)
            ->with('success', 'Purchase order marked as received successfully.');
    }

    /**
     * Get purchase request details
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPurchaseRequestDetails(Request $request)
    {
        $purchaseRequest = PurchaseRequest::with(['supplier', 'branch', 'department', 'user'])
            ->findOrFail($request->purchase_request_id);
        
        return response()->json([
            'purchaseRequest' => $purchaseRequest
        ]);
    }

    /**
     * Get users for a specific branch and department
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBranchUsers(Request $request)
    {
        $users = User::whereHas('employee', function($query) use ($request) {
            $query->where('branch_id', $request->branch_id)
                ->when($request->department_id, function($q) use ($request) {
                    $q->where('department_id', $request->department_id);
                });
        })
        ->get()
        ->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name
            ];
        });

        return response()->json([
            'users' => $users
        ]);
    }

    /**
     * Print purchase order
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print($id)
    {
        $purchaseOrder = PurchaseOrder::with([
            'supplier',
            'branch',
            'department',
            'user',
            'createdBy',
            'approvedBy',
            'purchaseRequest'
        ])->findOrFail($id);
        
        $categories = AssetCategory::all();

        return view('fixed-assets.purchase-orders.print', compact('purchaseOrder', 'categories'));
    }
}
