<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-blue-50 text-gray-800">

    <div class="relative min-h-screen">

        @php
            $prefix = auth()->user()->hasRole('manajer') ? 'manajer.' : '';

            $isDropdownMasterActive =
                $isDropdownMasterActive ??
                Request::is($prefix . 'produk') ||
                Request::is($prefix . 'kategori') ||
                Request::is($prefix . 'supplier');

            $isDropdownActivityActive =
                $isDropdownActivityActive ??
                Request::is($prefix . 'penerimaan') || Request::is($prefix . 'pengeluaran');
        @endphp

        <!-- Sidebar -->
        <aside id="sidebar"
            class="bg-blue-600 w-64 h-full fixed top-0 left-0 z-40 pt-16 shadow-lg transform transition-transform duration-300 -translate-x-full text-white">
            <div class="px-6 py-4 text-2xl font-bold border-b border-blue-400 flex items-center gap-2">
                <i class="fa-solid fa-building"></i>
                <span>WareHouse</span>
            </div>

            <nav class="px-4 py-6 space-y-6 text-sm">
                <!-- Dashboard -->
                <div>
                    <a href="{{ route($prefix . 'dashboard') }}"
                        class="block px-4 py-2 rounded-md transition
                            {{ Request::routeIs($prefix . 'dashboard') 
                                ? 'bg-blue-500 text-white font-semibold' 
                                : 'hover:bg-blue-500 hover:text-white' }}">
                        <i class="fa-solid fa-chart-line mr-2"></i> Dashboard
                    </a>
                </div>

                <!-- Master Menu -->
                <div>
                    <button id="dropdownToggle" type="button"
                        class="w-full flex items-center justify-between px-4 py-2 rounded-md transition 
                            {{ $isDropdownMasterActive ? 'bg-blue-500 font-semibold text-white' : 'hover:bg-blue-500 hover:text-white' }}">
                        <span><i class="fa-solid fa-database mr-2"></i> Master</span>
                        <svg id="dropdownIcon"
                            class="w-4 h-4 transform transition-transform duration-200 {{ $isDropdownMasterActive ? 'rotate-180' : '' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="dropdownMenu"
                        class="ml-4 mt-2 space-y-1 transition-all duration-300 overflow-hidden {{ $isDropdownMasterActive ? 'max-h-40' : 'max-h-0' }}">
                        <a href="{{ route($prefix . 'kategori.index') }}"
                            class="block px-4 py-2 rounded 
                                {{ Request::routeIs($prefix . 'kategori.index') 
                                    ? 'bg-blue-500 text-white font-semibold' 
                                    : 'hover:bg-blue-500 hover:text-white' }}">
                            Kategori
                        </a>
                        <a href="{{ route($prefix . 'produk.index') }}"
                            class="block px-4 py-2 rounded 
                                {{ Request::routeIs($prefix . 'produk.index') 
                                    ? 'bg-blue-500 text-white font-semibold' 
                                    : 'hover:bg-blue-500 hover:text-white' }}">
                            Produk
                        </a>
                        <a href="{{ route($prefix . 'supplier.index') }}"
                            class="block px-4 py-2 rounded 
                                {{ Request::routeIs($prefix . 'supplier.index') 
                                    ? 'bg-blue-500 text-white font-semibold' 
                                    : 'hover:bg-blue-500 hover:text-white' }}">
                            Supplier
                        </a>
                    </div>
                </div>

                <!-- Activity Menu -->
                <div>
                    <button id="dropdownActivityToggle" type="button"
                        class="w-full flex items-center justify-between px-4 py-2 rounded-md transition 
                            {{ $isDropdownActivityActive ? 'bg-blue-500 font-semibold text-white' : 'hover:bg-blue-500 hover:text-white' }}">
                        <span><i class="fa-solid fa-clipboard-list mr-2"></i> Activity</span>
                        <svg id="dropdownActivityIcon"
                            class="w-4 h-4 transform transition-transform duration-200 {{ $isDropdownActivityActive ? 'rotate-180' : '' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="dropdownActivityMenu"
                        class="ml-4 mt-2 space-y-1 transition-all duration-300 overflow-hidden {{ $isDropdownActivityActive ? 'max-h-40' : 'max-h-0' }}">
                        <a href="{{ route($prefix . 'penerimaan.index') }}"
                            class="block px-4 py-2 rounded 
                                {{ Request::routeIs($prefix . 'penerimaan.index') 
                                    ? 'bg-blue-500 text-white font-semibold' 
                                    : 'hover:bg-blue-500 hover:text-white' }}">
                            Penerimaan
                        </a>
                        <a href="{{ route($prefix . 'pengeluaran.index') }}"
                            class="block px-4 py-2 rounded 
                                {{ Request::routeIs($prefix . 'pengeluaran.index') 
                                    ? 'bg-blue-500 text-white font-semibold' 
                                    : 'hover:bg-blue-500 hover:text-white' }}">
                            Pengeluaran
                        </a>
                    </div>
                </div>

                <!-- Laporan -->
                <a href="#"
                    class="flex items-center gap-2 px-4 py-2 rounded transition 
                        {{ Request::routeIs($prefix . 'laporan') 
                            ? 'bg-blue-500 text-white font-semibold' 
                            : 'hover:bg-blue-500 hover:text-white' }}">
                    <i class="fa-solid fa-envelope"></i>
                    <span>Laporan</span>
                </a>
            </nav>
        </aside>

        <!-- Header -->
        <header id="mainHeader"
            class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between p-4 bg-blue-700 shadow-md transition-all ml-0 text-white">
            <button id="toggleBtn">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div class="ml-auto flex items-center gap-6">
                <div id="datetime" class="text-sm font-medium"></div>

                <!-- Dropdown Profile -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 hover:text-blue-300">
                        <svg class="w-6 h-6 rounded-full bg-blue-300 p-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A10 10 0 0112 2a10 10 0 016.879 15.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-sm font-semibold">{{ Auth::user()->name }}
                            ({{ Auth::user()->getRoleNames()->first() }})
                        </span>
                    </button>

                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 mt-2 w-32 bg-white border border-gray-200 rounded shadow-md z-50">
                        <form action="/logout" method="POST">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i><span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main -->
        <main id="mainContent" class="transition-all duration-300 pl-4 pr-4 pt-24">
            @yield('content')
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleBtn');
        const mainContent = document.getElementById('mainContent');
        const mainHeader = document.getElementById('mainHeader');
        let isSidebarOpen = false;

        const openSidebar = () => {
            sidebar.classList.remove('-translate-x-full');
            mainContent.classList.add('ml-64');
            mainHeader.classList.add('ml-64');
            isSidebarOpen = true;
        };

        const closeSidebar = () => {
            sidebar.classList.add('-translate-x-full');
            mainContent.classList.remove('ml-64');
            mainHeader.classList.remove('ml-64');
            isSidebarOpen = false;
        };

        toggleBtn.addEventListener('click', () => {
            isSidebarOpen ? closeSidebar() : openSidebar();
        });

        openSidebar();

        // Dropdown master
        const dropdownToggle = document.getElementById('dropdownToggle');
        const dropdownMenu = document.getElementById('dropdownMenu');
        const dropdownIcon = document.getElementById('dropdownIcon');
        let dropdownOpen = {{ $isDropdownMasterActive ? 'true' : 'false' }};
        dropdownToggle.addEventListener('click', () => {
            dropdownOpen = !dropdownOpen;
            dropdownMenu.style.maxHeight = dropdownOpen ? dropdownMenu.scrollHeight + "px" : "0px";
            dropdownIcon.classList.toggle('rotate-180');
        });

        // Dropdown activity
        const dropdownActivityToggle = document.getElementById('dropdownActivityToggle');
        const dropdownActivityMenu = document.getElementById('dropdownActivityMenu');
        const dropdownActivityIcon = document.getElementById('dropdownActivityIcon');
        let dropdownActivityOpen = {{ $isDropdownActivityActive ? 'true' : 'false' }};
        dropdownActivityToggle.addEventListener('click', () => {
            dropdownActivityOpen = !dropdownActivityOpen;
            dropdownActivityMenu.style.maxHeight = dropdownActivityOpen ? dropdownActivityMenu.scrollHeight + "px" : "0px";
            dropdownActivityIcon.classList.toggle('rotate-180');
        });

        function updateDateTime() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' };
            const date = now.toLocaleDateString('id-ID', options);
            const time = now.toLocaleTimeString('id-ID');
            document.getElementById('datetime').textContent = `${date} - ${time}`;
        }

        setInterval(updateDateTime, 1000);
        updateDateTime();
    </script>
</body>

</html>