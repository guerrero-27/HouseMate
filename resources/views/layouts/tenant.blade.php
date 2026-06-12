<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BoardEase — @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">

    <nav class="bg-red-600 text-white px-6 py-4 flex justify-between items-center shadow">
        <span class="text-xl font-bold">BoardEase</span>
        <div class="flex items-center gap-4">
            <a href="{{ route('tenant.dashboard') }}" class="text-sm hover:underline">Dashboard</a>
            <a href="{{ route('tenant.reservations.index') }}" class="text-sm hover:underline">My Reservations</a>
            <a href="{{ route('tenant.payments.index') }}" class="text-sm hover:underline">My Payments</a>


            <span class="text-sm">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm bg-white text-red-600 px-3 py-1 rounded">Logout</button>
            </form>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto p-6">
        @yield('content')
    </main>

</body>
</html>
