@extends('layouts.tenant')

@section('title', 'Payment Details')

@section('content')

<div class="max-w-2xl mx-auto">
    <a href="{{ route('tenant.payments.index') }}" class="text-sm text-gray-500 hover:underline mb-4 block">
        ← Back to My Payments
    </a>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow p-6">

        <div class="flex justify-between items-start mb-6">
            <h1 class="text-xl font-bold text-gray-800">Payment Details</h1>
            @php $c = $payment->statusColor(); @endphp
            <span class="px-3 py-1 rounded-full text-sm font-semibold bg-{{ $c }}-100 text-{{ $c }}-700">
                {{ $payment->statusLabel() }}
            </span>
        </div>

        <div class="space-y-3 text-sm mb-6">
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
                <span class="font-bold text-xl text-red-600">₱{{ number_format($payment->amount, 2) }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Due Date</span>
                <span class="{{ $payment->isOverdue() ? 'text-red-600 font-semibold' : '' }}">
                    {{ $payment->due_date->format('F d, Y') }}
                </span>
            </div>
            @if($payment->paid_date)
            <div class="flex justify-between">
                <span class="text-gray-500">Paid On</span>
                <span class="text-green-600 font-semibold">{{ $payment->paid_date->format('F d, Y') }}</span>
            </div>
            @endif
        </div>

        {{-- Admin returned note --}}
        @if($payment->admin_notes && $payment->isUnpaid())
        <div class="bg-yellow-50 border border-yellow-300 rounded p-3 mb-5">
            <p class="text-xs font-semibold text-yellow-700 mb-1">Admin Note:</p>
            <p class="text-sm text-yellow-800">{{ $payment->admin_notes }}</p>
        </div>
        @endif

        {{-- Current receipt (if uploaded) --}}
        @if($payment->receipt_path && $payment->isPendingVerification())
        <div class="mb-5">
            <p class="text-sm font-medium text-gray-700 mb-2">Your Uploaded Receipt</p>
            @php $ext = pathinfo($payment->receipt_path, PATHINFO_EXTENSION); @endphp
            @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                <img src="{{ asset('storage/' . $payment->receipt_path) }}"
                     class="max-h-48 rounded border" alt="Receipt">
            @else
                <a href="{{ asset('storage/' . $payment->receipt_path) }}" target="_blank"
                   class="text-blue-600 text-sm hover:underline">📄 View Receipt PDF</a>
            @endif
            <p class="text-xs text-gray-400 mt-1">Awaiting admin verification.</p>
        </div>
        @endif

        {{-- Upload / Re-upload Receipt --}}
        @if(!$payment->isPaid() && !$payment->isPendingVerification())
        <form method="POST"
              action="{{ route('tenant.payments.uploadReceipt', $payment) }}"
              enctype="multipart/form-data"
              class="border-t pt-5">
            @csrf

            <p class="text-sm font-semibold text-gray-700 mb-3">
                {{ $payment->receipt_path ? 'Re-upload Receipt' : 'Upload Proof of Payment' }}
            </p>

            <div class="mb-4">
                <label class="block text-xs text-gray-500 mb-1">
                    Receipt (JPG, PNG, or PDF — max 5MB)
                </label>
                <input type="file" name="receipt" accept=".jpg,.jpeg,.png,.pdf"
                       class="w-full text-sm text-gray-600 @error('receipt') border-red-500 @enderror"
                       id="receiptInput">
                @error('receipt')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Image Preview --}}
            <img id="receiptPreview" src="#" alt="Preview"
                 class="max-h-40 rounded mb-3 hidden">

            <button type="submit"
                    class="w-full bg-red-600 text-white py-2.5 rounded-lg font-semibold hover:bg-red-700 text-sm">
                Upload Receipt
            </button>
        </form>
        @endif

    </div>
</div>

@push('scripts')
<script>
    // Preview image before upload
    const input = document.getElementById('receiptInput');
    if (input) {
        input.addEventListener('change', function (e) {
            const file = e.target.files[0];
            const preview = document.getElementById('receiptPreview');
            if (file && file.type.startsWith('image/')) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }
        });
    }
</script>
@endpush

@endsection
