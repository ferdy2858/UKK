<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-tr from-yellow-50 to-blue-100 flex items-center justify-center min-h-screen">

    <form method="POST" action="/login" class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-sm text-center">
        @csrf

        <!-- Icon -->
        <div class="flex justify-center mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
        </div>

        <h2 class="text-xl font-bold mb-4">Login</h2>

        <!-- Error -->
        @if ($errors->any())
            <div class="text-red-500 text-sm mb-3">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Username -->
        <div class="relative mb-4">
            <input type="text" name="username" id="username" required
                   class="w-full px-4 py-2 pl-10 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-400"
                   placeholder="Username">
            <span class="absolute left-3 top-2.5 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5.121 17.804A12.093 12.093 0 0112 15c2.5 0 4.847.735 6.879 1.996M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </span>
        </div>

        <!-- Password -->
        <div class="relative mb-4">
            <input type="password" name="password" id="password" required
                   class="w-full px-4 py-2 pl-10 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-400"
                   placeholder="Password">
            <span class="absolute left-3 top-2.5 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 11c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
            </span>
        </div>

        <!-- Button -->
        <button type="submit"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-full transition duration-200">
            LOGIN
        </button>
    </form>
</body>
</html>