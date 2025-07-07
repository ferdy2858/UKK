<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<div class="relative min-h-screen">

    <!-- Sidebar -->
    <aside id="sidebar"
           class="bg-white w-64 h-full fixed top-0 left-0 z-40 pt-16 shadow-lg transform transition-transform duration-300 -translate-x-full">
        <div class="p-4 text-lg font-bold border-b">My Dashboard</div>
        <nav class="p-4 space-y-2">
            <a href="/"
               class="block px-4 py-2 rounded hover:bg-gray-200 {{ Request::is('/') ? 'bg-gray-200 font-semibold' : '' }}">
                Dashboard
            </a>
            <a href="/buss"
               class="block px-4 py-2 rounded hover:bg-gray-200 {{ Request::is('buss') ? 'bg-gray-200 font-semibold' : '' }}">
                Kontol
            </a>
        </nav>
    </aside>

    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between p-4 bg-white shadow-md">
        <!-- Toggle Button -->
        <button id="toggleBtn" class="text-gray-700">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <form action="/logout" method="POST">
            @csrf
            <button class="bg-red-500 text-white px-3 py-1 rounded">Logout</button>
        </form>
    </header>

    <!-- Main Content -->
    <main id="mainContent" class="transition-all duration-300 pl-4 pr-4 pt-24">
        @yield('content')
    </main>

</div>

<!-- Toggle JS -->
<script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleBtn');
    const mainContent = document.getElementById('mainContent');

    let isSidebarOpen = false;

    const openSidebar = () => {
        sidebar.classList.remove('-translate-x-full');
        mainContent.classList.add('ml-64');
        isSidebarOpen = true;
    };

    const closeSidebar = () => {
        sidebar.classList.add('-translate-x-full');
        mainContent.classList.remove('ml-64');
        isSidebarOpen = false;
    };

    toggleBtn.addEventListener('click', () => {
        isSidebarOpen ? closeSidebar() : openSidebar();
    });

    // Auto open sidebar on page load
    openSidebar();
</script>

</body>
</html>
