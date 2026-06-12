<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(): View
    {
        $rooms = Room::available()->latest()->paginate(9);
        return view('tenant.rooms.index', compact('rooms'));
    }

    public function show(Room $room): View
    {
        $room->load('images');
        return view('tenant.rooms.show', compact('room'));
    }
}
