<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <form method="POST" action="/login" class="bg-white p-6 rounded shadow-md w-full max-w-sm">
        @csrf
        <h2 class="text-xl font-bold mb-4">Login</h2>

        @if ($errors->any())
            <div class="text-red-500 text-sm mb-3">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="mb-4">
            <label for="username" class="block text-sm font-semibold">Username</label>
            <input type="text" name="username" id="username" required class="w-full p-2 border rounded mt-1">
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-semibold">Password</label>
            <input type="password" name="password" id="password" required class="w-full p-2 border rounded mt-1">
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">
            Login
        </button>
    </form>

</body>
</html>