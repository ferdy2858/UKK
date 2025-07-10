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
            <div class="p-4 text-lg font-bold border-b">WareHouse</div>
            <nav class="p-4 space-y-2">
                <!-- Menu Biasa -->
                <a href="/"
                    class="block px-4 py-2 rounded hover:bg-gray-200 {{ Request::is('/') ? 'bg-gray-200 font-semibold' : '' }}">
                    Dashboard
                </a>

                @php
                    $isDropdownMasterActive = Request::is('produk') || Request::is('kategori') || Request::is('supplier');
                    $isDropdownActivityActive = Request::is('penerimaan') || Request::is('pengeluaran');
                @endphp

                <!-- Dropdown Menu: Master -->
                <div class="relative">
                    <button id="dropdownToggle" type="button"
                        class="w-full flex items-center justify-between px-4 py-2 rounded hover:bg-gray-200 focus:outline-none {{ $isDropdownMasterActive ? 'bg-gray-200 font-semibold' : '' }}">
                        <span>Master</span>
                        <svg id="dropdownIcon"
                            class="w-4 h-4 transform transition-transform duration-200 {{ $isDropdownMasterActive ? 'rotate-180' : '' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="dropdownMenu"
                        class="mt-2 ml-4 space-y-1 transition-all duration-300 overflow-hidden {{ $isDropdownMasterActive ? 'max-h-40' : 'max-h-0' }}">
                        <a href="/kategori"
                            class="block px-4 py-2 text-sm rounded hover:bg-gray-100 {{ Request::is('kategori') ? 'bg-gray-200 font-semibold' : '' }}">
                            Kategori
                        </a>
                        <a href="/produk"
                            class="block px-4 py-2 text-sm rounded hover:bg-gray-100 {{ Request::is('produk') ? 'bg-gray-200 font-semibold' : '' }}">
                            Produk
                        </a>
                        <a href="/supplier"
                            class="block px-4 py-2 text-sm rounded hover:bg-gray-100 {{ Request::is('supplier') ? 'bg-gray-200 font-semibold' : '' }}">
                            Supplier
                        </a>
                    </div>
                </div>

                <!-- Dropdown Menu: Activity -->
                <div class="relative">
                    <button id="dropdownActivityToggle" type="button"
                        class="w-full flex items-center justify-between px-4 py-2 rounded hover:bg-gray-200 focus:outline-none {{ $isDropdownActivityActive ? 'bg-gray-200 font-semibold' : '' }}">
                        <span>Activity</span>
                        <svg id="dropdownActivityIcon"
                            class="w-4 h-4 transform transition-transform duration-200 {{ $isDropdownActivityActive ? 'rotate-180' : '' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="dropdownActivityMenu"
                        class="mt-2 ml-4 space-y-1 transition-all duration-300 overflow-hidden {{ $isDropdownActivityActive ? 'max-h-40' : 'max-h-0' }}">
                        <a href="/penerimaan"
                            class="block px-4 py-2 text-sm rounded hover:bg-gray-100 {{ Request::is('penerimaan') ? 'bg-gray-200 font-semibold' : '' }}">
                            Penerimaan
                        </a>
                        <a href="/pengeluaran"
                            class="block px-4 py-2 text-sm rounded hover:bg-gray-100 {{ Request::is('pengeluaran') ? 'bg-gray-200 font-semibold' : '' }}">
                            Pengeluaran
                        </a>
                    </div>
                </div>
            </nav>
        </aside>

        <!-- Header -->
        <header class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between p-4 bg-white shadow-md">
            <button id="toggleBtn" class="text-gray-700">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <form action="/logout" method="POST">
                @csrf
                <button class="bg-red-500 text-white px-3 py-1 rounded flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-9V4m0 1a9 9 0 100 14" />
                    </svg>
                    Logout
                </button>
            </form>
        </header>

        <!-- Main Content -->
        <main id="mainContent" class="transition-all duration-300 pl-4 pr-4 pt-24">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script>
        // Sidebar toggle
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

        // Auto open sidebar on load
        openSidebar();

        // Dropdown toggle for Master
        const dropdownToggle = document.getElementById('dropdownToggle');
        const dropdownMenu = document.getElementById('dropdownMenu');
        const dropdownIcon = document.getElementById('dropdownIcon');
        let dropdownOpen = {{ $isDropdownMasterActive ? 'true' : 'false' }};
        dropdownToggle.addEventListener('click', () => {
            dropdownOpen = !dropdownOpen;
            dropdownMenu.style.maxHeight = dropdownOpen ? dropdownMenu.scrollHeight + "px" : "0px";
            dropdownIcon.classList.toggle('rotate-180');
        });

        // Dropdown toggle for Activity
        const dropdownActivityToggle = document.getElementById('dropdownActivityToggle');
        const dropdownActivityMenu = document.getElementById('dropdownActivityMenu');
        const dropdownActivityIcon = document.getElementById('dropdownActivityIcon');
        let dropdownActivityOpen = {{ $isDropdownActivityActive ? 'true' : 'false' }};
        dropdownActivityToggle.addEventListener('click', () => {
            dropdownActivityOpen = !dropdownActivityOpen;
            dropdownActivityMenu.style.maxHeight = dropdownActivityOpen ? dropdownActivityMenu.scrollHeight + "px" : "0px";
            dropdownActivityIcon.classList.toggle('rotate-180');
        });
    </script>

</body>

</html>