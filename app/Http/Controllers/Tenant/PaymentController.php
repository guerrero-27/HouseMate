<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadReceiptRequest;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PaymentController extends Controller
{
    // Show all payments for the logged-in tenant
    public function index(): View
    {
        $payments = auth()->user()
            ->payments()
            ->latest()
            ->paginate(10);

        return view('tenant.payments.index', compact('payments'));
    }

    // View a single payment
    public function show(Payment $payment): View
    {
        // Security: tenant can only view their own payments
        abort_if($payment->user_id !== auth()->id(), 403);

        return view('tenant.payments.show', compact('payment'));
    }

    // Upload a payment receipt
    public function uploadReceipt(UploadReceiptRequest $request, Payment $payment): RedirectResponse
    {
        // Security: tenant can only upload to their own payment
        abort_if($payment->user_id !== auth()->id(), 403);

        // Only allow upload if unpaid (not already verified)
        abort_if($payment->isPaid(), 403, 'This payment has already been verified.');

        // Delete old receipt if re-uploading
        if ($payment->receipt_path) {
            Storage::disk('public')->delete($payment->receipt_path);
        }

        $path = $request->file('receipt')
            ->store('payments/receipts', 'public');

        $payment->update([
            'receipt_path'        => $path,
            'status'              => 'pending_verification',
            'receipt_uploaded_at' => now(),
            'admin_notes'         => null, // clear previous admin note
        ]);

        return redirect()->route('tenant.payments.show', $payment)
            ->with('success', 'Receipt uploaded successfully. Awaiting admin verification.');
    }
}
