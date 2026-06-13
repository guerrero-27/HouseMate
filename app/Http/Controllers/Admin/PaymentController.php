<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\VerifyPaymentRequest;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentController extends Controller
{
    // List all payments with optional filters
    public function index(): View
    {
        $status = request('status', 'all');
        $month  = request('month', now()->format('Y-m'));

        $payments = Payment::with(['user', 'reservation.room'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->when($month, fn($q) => $q->where('billing_month', $month))
            ->latest()
            ->paginate(15);

        return view('admin.payments.index', compact('payments', 'status', 'month'));
    }

    // Show the create bill form
    public function create(): View
    {
        // Only fetch tenants who have an approved or active reservation
        $tenants = User::where('role', 'tenant')
            ->whereHas('reservations', fn($q) => $q->whereIn('status', ['approved', 'active']))
            ->get();

        return view('admin.payments.create', compact('tenants'));
    }

    // Get reservations by tenant — called via AJAX in the create form
    public function getReservationsByTenant(): \Illuminate\Http\JsonResponse
    {
        $reservations = Reservation::where('user_id', request('user_id'))
            ->whereIn('status', ['approved', 'active'])
            ->with('room')
            ->get()
            ->map(fn($r) => [
                'id'    => $r->id,
                'label' => "Room {$r->room->room_number} ({$r->room->room_type}) — ₱" . number_format($r->room->monthly_rate, 2) . '/mo',
                'rate'  => $r->room->monthly_rate,
            ]);

        return response()->json($reservations);
    }

    // Store a new billing record
    public function store(StorePaymentRequest $request): RedirectResponse
    {
        // Prevent duplicate billing for same reservation + month + type
        $exists = Payment::where('reservation_id', $request->reservation_id)
            ->where('billing_month', $request->billing_month)
            ->where('payment_type', $request->payment_type)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'billing_month' => 'A bill for this period and type already exists.',
            ])->withInput();
        }

        Payment::create([
            'user_id'        => $request->user_id,
            'reservation_id' => $request->reservation_id,
            'billing_month'  => $request->billing_month,
            'amount'         => $request->amount,
            'payment_type'   => $request->payment_type,
            'due_date'       => $request->due_date,
            'status'         => 'unpaid',
        ]);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Bill created successfully.');
    }

    // Show a single payment detail
    public function show(Payment $payment): View
    {
        $payment->load(['user', 'reservation.room']);
        return view('admin.payments.show', compact('payment'));
    }

    // Verify or return a payment receipt
    public function verify(VerifyPaymentRequest $request, Payment $payment): RedirectResponse
    {
        abort_if(!$payment->isPendingVerification(), 403, 'This payment is not awaiting verification.');

        if ($request->action === 'verify') {
            $payment->update([
                'status'      => 'paid',
                'paid_date'   => now()->toDateString(),
                'verified_at' => now(),
                'admin_notes' => $request->admin_notes,
            ]);

            $message = 'Payment verified and marked as paid.';
        } else {
            // Return to tenant — they need to re-upload
            $payment->update([
                'status'      => 'unpaid',
                'receipt_path'=> null,
                'receipt_uploaded_at' => null,
                'admin_notes' => $request->admin_notes,
            ]);

            $message = 'Receipt returned to tenant for re-upload.';
        }

        return redirect()->route('admin.payments.show', $payment)
            ->with('success', $message);
    }
}
