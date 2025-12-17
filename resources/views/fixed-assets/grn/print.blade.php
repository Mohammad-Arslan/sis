<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goods Received Note - {{ $grn->grn_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .header:after {
            content: "";
            display: table;
            clear: both;
        }
        .logo {
            float: left;
            width: 30%;
        }
        .company-info {
            float: right;
            width: 40%;
            text-align: right;
        }
        .grn-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section:after {
            content: "";
            display: table;
            clear: both;
        }
        .supplier-info {
            float: left;
            width: 45%;
        }
        .receiving-info {
            float: right;
            width: 45%;
        }
        .info-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }
        .info-box h3 {
            margin-top: 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 40px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .signature {
            width: 45%;
            float: left;
            margin-top: 20px;
            border-top: 1px solid #333;
            padding-top: 5px;
            text-align: center;
        }
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            @page {
                margin: 1cm;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="{{ asset('Group.png') }}" alt="SuperNovaSchool Logo" style="max-height: 60px;">
        </div>
        <div class="company-info">
            <h2>{{ config('app.name', 'Supernova SIS') }}</h2>
            <p>{{ $grn->branch->br_name ?? 'Head Office' }}</p>
            <p>{{ $grn->branch->address ?? 'Address not specified' }}</p>
        </div>
    </div>

    <div class="grn-title">GOODS RECEIVED NOTE</div>

    <div class="info-section">
        <div class="supplier-info">
            <div class="info-box">
                <h3>Supplier</h3>
                <p><strong>{{ $grn->supplier->name ?? 'N/A' }}</strong></p>
                <p>Contact: {{ $grn->supplier->contact_person ?? 'N/A' }}</p>
                <p>Email: {{ $grn->supplier->email ?? 'N/A' }}</p>
                <p>Phone: {{ $grn->supplier->phone ?? 'N/A' }}</p>
                <p>Address: {{ $grn->supplier->address ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="receiving-info">
            <div class="info-box">
                <h3>GRN Details</h3>
                <p><strong>GRN Number:</strong> {{ $grn->grn_number }}</p>
                <p><strong>Received Date:</strong> {{ $grn->received_date ? date('Y-m-d', strtotime($grn->received_date)) : 'N/A' }}</p>
                @if($grn->purchase_order)
                <p><strong>Purchase Order:</strong> {{ $grn->purchase_order->po_number }}</p>
                @endif
                @if($grn->delivery_note_number)
                <p><strong>Delivery Note:</strong> {{ $grn->delivery_note_number }}</p>
                @endif
                @if($grn->invoice_number)
                <p><strong>Invoice Number:</strong> {{ $grn->invoice_number }}</p>
                @endif
                <p><strong>Status:</strong> {{ ucfirst($grn->status) }}</p>
            </div>
        </div>
    </div>

    <div class="info-section">
        <div class="supplier-info">
            <div class="info-box">
                <h3>Destination</h3>
                <p><strong>Branch:</strong> {{ $grn->branch->br_name ?? 'N/A' }}</p>
                <p><strong>Department:</strong> {{ $grn->department->department_name ?? 'N/A' }}</p>
                <p><strong>User:</strong> {{ $grn->user->first_name ?? '' }} {{ $grn->user->last_name ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="receiving-info">
            <div class="info-box">
                <h3>Processing</h3>
                <p><strong>Received By:</strong> {{ $grn->receivedBy->first_name ?? '' }} {{ $grn->receivedBy->last_name ?? 'N/A' }}</p>
                <p><strong>Received On:</strong> {{ $grn->created_at ? date('Y-m-d H:i', strtotime($grn->created_at)) : 'N/A' }}</p>
                @if($grn->status == 'verified')
                <p><strong>Verified By:</strong> {{ $grn->verifiedBy->first_name ?? '' }} {{ $grn->verifiedBy->last_name ?? 'N/A' }}</p>
                <p><strong>Verified On:</strong> {{ $grn->verified_at ? date('Y-m-d H:i', strtotime($grn->verified_at)) : 'N/A' }}</p>
                @endif
            </div>
        </div>
    </div>

    @if($grn->remarks)
    <div class="info-box" style="margin-top: 20px;">
        <h3>Remarks</h3>
        <p>{{ $grn->remarks }}</p>
    </div>
    @endif

    <h3>Received Items</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Item</th>
                <th>Description</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $items = json_decode($grn->items, true);
                $subtotal = 0;
            @endphp
            @if(is_array($items))
                @foreach($items as $index => $item)
                    @php
                        $quantity = $item['quantity'] ?? 0;
                        $unitPrice = $item['unit_price'] ?? 0;
                        $total = $quantity * $unitPrice;
                        $subtotal += $total;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['name'] ?? 'N/A' }}</td>
                        <td>{{ $item['description'] ?? 'N/A' }}</td>
                        <td>
                            @php
                                $categoryId = $item['category_id'] ?? null;
                                $category = $categories->where('id', $categoryId)->first();
                            @endphp
                            {{ $category->name ?? 'N/A' }}
                        </td>
                        <td>{{ $quantity }}</td>
                        <td>{{ number_format($unitPrice, 2) }}</td>
                        <td>{{ number_format($total, 2) }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6" style="text-align: right;">Total:</th>
                <td><strong>{{ number_format($grn->total_cost, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>This document certifies that the items listed above have been received in good condition, unless otherwise noted.</p>
    </div>

    <div class="signature">
        Received By: {{ $grn->receivedBy->first_name ?? '' }} {{ $grn->receivedBy->last_name ?? 'N/A' }}
    </div>
    <div class="signature" style="float: right;">
        @if($grn->status == 'verified')
        Verified By: {{ $grn->verifiedBy->first_name ?? '' }} {{ $grn->verifiedBy->last_name ?? 'N/A' }}
        @else
        Verification Pending
        @endif
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
