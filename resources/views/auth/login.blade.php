<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CRM FruitStand</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Floating label effect */
        input:focus + label,
        input:not(:placeholder-shown) + label {
            transform: translateY(-1.75rem) scale(0.85);
            color: #059669; /* green-600 */
        }

        /* Glass effect for login form */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Smooth hover shadow */
        .shadow-hover:hover {
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        /* Input transition */
        input {
            transition: all 0.3s ease-in-out;
        }

        /* Sticker effect */
        .sticker {
            width: 120px;
            height: 120px;
            background: url('https://images.unsplash.com/photo-1601004890684-d8cbf643f5f2?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w5NTYxfDB8MXxzZWFyY2h8MXx8cG9tZWxvfGVufDB8fHx8MTY5OTI5OTkyOA&ixlib=rb-4.0.3&q=80&w=400') no-repeat center/cover;
            border-radius: 50%;
            border: 5px dashed #fff;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            transform: rotate(-10deg);
            animation: bounceSticker 2s infinite alternate;
        }

        @keyframes bounceSticker {
            0% { transform: rotate(-10deg) translateY(0); }
            100% { transform: rotate(-10deg) translateY(-10px); }
        }
    </style>
</head>
<body class="bg-gradient-to-r from-green-100 to-green-200 h-screen flex items-center justify-center">

<div class="flex flex-col md:flex-row w-full max-w-6xl shadow-2xl rounded-xl overflow-hidden">

    <!-- Left Panel: Branding & Sticker -->
    <div class="md:w-1/2 bg-gradient-to-tr from-green-600 to-green-700 text-white flex flex-col items-center justify-center p-10 relative">
        <div class="absolute inset-0 bg-green-800 opacity-80"></div>
        <div class="relative z-10 flex flex-col items-center text-center space-y-6">
            <div class="sticker"></div>
            <h1 class="text-4xl md:text-5xl font-extrabold mb-2">CRM FruitStand</h1>
            <p class="text-lg md:text-xl font-light max-w-xs">Effortlessly manage inventory, sales, and customers</p>
        </div>
    </div>

    <!-- Right Panel: Login Form -->
    <div class="md:w-1/2 flex items-center justify-center p-10 bg-white relative">
        <div class="w-full max-w-md glass p-8 shadow-hover">
            <h2 class="text-3xl font-bold text-gray-700 mb-6 text-center">Welcome Back</h2>

            @if($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-center font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Email Field -->
                <div class="relative">
                    <input type="email" name="email" placeholder=" " required
                           class="peer w-full px-4 py-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-green-600 focus:outline-none transition bg-white/80"
                    >
                    <label class="absolute left-4 top-3 text-gray-400 pointer-events-none transition-all duration-200">Email</label>
                </div>

                <!-- Password Field -->
                <div class="relative">
                    <input type="password" name="password" placeholder=" " required
                           class="peer w-full px-4 py-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-green-600 focus:outline-none transition bg-white/80"
                    >
                    <label class="absolute left-4 top-3 text-gray-400 pointer-events-none transition-all duration-200">Password</label>
                </div>

                <!-- Login Button -->
                <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold text-lg transition duration-300 shadow-lg hover:shadow-xl">
                    Login
                </button>
            </form>

            <p class="text-center text-gray-500 mt-6 text-sm">
                &copy; {{ date('Y') }} CRM FruitStand. All rights reserved.
            </p>
        </div>
    </div>
</div>

</body>
</html>
