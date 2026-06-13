<!DOCTYPE html>
<html lang="en" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HouseMate Admin — @yield('title')</title>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-950 font-sans text-gray-800 dark:text-gray-100 transition-colors duration-200"
      x-data="{ sidebarOpen: false }">

<div class="flex min-h-screen">

    {{-- Mobile overlay --}}
    <div x-show="sidebarOpen"
         x-transition.opacity
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/40 z-20 lg:hidden"></div>

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-30 w-60 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 flex flex-col transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:z-auto">

        <div class="h-16 flex items-center px-6 border-b border-gray-200 dark:border-gray-800 shrink-0">
            <span class="text-lg font-bold text-gray-900 dark:text-white"> HouseMate</span>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-0.5 text-sm overflow-y-auto">
            <p class="px-3 pt-2 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Main</p>

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-md font-medium transition-colors
                      {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.rooms.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-md font-medium transition-colors
                      {{ request()->routeIs('admin.rooms.*') ? 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0h2M5 21H3"/></svg>
                Rooms
            </a>
            <a href="{{ route('admin.reservations.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-md font-medium transition-colors
                      {{ request()->routeIs('admin.reservations.*') ? 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Reservations
            </a>
            <a href="{{ route('admin.payments.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-md font-medium transition-colors
                      {{ request()->routeIs('admin.payments.*') ? 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                Payments
            </a>
        </nav>

        <div class="px-4 py-4 border-t border-gray-200 dark:border-gray-800 shrink-0">
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 truncate">{{ auth()->user()->name }}</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium">Sign out</button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex-1 flex flex-col min-w-0">
        <header class="h-16 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 flex items-center px-4 gap-3 shrink-0">
            {{-- Mobile hamburger --}}
            <button @click="sidebarOpen = true" class="lg:hidden p-1.5 rounded-md text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            <h1 class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex-1">@yield('title')</h1>

            {{-- Dark mode toggle --}}
            <button onclick="toggleDarkMode()"
                    class="p-1.5 rounded-md text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <svg x-show="!$el.closest('html').classList.contains('dark')" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <svg x-show="$el.closest('html').classList.contains('dark')" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                </svg>
            </button>
        </header>

        <main class="flex-1 p-4 lg:p-6 overflow-x-hidden">
            @yield('content')
        </main>
    </div>

</div>

</body>
</html>
