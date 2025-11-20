@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div class="bg-green-100 p-4 rounded-lg">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div class="text-right">
                <h3 class="text-gray-600 text-sm font-semibold uppercase">Total Products</h3>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalProducts ?? 0 }}</p>
                @if(($lowStockProducts->count() ?? 0) > 0)
                    <p class="text-xs text-yellow-600 mt-2">⚠️ {{ $lowStockProducts->count() }} low stock</p>
                @endif
                @if(($outOfStockCount ?? 0) > 0)
                    <p class="text-xs text-red-600">🚫 {{ $outOfStockCount }} out of stock</p>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div class="bg-blue-100 p-4 rounded-lg">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="text-right">
                <h3 class="text-gray-600 text-sm font-semibold uppercase">Total Customers</h3>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalCustomers ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div class="bg-purple-100 p-4 rounded-lg">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
            </div>
            <div class="text-right">
                <h3 class="text-gray-600 text-sm font-semibold uppercase">Total Suppliers</h3>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalSuppliers ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-orange-500">
        <div class="flex items-center justify-between">
            <div class="bg-orange-100 p-4 rounded-lg">
                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="text-right">
                <h3 class="text-gray-600 text-sm font-semibold uppercase">Total Sales</h3>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalSales ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-lg shadow-xl text-white">
        <div class="flex items-center justify-between">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-right">
                <h3 class="font-bold text-lg">Today's Revenue</h3>
                <p class="text-4xl font-bold mt-1">₱{{ number_format($todayRevenue ?? 0, 2) }}</p>
                <p class="text-blue-100 text-xs mt-2">{{ \Carbon\Carbon::now()->format('l, M d, Y') }}</p>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-6 rounded-lg shadow-xl text-white">
        <div class="flex items-center justify-between">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <div class="text-right">
                <h3 class="font-bold text-lg">This Week</h3>
                <p class="text-4xl font-bold mt-1">₱{{ number_format($weekRevenue ?? 0, 2) }}</p>
                <p class="text-purple-100 text-xs mt-2">{{ \Carbon\Carbon::now()->startOfWeek()->format('M d') }} - {{ \Carbon\Carbon::now()->endOfWeek()->format('M d') }}</p>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 p-6 rounded-lg shadow-xl text-white">
        <div class="flex items-center justify-between">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <div class="text-right">
                <h3 class="font-bold text-lg">This Month</h3>
                <p class="text-4xl font-bold mt-1">₱{{ number_format($monthRevenue ?? 0, 2) }}</p>
                <p class="text-green-100 text-xs mt-2">{{ \Carbon\Carbon::now()->format('F Y') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Revenue Chart with Tabs -->
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-gray-800 font-bold text-xl mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Revenue Overview
        </h3>
        
        <!-- Tab Buttons -->
        <div class="flex gap-2 mb-4 border-b">
            <button onclick="showChart('daily')" id="dailyTab" class="px-4 py-2 font-semibold text-blue-600 border-b-2 border-blue-600 transition-all">
                Daily
            </button>
            <button onclick="showChart('weekly')" id="weeklyTab" class="px-4 py-2 font-semibold text-gray-600 hover:text-blue-600 transition-all">
                Weekly
            </button>
            <button onclick="showChart('monthly')" id="monthlyTab" class="px-4 py-2 font-semibold text-gray-600 hover:text-blue-600 transition-all">
                Monthly
            </button>
        </div>

        <div style="height: 300px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Top Selling Products -->
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-gray-800 font-bold text-xl mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
            </svg>
            Top Selling Products
        </h3>
        @if($topProducts && $topProducts->count() > 0)
            <div class="space-y-4">
                @foreach($topProducts as $index => $product)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-green-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 text-white rounded-full flex items-center justify-center font-bold text-lg shadow-lg">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">{{ $product->name }}</p>
                                <p class="text-sm text-gray-600">{{ $product->total_sold }} units sold</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-green-600 text-lg">₱{{ number_format($product->total_revenue, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-gray-500 mt-4">No sales data yet</p>
            </div>
        @endif
    </div>
</div>

<!-- Low Stock Alert -->
@if($lowStockProducts && $lowStockProducts->count() > 0)
<div class="bg-white p-6 rounded-lg shadow-lg mb-6">
    <div class="flex items-center gap-2 mb-6">
        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <h3 class="text-gray-800 font-bold text-xl">Low Stock Alert</h3>
        <span class="ml-auto bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold">{{ $lowStockProducts->count() }} Items</span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50 border-b-2 border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Current Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($lowStockProducts as $product)
                    <tr class="hover:bg-red-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">
                            {{ $product->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-red-600 font-bold text-lg">{{ $product->stock_quantity ?? $product->Quantity_in_Stock }}</span>
                            <span class="text-gray-500 text-sm ml-1">units</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700 font-medium">
                            ₱{{ number_format($product->unit_price ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $qty = $product->stock_quantity ?? $product->Quantity_in_Stock;
                            @endphp
                            @if($qty <= 5)
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 border border-red-200">
                                     CRITICAL
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                    ⚠️ LOW
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
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="flex items-center gap-2 mb-6">
            <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-red-600 font-bold text-xl">Expired Products</h3>
        </div>
        
        @if($expiredProducts && $expiredProducts->count() > 0)
            <div class="space-y-3 max-h-80 overflow-y-auto">
                @foreach($expiredProducts as $product)
                <div class="flex justify-between items-center p-4 bg-red-50 rounded-lg border-l-4 border-red-600 hover:shadow-md transition-shadow">
                    <div>
                        <p class="font-bold text-gray-800">{{ $product->Product_Name }}</p>
                        <p class="text-sm text-gray-600 mt-1">Expired: {{ \Carbon\Carbon::parse($product->expiry_date)->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500 mt-1">Stock: {{ $product->Quantity_in_Stock }} units</p>
                    </div>
                    <span class="text-red-700 font-bold text-sm px-4 py-2 bg-red-200 rounded-lg">EXPIRED</span>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-gray-500 mt-4 font-medium">No expired products</p>
            </div>
        @endif
    </div>

    <!-- Expiring Soon Products -->
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="flex items-center gap-2 mb-6">
            <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-orange-600 font-bold text-xl">Expiring Soon (Within 7 Days)</h3>
        </div>
        
        @if($expiringSoonProducts && $expiringSoonProducts->count() > 0)
            <div class="space-y-3 max-h-80 overflow-y-auto">
                @foreach($expiringSoonProducts as $product)
                <div class="flex justify-between items-center p-4 bg-orange-50 rounded-lg border-l-4 border-orange-600 hover:shadow-md transition-shadow">
                    <div>
                        <p class="font-bold text-gray-800">{{ $product->Product_Name }}</p>
                        <p class="text-sm text-gray-600 mt-1">Expires: {{ \Carbon\Carbon::parse($product->expiry_date)->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500 mt-1">Stock: {{ $product->Quantity_in_Stock }} units</p>
                    </div>
                    <span class="text-orange-700 font-bold text-sm px-4 py-2 bg-orange-200 rounded-lg">
                        {{ $product->days_until_expiry }} {{ $product->days_until_expiry == 1 ? 'day' : 'days' }}
                    </span>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-gray-500 mt-4 font-medium">No products expiring soon</p>
            </div>
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
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    pointBackgroundColor: 'rgb(34, 197, 94)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    borderWidth: 3
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
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                return 'Revenue: ₱' + context.parsed.y.toLocaleString('en-US', {
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
                            },
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: false
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
        const tabs = ['dailyTab', 'weeklyTab', 'monthlyTab'];
        const types = ['daily', 'weekly', 'monthly'];
        
        tabs.forEach((tabId, index) => {
            const tab = document.getElementById(tabId);
            if (types[index] === type) {
                tab.className = 'px-4 py-2 font-semibold text-blue-600 border-b-2 border-blue-600 transition-all';
            } else {
                tab.className = 'px-4 py-2 font-semibold text-gray-600 hover:text-blue-600 transition-all';
            }
        });
    }

    // Initialize chart on page load
    document.addEventListener('DOMContentLoaded', function() {
        createChart('daily');
    });
</script>
@endsection