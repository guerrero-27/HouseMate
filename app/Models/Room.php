<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'room_number',
        'room_type',
        'description',
        'montly_rate',
        'capacity',
        'floor_number',
        'status',
        'thumbnail',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(RoomImage::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function statusColor(): string
    {
        return match($this->status){
            'vailable' => 'green',
            'occupied' => 'red',
            'maintenance' => 'yellow',
            default => 'gray',
        };
    }
}
