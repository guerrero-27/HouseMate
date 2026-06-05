@extends('layouts.tenant')

@section('title', 'My Dashboard')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-6">Hello, {{ auth()->user()->name }} 👋</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-red-600">
        <p class="text-sm text-gray-500">Rental Status</p>
        <p class="text-xl font-bold text-gray-800">No Active Lease</p>
    </div>

    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-yellow-500">
        <p class="text-sm text-gray-500">Next Due Date</p>
        <p class="text-xl font-bold text-gray-800">—</p>
    </div>

    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-500">
        <p class="text-sm text-gray-500">Payment Status</p>
        <p class="text-xl font-bold text-gray-800">No Payments</p>
    </div>

</div>

@endsection
