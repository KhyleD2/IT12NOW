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
            border: 4px dashed #34D399;
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

        .sidebar { overflow-y: auto; }
        header { position: sticky; top: 0; z-index: 10; }

        /* Improved Dropdown Styles */
        .dropdown {
            position: relative;
        }

        .dropdown-arrow {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dropdown:hover .dropdown-arrow {
            transform: rotate(180deg);
        }

        .dropdown-content {
            max-height: 0;
            overflow: hidden;
            padding-left: 2.5rem;
            opacity: 0;
            transform: translateY(-10px);
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                        opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                        transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dropdown:hover .dropdown-content {
            max-height: 300px;
            opacity: 1;
            transform: translateY(0);
            margin-top: 0.5rem;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 0.5rem 0.75rem;
            margin-bottom: 0.25rem;
            color: white;
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.9rem;
            opacity: 0;
            transform: translateX(-10px);
        }

        .dropdown:hover .dropdown-item {
            opacity: 1;
            transform: translateX(0);
        }

        .dropdown:hover .dropdown-item:nth-child(1) {
            transition-delay: 0.05s;
        }

        .dropdown:hover .dropdown-item:nth-child(2) {
            transition-delay: 0.1s;
        }

        .dropdown:hover .dropdown-item:nth-child(3) {
            transition-delay: 0.15s;
        }

        .dropdown-item:hover {
            background-color: #059669;
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body class="bg-gray-100">

<div class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="sidebar bg-green-700 text-white w-72 flex-shrink-0 hidden md:flex flex-col p-6 rounded-r-3xl shadow-lg">
        
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
            <!-- Dashboard - Visible to ALL roles -->
            <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 hover:scale-105 transition shadow-sm {{ request()->routeIs('dashboard') ? 'bg-green-600' : '' }}">
                <span class="material-icons mr-2">dashboard</span> Dashboard
            </a>
            
            @if(in_array(auth()->user()->role, ['admin', 'manager']))
                <!-- ADMIN & MANAGER - Inventory Section -->
                
                <!-- Inventory with Dropdown -->
                <div class="dropdown">
                    <a href="{{ route('inventory.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 hover:scale-105 transition shadow-sm {{ request()->routeIs('inventory.*') && !request()->routeIs('inventory.reports') ? 'bg-green-600' : '' }}">
                        <span class="material-icons mr-2">inventory_2</span> Inventory
                        <span class="material-icons ml-auto text-sm dropdown-arrow">expand_more</span>
                    </a>
                    <!-- Dropdown Content -->
                    <div class="dropdown-content">
                        <a href="{{ route('stockins.index') }}" class="dropdown-item {{ request()->routeIs('stockins.*') ? 'bg-green-600' : '' }}">
                            <span class="material-icons mr-2" style="font-size: 18px;">add_circle</span> Stock-In
                        </a>
                        <a href="{{ route('inventory.reports') }}" class="dropdown-item {{ request()->routeIs('inventory.reports') ? 'bg-green-600' : '' }}">
                            <span class="material-icons mr-2" style="font-size: 18px;">assessment</span> 
                            Inventory Reports
                        </a>
                    </div>
                </div>

                <!-- Supplier Transaction Dropdown -->
                <div class="dropdown">
                    <a href="#" class="flex items-center p-3 rounded-lg hover:bg-green-600 hover:scale-105 transition shadow-sm">
                        <span class="material-icons mr-2">local_shipping</span> Supplier Transaction
                        <span class="material-icons ml-auto text-sm dropdown-arrow">expand_more</span>
                    </a>
                    <div class="dropdown-content">
                        <a href="{{ route('suppliers.index') }}" class="dropdown-item {{ request()->routeIs('suppliers.*') ? 'bg-green-600' : '' }}">
                            <span class="material-icons mr-2" style="font-size: 18px;">list_alt</span> All Suppliers
                        </a>
                        <a href="{{ route('supplier.transactions') }}" class="dropdown-item {{ request()->routeIs('supplier.transactions') ? 'bg-green-600' : '' }}">
                            <span class="material-icons mr-2" style="font-size: 18px;">receipt_long</span> Transactions
                        </a>
                    </div>
                </div>
            @endif

            @if(in_array(auth()->user()->role, ['admin', 'cashier']))
                <!-- ADMIN & CASHIER - Sales Section -->
                
                <a href="{{ route('customers.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 hover:scale-105 transition shadow-sm {{ request()->routeIs('customers.*') ? 'bg-green-600' : '' }}">
                    <span class="material-icons mr-2">people</span> Customers
                </a>
                
                <a href="{{ route('sales.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 hover:scale-105 transition shadow-sm {{ request()->routeIs('sales.*') ? 'bg-green-600' : '' }}">
                    <span class="material-icons mr-2">sell</span> Sales
                </a>
            @endif

            @if(auth()->user()->role === 'admin')
                <!-- ADMIN ONLY - User Management Section -->
                
                <!-- Divider -->
                <div class="border-t border-green-600 my-2"></div>
                
                <div class="text-xs text-green-300 px-3 py-2 font-semibold uppercase tracking-wider">
                    User Management
                </div>

                <!-- View All Users -->
                <a href="{{ route('users.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 hover:scale-105 transition shadow-sm {{ request()->routeIs('users.index') ? 'bg-green-600' : '' }}">
                    <span class="material-icons mr-2">manage_accounts</span> All Users
                </a>
                
                <!-- Create Manager with Dropdown -->
                <div class="dropdown">
                    <a href="{{ route('users.create-manager') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 hover:scale-105 transition shadow-sm {{ request()->routeIs('users.create-manager') || request()->routeIs('users.store-manager') ? 'bg-green-600' : '' }}">
                        <span class="material-icons mr-2">admin_panel_settings</span> Create Manager
                        <span class="material-icons ml-auto text-sm dropdown-arrow">expand_more</span>
                    </a>
                    <div class="dropdown-content">
                        <a href="{{ route('users.list-managers') }}" class="dropdown-item {{ request()->routeIs('users.list-managers') ? 'bg-green-600' : '' }}">
                            <span class="material-icons mr-2" style="font-size: 18px;">people</span> 
                            All Managers
                        </a>
                    </div>
                </div>
                
                <!-- Create Cashier with Dropdown -->
                <div class="dropdown">
                    <a href="{{ route('users.create-cashier') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 hover:scale-105 transition shadow-sm {{ request()->routeIs('users.create-cashier') || request()->routeIs('users.store-cashier') ? 'bg-green-600' : '' }}">
                        <span class="material-icons mr-2">person_add</span> Create Cashier
                        <span class="material-icons ml-auto text-sm dropdown-arrow">expand_more</span>
                    </a>
                    <div class="dropdown-content">
                        <a href="{{ route('users.list-cashiers') }}" class="dropdown-item {{ request()->routeIs('users.list-cashiers') ? 'bg-green-600' : '' }}">
                            <span class="material-icons mr-2" style="font-size: 18px;">people</span> 
                            All Cashiers
                        </a>
                    </div>
                </div>

                <!-- Employees -->
                <a href="{{ route('employees.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 hover:scale-105 transition shadow-sm {{ request()->routeIs('employees.index') ? 'bg-green-600' : '' }}">
                    <span class="material-icons mr-2">badge</span> Employees
                </a>
            @endif
        </nav>

        <!-- User Info & Logout Button at Bottom -->
        <div class="mt-auto pt-4 border-t border-green-600">
            <div class="text-xs text-green-200 mb-2 px-2">
                <p class="font-semibold">{{ auth()->user()->fname }} {{ auth()->user()->lname }}</p>
                <p class="text-green-300">{{ ucfirst(auth()->user()->role) }}</p>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center p-2 rounded-lg bg-red-600 hover:bg-red-700 transition shadow-sm text-sm">
                    <span class="material-icons mr-1" style="font-size: 20px;">logout</span> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-auto">

        <!-- Topbar -->
        <header class="bg-white shadow flex items-center justify-end px-6 py-4">
            <div class="text-sm text-gray-600">
                Welcome, <span class="font-semibold">{{ auth()->user()->fname }}</span>
            </div>
        </header>

        <!-- Content Area -->
        <main class="flex-1 p-6 bg-gray-100">
            @yield('content')
        </main>

    </div>
</div>

</body>
</html>
