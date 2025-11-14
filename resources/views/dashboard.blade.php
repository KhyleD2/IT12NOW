@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white p-6 rounded shadow">
        <h3 class="text-gray-700 font-bold">Total Inventory</h3>
        <p class="text-2xl mt-2 text-green-700">{{ $totalProducts ?? 0 }}</p>
        @if(($lowStockProducts->count() ?? 0) > 0)
            <p class="text-sm text-yellow-600 mt-1">⚠️ {{ $lowStockProducts->count() }} low stock</p>
        @endif
        @if(($outOfStockCount ?? 0) > 0)
            <p class="text-sm text-red-600">🚫 {{ $outOfStockCount }} out of stock</p>
        @endif
    </div>
    <div class="bg-white p-6 rounded shadow">
        <h3 class="text-gray-700 font-bold">Total Customers</h3>
        <p class="text-2xl mt-2 text-green-700">{{ $totalCustomers ?? 0 }}</p>
    </div>
    <div class="bg-white p-6 rounded shadow">
        <h3 class="text-gray-700 font-bold">Total Suppliers</h3>
        <p class="text-2xl mt-2 text-green-700">{{ $totalSuppliers ?? 0 }}</p>
    </div>
    <div class="bg-white p-6 rounded shadow">
        <h3 class="text-gray-700 font-bold">Total Sales</h3>
        <p class="text-2xl mt-2 text-green-700">{{ $totalSales ?? 0 }}</p>
    </div>
</div>

<!-- Revenue Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded shadow text-white">
        <h3 class="font-bold text-lg">Today's Revenue</h3>
        <p class="text-3xl mt-2">₱{{ number_format($todayRevenue ?? 0, 2) }}</p>
    </div>
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 rounded shadow text-white">
        <h3 class="font-bold text-lg">This Week</h3>
        <p class="text-3xl mt-2">₱{{ number_format($weekRevenue ?? 0, 2) }}</p>
    </div>
    <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 rounded shadow text-white">
        <h3 class="font-bold text-lg">This Month</h3>
        <p class="text-3xl mt-2">₱{{ number_format($monthRevenue ?? 0, 2) }}</p>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Revenue Chart with Tabs -->
    <div class="bg-white p-6 rounded shadow">
        <h3 class="text-gray-800 font-bold text-lg mb-4">Revenue Overview</h3>
        
        <!-- Tab Buttons -->
        <div class="flex gap-2 mb-4 border-b">
            <button onclick="showChart('daily')" id="dailyTab" class="px-4 py-2 font-semibold text-blue-600 border-b-2 border-blue-600">
                Daily
            </button>
            <button onclick="showChart('weekly')" id="weeklyTab" class="px-4 py-2 font-semibold text-gray-600 hover:text-blue-600">
                Weekly
            </button>
            <button onclick="showChart('monthly')" id="monthlyTab" class="px-4 py-2 font-semibold text-gray-600 hover:text-blue-600">
                Monthly
            </button>
        </div>

        <div style="height: 300px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Top Selling Products -->
    <div class="bg-white p-6 rounded shadow">
        <h3 class="text-gray-800 font-bold text-lg mb-4">Top Selling Products</h3>
        @if($topProducts && $topProducts->count() > 0)
            <div class="space-y-4">
                @foreach($topProducts as $index => $product)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 text-green-700 rounded-full flex items-center justify-center font-bold">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                                <p class="text-sm text-gray-600">{{ $product->total_sold }} units sold</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-green-600">₱{{ number_format($product->total_revenue, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-8">No sales data yet</p>
        @endif
    </div>
</div>

<!-- Low Stock Alert -->
@if($lowStockProducts && $lowStockProducts->count() > 0)
<div class="bg-white p-6 rounded shadow">
    <div class="flex items-center gap-2 mb-4">
        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <h3 class="text-gray-800 font-bold text-lg">Low Stock Alert</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($lowStockProducts as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                            {{ $product->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-red-600 font-bold">{{ $product->stock_quantity ?? $product->Quantity_in_Stock }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                            ₱{{ number_format($product->unit_price ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $qty = $product->stock_quantity ?? $product->Quantity_in_Stock;
                            @endphp
                            @if($qty <= 5)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Critical
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Low
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Expiry Alerts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Expired Products -->
    <div class="bg-white p-6 rounded shadow">
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-red-600 font-bold text-lg">Expired Products</h3>
        </div>
        
        @if($expiredProducts && $expiredProducts->count() > 0)
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @foreach($expiredProducts as $product)
                <div class="flex justify-between items-center p-3 bg-red-50 rounded border-l-4 border-red-600">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $product->Product_Name }}</p>
                        <p class="text-sm text-gray-600">Expired: {{ \Carbon\Carbon::parse($product->expiry_date)->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">Stock: {{ $product->Quantity_in_Stock }}</p>
                    </div>
                    <span class="text-red-600 font-bold text-sm px-3 py-1 bg-red-100 rounded">EXPIRED</span>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-8">No expired products</p>
        @endif
    </div>

    <!-- Expiring Soon Products -->
    <div class="bg-white p-6 rounded shadow">
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-orange-600 font-bold text-lg">Expiring Soon (Within 7 Days)</h3>
        </div>
        
        @if($expiringSoonProducts && $expiringSoonProducts->count() > 0)
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @foreach($expiringSoonProducts as $product)
                <div class="flex justify-between items-center p-3 bg-orange-50 rounded border-l-4 border-orange-600">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $product->Product_Name }}</p>
                        <p class="text-sm text-gray-600">Expires: {{ \Carbon\Carbon::parse($product->expiry_date)->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">Stock: {{ $product->Quantity_in_Stock }}</p>
                    </div>
                    <span class="text-orange-600 font-bold text-sm px-3 py-1 bg-orange-100 rounded">{{ $product->days_until_expiry }} {{ $product->days_until_expiry == 1 ? 'day' : 'days' }}</span>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-8">No products expiring soon</p>
        @endif
    </div>
</div>


<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
    // Data from Laravel
    const chartData = {
        daily: {
            labels: @json($dailyLabels ?? []),
            data: @json($dailyData ?? [])
        },
        weekly: {
            labels: @json($weeklyLabels ?? []),
            data: @json($weeklyRevenue ?? [])
        },
        monthly: {
            labels: @json($monthlyLabels ?? []),
            data: @json($monthlyRevenue ?? [])
        }
    };

    let revenueChart = null;
    let currentView = 'daily';

    function createChart(type) {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        if (revenueChart) {
            revenueChart.destroy();
        }

        revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData[type].labels,
                datasets: [{
                    label: 'Revenue (₱)',
                    data: chartData[type].data,
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '₱' + context.parsed.y.toLocaleString('en-US', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    function showChart(type) {
        currentView = type;
        createChart(type);
        
        // Update tab styles
        document.getElementById('dailyTab').className = type === 'daily' 
            ? 'px-4 py-2 font-semibold text-blue-600 border-b-2 border-blue-600'
            : 'px-4 py-2 font-semibold text-gray-600 hover:text-blue-600';
        
        document.getElementById('weeklyTab').className = type === 'weekly'
            ? 'px-4 py-2 font-semibold text-blue-600 border-b-2 border-blue-600'
            : 'px-4 py-2 font-semibold text-gray-600 hover:text-blue-600';
        
        document.getElementById('monthlyTab').className = type === 'monthly'
            ? 'px-4 py-2 font-semibold text-blue-600 border-b-2 border-blue-600'
            : 'px-4 py-2 font-semibold text-gray-600 hover:text-blue-600';
    }

    // Initialize chart on page load
    document.addEventListener('DOMContentLoaded', function() {
        createChart('daily');
    });
</script>
@endsection