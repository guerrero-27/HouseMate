<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'room_number',
        'room_type',
        'description',
        'monthly_rate',
        'capacity',
        'floor_number',
        'status',
        'thumbnail',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(RoomImage::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function statusColor(): string
    {
        return match($this->status) {
            'available'   => 'green',
            'occupied'    => 'red',
            'maintenance' => 'yellow',
            default       => 'gray',
        };
    }
}
