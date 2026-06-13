<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'HouseMate') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen flex">

            {{-- Left branding panel --}}
            <div class="hidden lg:flex lg:w-1/2 bg-gray-900 flex-col justify-between p-12">
                <div>
                    <span class="text-white text-2xl font-bold tracking-tight">HouseMate</span>
                </div>
                <div>
                    <h1 class="text-white text-4xl font-bold leading-snug mb-4">
                        Find your perfect<br>place to stay.
                    </h1>
                    <p class="text-gray-400 text-lg">
                        Manage rooms, reservations, and tenants — all in one place.
                    </p>
                </div>
                <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} HouseMate. All rights reserved.</p>
            </div>

            {{-- Right form panel --}}
            <div class="flex-1 flex flex-col justify-center items-center px-6 py-12 bg-white">
                <div class="w-full max-w-md">
                    <div class="lg:hidden mb-8 text-center">
                        <span class="text-indigo-600 text-2xl font-bold">🏠 HouseMate</span>
                    </div>
                    {{ $slot }}
                </div>
            </div>

        </div>
    </body>
</html>
