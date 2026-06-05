<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BoardEase Admin — @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">

    {{-- Top Navigation Bar --}}
    <nav class="bg-red-600 text-white px-6 py-4 flex justify-between items-center shadow">
        <span class="text-xl font-bold tracking-wide">BoardEase Admin</span>
        <div class="flex items-center gap-4">
            <span class="text-sm">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm bg-white text-red-600 px-3 py-1 rounded hover:bg-gray-100">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    {{-- Sidebar + Content --}}
    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside class="w-64 bg-gray-900 text-gray-300 p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-700">
                Dashboard
            </a>
            {{-- More nav links will be added per module --}}
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 p-6">
            @yield('content')
        </main>

    </div>

</body>
</html>
