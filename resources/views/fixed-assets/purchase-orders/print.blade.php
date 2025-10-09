<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order - {{ $purchaseOrder->po_number }}</title>
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
        .po-title {
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
        .shipping-info {
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
        .totals {
            width: 40%;
            float: right;
        }
        .totals table {
            width: 100%;
        }
        .totals th {
            text-align: right;
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
            <p>{{ $purchaseOrder->branch->br_name ?? 'Head Office' }}</p>
            <p>{{ $purchaseOrder->billing_address ?? 'Address not specified' }}</p>
        </div>
    </div>

    <div class="po-title">PURCHASE ORDER</div>

    <div class="info-section">
        <div class="supplier-info">
            <div class="info-box">
                <h3>Supplier</h3>
                <p><strong>{{ $purchaseOrder->supplier->name ?? 'N/A' }}</strong></p>
                <p>Contact: {{ $purchaseOrder->supplier->contact_person ?? 'N/A' }}</p>
                <p>Email: {{ $purchaseOrder->supplier->email ?? 'N/A' }}</p>
                <p>Phone: {{ $purchaseOrder->supplier->phone ?? 'N/A' }}</p>
                <p>Address: {{ $purchaseOrder->supplier->address ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="shipping-info">
            <div class="info-box">
                <h3>Purchase Order Details</h3>
                <p><strong>PO Number:</strong> {{ $purchaseOrder->po_number }}</p>
                <p><strong>Date:</strong> {{ $purchaseOrder->created_at->format('Y-m-d') }}</p>
                <p><strong>Expected Delivery:</strong> {{ $purchaseOrder->expected_delivery_date ? date('Y-m-d', strtotime($purchaseOrder->expected_delivery_date)) : 'N/A' }}</p>
                <p><strong>Payment Terms:</strong> {{ $purchaseOrder->payment_terms ?? 'N/A' }}</p>
                <p><strong>Shipping Terms:</strong> {{ $purchaseOrder->shipping_terms ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="info-section">
        <div class="supplier-info">
            <div class="info-box">
                <h3>Shipping Address</h3>
                <p>{{ $purchaseOrder->delivery_address ?? $purchaseOrder->branch->br_name ?? 'Address not specified' }}</p>
            </div>
        </div>
        <div class="shipping-info">
            <div class="info-box">
                <h3>Billing Address</h3>
                <p>{{ $purchaseOrder->billing_address ?? $purchaseOrder->branch->br_name ?? 'Address not specified' }}</p>
            </div>
        </div>
    </div>

    <h3>Order Items</h3>
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
                $items = json_decode($purchaseOrder->items, true);
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
    </table>

    <div class="totals">
        <table>
            <tr>
                <th>Subtotal:</th>
                <td>{{ number_format($subtotal, 2) }}</td>
            </tr>
            @if($purchaseOrder->discount_amount > 0)
            <tr>
                <th>Discount:</th>
                <td>{{ number_format($purchaseOrder->discount_amount, 2) }}</td>
            </tr>
            @endif
            @if($purchaseOrder->tax_amount > 0)
            <tr>
                <th>Tax:</th>
                <td>{{ number_format($purchaseOrder->tax_amount, 2) }}</td>
            </tr>
            @endif
            @if($purchaseOrder->shipping_cost > 0)
            <tr>
                <th>Shipping:</th>
                <td>{{ number_format($purchaseOrder->shipping_cost, 2) }}</td>
            </tr>
            @endif
            <tr>
                <th>Grand Total:</th>
                <td><strong>{{ number_format($purchaseOrder->total_cost, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    <div style="clear: both;"></div>

    @if($purchaseOrder->remarks)
    <div class="info-box" style="margin-top: 20px;">
        <h3>Remarks</h3>
        <p>{{ $purchaseOrder->remarks }}</p>
    </div>
    @endif

    <div class="footer">
        <p><strong>Terms and Conditions:</strong></p>
        <ol>
            <li>Please send two copies of your invoice.</li>
            <li>Enter this order in accordance with the prices, terms, delivery method, and specifications listed above.</li>
            <li>Please notify us immediately if you are unable to ship as specified.</li>
            <li>Send all correspondence to the address above.</li>
        </ol>
    </div>

    <div class="signature">
        Authorized By
    </div>
    <div class="signature" style="float: right;">
        Received By
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
