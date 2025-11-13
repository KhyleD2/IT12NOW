<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CRM FruitStand')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* Sticker animation */
        .sticker {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            border: 4px dashed #34D399; /* Tailwind green-400 */
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            transform: rotate(-10deg);
            animation: bounceSticker 2s infinite alternate;
            background-color: #fff;
        }

        @keyframes bounceSticker {
            0% { transform: rotate(-10deg) translateY(0); }
            100% { transform: rotate(-10deg) translateY(-10px); }
        }

        /* Sidebar scroll */
        .sidebar { overflow-y: auto; }

        /* Optional: sticky topbar shadow */
        header { position: sticky; top: 0; z-index: 10; }
    </style>
</head>
<body class="bg-gray-100">

<div class="flex h-screen overflow-hidden">

    <!-- Sidebar (30%) -->
    <aside class="sidebar bg-green-700 text-white w-72 flex-shrink-0 hidden md:flex flex-col p-6 space-y-6 rounded-r-3xl shadow-lg">
        
        <!-- Sticker Logo Section -->
        <div class="flex flex-col items-center">
            <div class="sticker flex items-center justify-center mb-2">
                <img src="https://images.unsplash.com/photo-1601004890684-d8cbf643f5f2?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=100" 
                     alt="Fruit Sticker" class="w-20 h-20 rounded-full">
            </div>
            <h1 class="text-xl font-bold text-center">CRM FruitStand</h1>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="flex-1 flex flex-col space-y-2 mt-6">
            <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 hover:scale-105 transition shadow-sm">
                <span class="material-icons mr-2">dashboard</span> Dashboard
            </a>
            <a href="{{ route('inventory.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 hover:scale-105 transition shadow-sm">
                <span class="material-icons mr-2">inventory_2</span> Inventory
            </a>
            <a href="{{ route('suppliers.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 hover:scale-105 transition shadow-sm">
                <span class="material-icons mr-2">local_shipping</span> Suppliers
            </a>
            <a href="{{ route('customers.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 hover:scale-105 transition shadow-sm">
                <span class="material-icons mr-2">people</span> Customers
            </a>
            <a href="{{ route('sales.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 hover:scale-105 transition shadow-sm">
                <span class="material-icons mr-2">sell</span> Sales
            </a>
        </nav>
    </aside>

    <!-- Main Content (70%) -->
    <div class="flex-1 flex flex-col overflow-auto">

        <!-- Topbar -->
        <header class="bg-white shadow flex items-center justify-between px-6 py-4">
            <h2 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition">Logout</button>
            </form>
        </header>

        <!-- Content Area -->
        <main class="flex-1 p-6 bg-gray-100">
            @yield('content')
        </main>

    </div>
</div>

</body>
</html>
