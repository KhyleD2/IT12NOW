@extends('layouts.app')

@section('title', 'Inventory Reports')

@section('content')
<div class="container mx-auto px-4 py-6">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-lg shadow-lg p-6 mb-6">
        <h1 class="text-3xl font-bold text-white flex items-center">
            <span class="material-icons mr-3 text-4xl">assessment</span>
            Inventory Reports
        </h1>
        <p class="text-green-100 mt-2">Simple analytics for your fruit stand business</p>
    </div>

    <!-- Date Filter -->
    <form method="GET" action="{{ route('inventory.reports') }}" class="bg-white rounded-lg shadow p-4 mb-6 flex flex-wrap items-center gap-4">
        <div class="flex items-center gap-2">
            <label class="text-gray-700 font-medium">From:</label>
            <input type="date" name="start_date" class="border border-gray-300 rounded px-3 py-2" value="{{ $startDate }}">
        </div>
        <div class="flex items-center gap-2">
            <label class="text-gray-700 font-medium">To:</label>
            <input type="date" name="end_date" class="border border-gray-300 rounded px-3 py-2" value="{{ $endDate }}">
        </div>
        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 flex items-center gap-2">
            <span class="material-icons text-sm">refresh</span>
            Update Report
        </button>
    </form>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        
        <!-- Total Products -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Total Products</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalProducts }}</p>
                </div>
                <div class="bg-blue-100 p-4 rounded-lg">
                    <span class="material-icons text-blue-600 text-3xl">inventory_2</span>
                </div>
            </div>
        </div>

        <!-- Total Stock Value -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Stock Value</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">₱{{ number_format($totalStockValue, 2) }}</p>
                </div>
                <div class="bg-green-100 p-4 rounded-lg">
                    <span class="material-icons text-green-600 text-3xl">attach_money</span>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Low Stock Items</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $lowStockCount }}</p>
                </div>
                <div class="bg-red-100 p-4 rounded-lg">
                    <span class="material-icons text-red-600 text-3xl">warning</span>
                </div>
            </div>
        </div>

        <!-- Total Sales -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Total Sales</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">₱{{ number_format($totalSales, 2) }}</p>
                </div>
                <div class="bg-purple-100 p-4 rounded-lg">
                    <span class="material-icons text-purple-600 text-3xl">trending_up</span>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Stock Levels Chart -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <span class="material-icons mr-2 text-green-600">inventory_2</span>
            Current Stock Levels by Product
        </h2>
        <div style="height: 400px; position: relative;">
            <canvas id="stockLevelsChart"></canvas>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        
        <!-- Low Stock Products Table -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-red-600 p-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <span class="material-icons mr-2">warning</span>
                    Low Stock Alert
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Product</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700">Stock</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700">Category</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($lowStockProducts as $product)
                        <tr class="hover:bg-red-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $product->Product_Name }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">
                                    {{ $product->Quantity_in_Stock }} pcs
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $product->Category }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-gray-500">
                                <span class="material-icons text-4xl text-green-400">check_circle</span>
                                <p class="mt-2">All products have sufficient stock!</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Stock by Category -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-green-600 p-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <span class="material-icons mr-2">category</span>
                    Stock Summary by Category
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Category</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700">Products</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700">Total Qty</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700">Total Value</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($stockByCategory as $category => $products)
                        @php
                            $totalQty = $products->sum('Quantity_in_Stock');
                            $totalValue = $products->sum('total_value');
                        @endphp
                        <tr class="hover:bg-green-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $category }}</td>
                            <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $products->count() }}</td>
                            <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $totalQty }} pcs</td>
                            <td class="px-4 py-3 text-right text-sm font-semibold text-gray-800">₱{{ number_format($totalValue, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
console.log('=== STARTING CHART INITIALIZATION ===');
console.log('Chart.js version:', Chart.version);

// Wait for page to fully load
window.addEventListener('load', function() {
    console.log('Page loaded, initializing charts...');
    
    try {
        // ========== STOCK LEVELS CHART ==========
        console.log('--- Stock Levels Chart ---');
        
        const rawStockData = @json($stockByCategory);
        console.log('Raw stock data:', rawStockData);
        
        // Flatten the data
        let allProducts = [];
        for (let category in rawStockData) {
            if (rawStockData.hasOwnProperty(category)) {
                const products = rawStockData[category];
                if (Array.isArray(products)) {
                    allProducts = allProducts.concat(products);
                }
            }
        }
        
        console.log('Flattened products:', allProducts);
        console.log('Total products:', allProducts.length);
        
        // Debug: Check what fields each product has
        if (allProducts.length > 0) {
            console.log('First product fields:', Object.keys(allProducts[0]));
            console.log('Sample product:', allProducts[0]);
        }
        
        const stockCanvas = document.getElementById('stockLevelsChart');
        console.log('Stock canvas found:', !!stockCanvas);
        
        if (stockCanvas && allProducts.length > 0) {
            // Create labels with variety if available, otherwise just product name
            const productLabels = allProducts.map(p => {
                console.log('Processing product:', p.Product_Name, 'variety:', p.variety);
                if (p.variety && p.variety !== 'N/A' && p.variety !== null && p.variety.trim() !== '') {
                    return p.Product_Name + ' (' + p.variety + ')';
                }
                return p.Product_Name;
            });
            const stockQuantities = allProducts.map(p => Number(p.Quantity_in_Stock));
            
            console.log('Product labels:', productLabels);
            console.log('Stock quantities:', stockQuantities);
            
            const barColors = stockQuantities.map(qty => {
                if (qty <= 5) return '#ef4444';
                if (qty <= 20) return '#f59e0b';
                return '#10b981';
            });
            
            new Chart(stockCanvas, {
                type: 'bar',
                data: {
                    labels: productLabels,
                    datasets: [{
                        label: 'Stock Quantity',
                        data: stockQuantities,
                        backgroundColor: barColors,
                        borderColor: barColors,
                        borderWidth: 2,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const qty = context.parsed.y;
                                    let status = qty <= 5 ? ' (CRITICAL)' : qty <= 20 ? ' (LOW)' : ' (GOOD)';
                                    return 'Stock: ' + qty + ' units' + status;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value + ' units';
                                }
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    }
                }
            });
            console.log('✓ Stock chart created successfully!');
        } else {
            console.error('✗ Cannot create stock chart:', {
                canvasFound: !!stockCanvas,
                productsCount: allProducts.length
            });
        }
        
        console.log('=== CHART INITIALIZATION COMPLETE ===');
        
    } catch (error) {
        console.error('!!! ERROR DURING CHART CREATION !!!');
        console.error('Error message:', error.message);
        console.error('Error stack:', error.stack);
    }
});
</script>
@endsection