@extends('layouts.admin')

@section('title', 'Payment Detail')

@section('content')

<div class="max-w-2xl mx-auto">
    <a href="{{ route('admin.payments.index') }}" class="text-sm text-gray-500 hover:underline mb-4 block">
        ← Back to Payments
    </a>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex justify-between items-start mb-6">
            <h1 class="text-xl font-bold text-gray-800">Payment Detail</h1>
            @php $c = $payment->statusColor(); @endphp
            <span class="px-3 py-1 rounded-full text-sm font-semibold bg-{{ $c }}-100 text-{{ $c }}-700">
                {{ $payment->statusLabel() }}
            </span>
        </div>

        <div class="space-y-3 text-sm mb-6">
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Tenant</span>
                <span class="font-semibold">{{ $payment->user->name }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Room</span>
                <span>Room {{ $payment->reservation->room->room_number }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Billing Period</span>
                <span class="font-semibold">{{ $payment->billingMonthLabel() }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Type</span>
                <span class="capitalize">{{ $payment->payment_type }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Amount Due</span>
                <span class="font-bold text-lg">₱{{ number_format($payment->amount, 2) }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Due Date</span>
                <span class="{{ $payment->isOverdue() ? 'text-red-600 font-semibold' : '' }}">
                    {{ $payment->due_date->format('F d, Y') }}
                </span>
            </div>
            @if($payment->paid_date)
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Paid On</span>
                <span class="text-green-600 font-semibold">{{ $payment->paid_date->format('F d, Y') }}</span>
            </div>
            @endif
            @if($payment->admin_notes)
            <div class="border-b pb-2">
                <span class="text-gray-500 block mb-1">Admin Notes</span>
                <p class="text-gray-700 text-sm">{{ $payment->admin_notes }}</p>
            </div>
            @endif
        </div>

        {{-- Receipt Preview --}}
        @if($payment->receipt_path)
        <div class="mb-6">
            <p class="text-sm font-medium text-gray-700 mb-2">Uploaded Receipt</p>
            @php $ext = pathinfo($payment->receipt_path, PATHINFO_EXTENSION); @endphp
            @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                <img src="{{ asset('storage/' . $payment->receipt_path) }}"
                     class="max-h-64 rounded border" alt="Receipt">
            @else
                <a href="{{ asset('storage/' . $payment->receipt_path) }}"
                   target="_blank"
                   class="inline-flex items-center gap-1 text-blue-600 hover:underline text-sm">
                    📄 View PDF Receipt
                </a>
            @endif
            <p class="text-xs text-gray-400 mt-1">
                Uploaded: {{ $payment->receipt_uploaded_at?->format('M d, Y h:i A') }}
            </p>
        </div>
        @endif

        {{-- Verify / Return Actions --}}
        @if($payment->isPendingVerification())
        <div class="border-t pt-5 space-y-3">
            <p class="text-sm font-semibold text-gray-700">Review Receipt</p>

            {{-- Verify --}}
            <form method="POST" action="{{ route('admin.payments.verify', $payment) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="action" value="verify">
                <div class="mb-3">
                    <label class="block text-xs text-gray-500 mb-1">Admin Notes (optional)</label>
                    <input type="text" name="admin_notes"
                           class="w-full border rounded-lg px-3 py-1.5 text-sm"
                           placeholder="e.g. Payment confirmed via GCash">
                </div>
                <button type="submit"
                        onclick="return confirm('Mark this payment as PAID?')"
                        class="w-full bg-green-600 text-white py-2.5 rounded-lg font-semibold hover:bg-green-700 text-sm">
                    ✓ Mark as Paid
                </button>
            </form>

            {{-- Return --}}
            <form method="POST" action="{{ route('admin.payments.verify', $payment) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="action" value="return">
                <div class="mb-3">
                    <label class="block text-xs text-gray-500 mb-1">Reason for Return *</label>
                    <input type="text" name="admin_notes"
                           class="w-full border rounded-lg px-3 py-1.5 text-sm @error('admin_notes') border-red-500 @enderror"
                           placeholder="e.g. Receipt is blurry, please re-upload">
                    @error('admin_notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                        onclick="return confirm('Return this receipt to the tenant?')"
                        class="w-full bg-yellow-500 text-white py-2.5 rounded-lg font-semibold hover:bg-yellow-600 text-sm">
                    ↩ Return Receipt
                </button>
            </form>
        </div>
        @endif

    </div>
</div>

@endsection
