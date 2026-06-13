@extends('layouts.admin')

@section('title', 'Payment Detail')

@section('content')

<div class="max-w-2xl mx-auto">
    <a href="{{ route('admin.payments.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:underline mb-4 block">
        ← Back to Payments
    </a>

    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-800 dark:text-green-300 px-4 py-3 rounded mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg p-6">
        <div class="flex justify-between items-start mb-6">
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">Payment Detail</h1>
            @php $c = $payment->statusColor(); @endphp
            <span class="px-3 py-1 rounded-full text-sm font-semibold
                bg-{{ $c }}-100 text-{{ $c }}-700 dark:bg-{{ $c }}-900/40 dark:text-{{ $c }}-400">
                {{ $payment->statusLabel() }}
            </span>
        </div>

        <div class="space-y-3 text-sm mb-6">
            <div class="flex justify-between border-b border-gray-100 dark:border-gray-800 pb-2">
                <span class="text-gray-500 dark:text-gray-400">Tenant</span>
                <span class="font-semibold text-gray-900 dark:text-white">{{ $payment->user->name }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-100 dark:border-gray-800 pb-2">
                <span class="text-gray-500 dark:text-gray-400">Room</span>
                <span class="text-gray-700 dark:text-gray-300">Room {{ $payment->reservation->room->room_number }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-100 dark:border-gray-800 pb-2">
                <span class="text-gray-500 dark:text-gray-400">Billing Period</span>
                <span class="font-semibold text-gray-900 dark:text-white">{{ $payment->billingMonthLabel() }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-100 dark:border-gray-800 pb-2">
                <span class="text-gray-500 dark:text-gray-400">Type</span>
                <span class="capitalize text-gray-700 dark:text-gray-300">{{ $payment->payment_type }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-100 dark:border-gray-800 pb-2">
                <span class="text-gray-500 dark:text-gray-400">Amount Due</span>
                <span class="font-bold text-lg text-gray-900 dark:text-white">₱{{ number_format($payment->amount, 2) }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-100 dark:border-gray-800 pb-2">
                <span class="text-gray-500 dark:text-gray-400">Due Date</span>
                <span class="{{ $payment->isOverdue() ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                    {{ $payment->due_date->format('F d, Y') }}
                </span>
            </div>
            @if($payment->paid_date)
            <div class="flex justify-between border-b border-gray-100 dark:border-gray-800 pb-2">
                <span class="text-gray-500 dark:text-gray-400">Paid On</span>
                <span class="text-green-600 dark:text-green-400 font-semibold">{{ $payment->paid_date->format('F d, Y') }}</span>
            </div>
            @endif
            @if($payment->admin_notes)
            <div class="border-b border-gray-100 dark:border-gray-800 pb-2">
                <span class="text-gray-500 dark:text-gray-400 block mb-1">Admin Notes</span>
                <p class="text-gray-700 dark:text-gray-300 text-sm">{{ $payment->admin_notes }}</p>
            </div>
            @endif
        </div>

        {{-- Receipt Preview --}}
        @if($payment->receipt_path)
        <div class="mb-6">
            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Uploaded Receipt</p>
            @php $ext = pathinfo($payment->receipt_path, PATHINFO_EXTENSION); @endphp
            @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                <img src="{{ asset('storage/' . $payment->receipt_path) }}"
                     class="max-h-64 rounded border border-gray-200 dark:border-gray-700" alt="Receipt">
            @else
                <a href="{{ asset('storage/' . $payment->receipt_path) }}"
                   target="_blank"
                   class="inline-flex items-center gap-1 text-indigo-600 dark:text-indigo-400 hover:underline text-sm">
                    📄 View PDF Receipt
                </a>
            @endif
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                Uploaded: {{ $payment->receipt_uploaded_at?->format('M d, Y h:i A') }}
            </p>
        </div>
        @endif

        @if($payment->isPendingVerification())
        <div class="border-t border-gray-200 dark:border-gray-700 pt-5 space-y-3">
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Review Receipt</p>

            <form method="POST" action="{{ route('admin.payments.verify', $payment) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="action" value="verify">
                <div class="mb-3">
                    <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Admin Notes (optional)</label>
                    <input type="text" name="admin_notes"
                           class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg px-3 py-1.5 text-sm"
                           placeholder="e.g. Payment confirmed via GCash">
                </div>
                <button type="submit"
                        onclick="return confirm('Mark this payment as PAID?')"
                        class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg font-semibold text-sm">
                    ✓ Mark as Paid
                </button>
            </form>

            <form method="POST" action="{{ route('admin.payments.verify', $payment) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="action" value="return">
                <div class="mb-3">
                    <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Reason for Return *</label>
                    <input type="text" name="admin_notes"
                           class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg px-3 py-1.5 text-sm @error('admin_notes') border-red-500 @enderror"
                           placeholder="e.g. Receipt is blurry, please re-upload">
                    @error('admin_notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                        onclick="return confirm('Return this receipt to the tenant?')"
                        class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2.5 rounded-lg font-semibold text-sm">
                    ↩ Return Receipt
                </button>
            </form>
        </div>
        @endif

    </div>
</div>

@endsection
