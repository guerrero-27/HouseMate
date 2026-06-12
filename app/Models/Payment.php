<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'reservation_id',
        'billing_month',
        'amount',
        'payment_type',
        'status',
        'receipt_path',
        'due_date',
        'paid_date',
        'admin_notes',
        'receipt_uploaded_at',
        'verified_at',
    ];

    protected function $casts(): array
    {
        return [
            'due_date' => 'date',
            'paid_date' => 'date',
            'receipt_uploaded_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function isUnpaid(): bool
    {
        return $this->status === 'unpaid';
    }

    public function isPendingVerification(): bool
    {
        return $this->status === 'pending_verification';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isOverdue(): bool
    {
        return $this->status === 'overdue' || ($this->isUnpaid() && $this->due_date->isPast());
    }

    public function statusColor(): string
    {
        return match($this->status){
            'unpaid' => 'red',
            'pending_verification' => 'yellow',
            'paid' => 'green',
            'overdue' => 'red',
            default => 'gray',
        };
    }

    public function statusLabel(): string
    {
        return match($this->status){
            'unpaid' => 'Unpaid',
            'pending_verification' => 'For Verification',
            'paid' => 'Paid',
            'overdue' => 'Overdue',
            default => ucfirst($this->status);
        }
    }

    public function billingMonthLabel(): string
    {
        return \Carbon\Carbon::createFromFormat('Y-m', $this->billing_month)->format('F Y');
    }
}
