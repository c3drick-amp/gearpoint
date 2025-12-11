@extends('layouts.app')

@section('title', 'Reports - Motorshop POS')

@section('content')
<h2 style="margin-bottom: 1.5rem;">Reports & Analytics</h2>

<div class="card">
    <div class="card-header">Generate Report</div>
    
    <form action="{{ route('reports') }}" method="GET">
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Report Type *</label>
                <select name="report_type" class="form-control" required>
                    <option value="sales" {{ request('report_type') == 'sales' ? 'selected' : '' }}>Sales Report</option>
                    <option value="inventory" {{ request('report_type') == 'inventory' ? 'selected' : '' }}>Inventory Report</option>
                    <option value="customers" {{ request('report_type') == 'customers' ? 'selected' : '' }}>Customer Report</option>
                    <option value="services" {{ request('report_type') == 'services' ? 'selected' : '' }}>Service Report</option>
                </select>
                <span class="form-hint">💡 Select the type of report to generate</span>
            </div>

            <div class="form-group">
                <label class="form-label">Period *</label>
                <select name="period" class="form-control" id="periodSelect" required>
                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="this_week" {{ request('period') == 'this_week' ? 'selected' : '' }}>This Week</option>
                    <option value="this_month" {{ request('period') == 'this_month' ? 'selected' : '' }}>This Month</option>
                    <option value="last_month" {{ request('period') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                    <option value="custom" {{ request('period') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                </select>
            </div>
        </div>

        <div class="form-row" id="customDateRange" style="{{ request('period') == 'custom' ? '' : 'display: none;' }}">
            <div class="form-group">
                <label class="form-label">Date From</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                <span class="form-hint">💡 Start date for report</span>
            </div>

            <div class="form-group">
                <label class="form-label">Date To</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                <span class="form-hint">💡 End date for report</span>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-right: 0.5rem;">Generate Report</button>
        <button type="button" class="btn btn-success" onclick="window.print()">Print Report</button>
    </form>
</div>

@if(request('report_type'))
<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header">
        {{ ucfirst(str_replace('_', ' ', request('report_type'))) }} Report
        @if(request('period') == 'custom')
            ({{ request('date_from') }} to {{ request('date_to') }})
        @else
            ({{ ucfirst(str_replace('_', ' ', request('period'))) }})
        @endif
    </div>

    @if(request('report_type') == 'sales')
        <!-- Sales Report -->
        <div style="margin-bottom: 1rem;">
            <h3 style="font-size: 1.1rem; margin-bottom: 0.5rem;">Sales Summary</h3>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">{{ $reportData['total_transactions'] ?? 0 }}</div>
                    <div class="stat-label">Total Transactions</div>
                </div>
                <div class="stat-card green">
                    <div class="stat-value">₱{{ number_format($reportData['total_sales'] ?? 0, 2) }}</div>
                    <div class="stat-label">Total Sales</div>
                </div>
                <div class="stat-card blue">
                    <div class="stat-value">₱{{ number_format($reportData['average_sale'] ?? 0, 2) }}</div>
                    <div class="stat-label">Average Sale</div>
                </div>
            </div>
        </div>

        @if(isset($reportData['sales']) && count($reportData['sales']) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData['sales'] as $sale)
                <tr>
                    <td>#{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $sale->created_at->format('M d, Y h:i A') }}</td>
                    <td>{{ $sale->customer ? $sale->customer->first_name . ' ' . $sale->customer->last_name : 'Walk-in' }}</td>
                    <td>{{ $sale->saleItems->sum('quantity') }}</td>
                    <td>₱{{ number_format($sale->total_amount, 2) }}</td>
                    <td>{{ ucfirst($sale->payment_method) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="text-align: center; color: #7f8c8d; padding: 2rem;">No sales data available for this period</p>
        @endif

    @elseif(request('report_type') == 'inventory')
        <!-- Inventory Report -->
        @if(isset($reportData['products']) && count($reportData['products']) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Current Stock</th>
                    <th>Cost Price</th>
                    <th>Selling Price</th>
                    <th>Stock Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData['products'] as $product)
                <tr style="{{ $product->stock <= $product->reorder_level ? 'background: #fff3cd;' : '' }}">
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>₱{{ number_format($product->cost_price, 2) }}</td>
                    <td>₱{{ number_format($product->selling_price, 2) }}</td>
                    <td>₱{{ number_format($product->stock * $product->cost_price, 2) }}</td>
                </tr>
                @endforeach
                <tr style="background: #f8f9fa; font-weight: bold;">
                    <td colspan="6" style="text-align: right;">Total Stock Value:</td>
                    <td>₱{{ number_format($reportData['total_stock_value'] ?? 0, 2) }}</td>
                </tr>
            </tbody>
        </table>
        @else
        <p style="text-align: center; color: #7f8c8d; padding: 2rem;">No inventory data available</p>
        @endif

    @elseif(request('report_type') == 'customers')
        <!-- Customer Report -->
        @if(isset($reportData['customers']) && count($reportData['customers']) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Phone</th>
                    <th>Total Purchases</th>
                    <th>Total Spent</th>
                    <th>Last Purchase</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData['customers'] as $customer)
                <tr>
                    <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>{{ $customer->sales_count }}</td>
                    <td>₱{{ number_format($customer->total_spent, 2) }}</td>
                    <td>{{ $customer->last_purchase ? $customer->last_purchase->format('M d, Y') : 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="text-align: center; color: #7f8c8d; padding: 2rem;">No customer data available</p>
        @endif

    @endif
</div>
@else
<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header">Report Preview</div>
    <div style="padding: 2rem; text-align: center; color: #7f8c8d;">
        <p>Select report parameters and click "Generate Report" to view results</p>
    </div>
</div>
@endif

<script>
document.getElementById('periodSelect').addEventListener('change', function() {
    const customRange = document.getElementById('customDateRange');
    if (this.value === 'custom') {
        customRange.style.display = 'grid';
    } else {
        customRange.style.display = 'none';
    }
});
</script>
@endsection