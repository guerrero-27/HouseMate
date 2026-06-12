<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'room_id',
        'move_in_date',
        'notes',
        'status',
        'rejection_reason',
        'approved_at',
        'rejected_at',
    ];

    protected function casts(): array
    {
        return [
            'move_in_date' => 'date',
            'approved_at'  => 'datetime',
            'rejected_at'  => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function statusColor(): string
    {
        return match($this->status){
            'pending' => 'yellow',
            'approved' => 'green',
            'active' => 'blue',
            'rejected' => 'red',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }

    public function statusLabel(): string
    {
        return match($this->status){
            'pending' => 'Pending Review',
            'approved' => 'Approved',
            'active' => 'Active',
            'rejected' => 'Rejected',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status),
        };
    }
}
