@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-6">Welcome, {{ auth()->user()->name }} 👋</h1>

{{-- Stats Cards Row --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-red-600">
        <p class="text-sm text-gray-500">Total Rooms</p>
        <p class="text-3xl font-bold text-gray-800">0</p>
    </div>

    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-500">
        <p class="text-sm text-gray-500">Available Rooms</p>
        <p class="text-3xl font-bold text-gray-800">0</p>
    </div>

    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-yellow-500">
        <p class="text-sm text-gray-500">Pending Reservations</p>
        <p class="text-3xl font-bold text-gray-800">0</p>
    </div>

    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-blue-500">
        <p class="text-sm text-gray-500">Total Tenants</p>
        <p class="text-3xl font-bold text-gray-800">0</p>
    </div>

</div>

@endsection
