@extends('layouts.admin')

@section('title', 'Create Bill')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Create Payment Bill</h1>
        <a href="{{ route('admin.payments.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:underline">← Back</a>
    </div>

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg p-6">
        <form method="POST" action="{{ route('admin.payments.store') }}" id="paymentForm">
            @csrf

            {{-- Select Tenant --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tenant *</label>
                <select name="user_id" id="tenantSelect"
                        class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg px-3 py-2 text-sm @error('user_id') border-red-500 @enderror">
                    <option value="">— Select Tenant —</option>
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}" {{ old('user_id') == $tenant->id ? 'selected' : '' }}>
                            {{ $tenant->name }} ({{ $tenant->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Select Reservation --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reservation / Room *</label>
                <select name="reservation_id" id="reservationSelect"
                        class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg px-3 py-2 text-sm @error('reservation_id') border-red-500 @enderror">
                    <option value="">— Select a tenant first —</option>
                </select>
                <p id="reservationHint" class="text-xs text-gray-400 dark:text-gray-500 mt-1 hidden">No active reservations found for this tenant.</p>
                @error('reservation_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Payment Type --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Type *</label>
                    <select name="payment_type"
                            class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg px-3 py-2 text-sm @error('payment_type') border-red-500 @enderror">
                        <option value="rent"        {{ old('payment_type') === 'rent'        ? 'selected' : '' }}>Monthly Rent</option>
                        <option value="electricity" {{ old('payment_type') === 'electricity' ? 'selected' : '' }}>Electricity</option>
                        <option value="water"       {{ old('payment_type') === 'water'       ? 'selected' : '' }}>Water</option>
                        <option value="other"       {{ old('payment_type') === 'other'       ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                {{-- Billing Month --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Billing Month *</label>
                    <input type="month" name="billing_month"
                           value="{{ old('billing_month', now()->format('Y-m')) }}"
                           class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg px-3 py-2 text-sm @error('billing_month') border-red-500 @enderror">
                    @error('billing_month')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Amount --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount (₱) *</label>
                    <input type="number" name="amount" id="amountField"
                           value="{{ old('amount') }}"
                           class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg px-3 py-2 text-sm @error('amount') border-red-500 @enderror"
                           step="0.01" min="1">
                    @error('amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Due Date --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Due Date *</label>
                    <input type="date" name="due_date"
                           value="{{ old('due_date') }}"
                           class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg px-3 py-2 text-sm @error('due_date') border-red-500 @enderror">
                    @error('due_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                    Create Bill
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const tenantSelect      = document.getElementById('tenantSelect');
    const reservationSelect = document.getElementById('reservationSelect');
    const amountField       = document.getElementById('amountField');
    const reservationHint   = document.getElementById('reservationHint');

    tenantSelect.addEventListener('change', function () {
        const userId = this.value;

        reservationSelect.innerHTML = '<option value="">Loading...</option>';
        reservationSelect.disabled = true;
        reservationHint.classList.add('hidden');
        amountField.value = '';

        if (!userId) {
            reservationSelect.innerHTML = '<option value="">— Select a tenant first —</option>';
            reservationSelect.disabled = false;
            return;
        }

        fetch(`{{ route('admin.payments.reservationsByTenant') }}?user_id=${userId}`)
            .then(res => res.json())
            .then(data => {
                reservationSelect.innerHTML = '';
                reservationSelect.disabled = false;

                if (data.length === 0) {
                    reservationSelect.innerHTML = '<option value="">— No active reservations —</option>';
                    reservationHint.classList.remove('hidden');
                    return;
                }

                reservationSelect.innerHTML = '<option value="">— Select a room —</option>';
                data.forEach(r => {
                    const opt = document.createElement('option');
                    opt.value = r.id;
                    opt.dataset.rate = r.rate;
                    opt.textContent = r.label;
                    reservationSelect.appendChild(opt);
                });

                // Auto-select if only one reservation
                if (data.length === 1) {
                    reservationSelect.value = data[0].id;
                    reservationSelect.dispatchEvent(new Event('change'));
                }
            });
    });

    reservationSelect.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        const paymentType = document.querySelector('[name="payment_type"]').value;
        if (selected.dataset.rate && paymentType === 'rent') {
            amountField.value = selected.dataset.rate;
        }
    });

    // Also auto-fill when payment type changes after room is selected
    document.querySelector('[name="payment_type"]').addEventListener('change', function () {
        const selected = reservationSelect.options[reservationSelect.selectedIndex];
        if (selected && selected.dataset.rate && this.value === 'rent') {
            amountField.value = selected.dataset.rate;
        } else if (this.value !== 'rent') {
            amountField.value = '';
        }
    });
</script>
@endpush

@endsection
