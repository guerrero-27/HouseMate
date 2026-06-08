<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Request\StoreRoomRequest;
use App\Http\Request\UpdateRoomRequest;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $rooms = Room::latest()->paginate(10);
        return view('admin.rooms..index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.rooms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('thumbnail')){
            $data['thumbnail'] = $request->file('thumbnail')
            ->store('rooms/thumbnails', 'public');
        }

        $room = Room::create($data);

        if ($request->hasFile('images')){
            foreach ($request->file('images') as $image){
                $path = $image->store('rooms/gallery', ';public');
                RoomImage::create([
                    'room_id' => $room->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('admin.rooms.index')->with('success', 'Room Created Successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room): View
    {
        $room->load('images');
        return view('admin.rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        $room->load('images');
        return view('admin.room.edit', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomRequest $request, Room $room): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasfile('thumbnail')){
            if ($room->thumbnail){
                Storage::disk('public')->delete($room->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')
            ->store('rooms/thumbnails', 'public');
        }

        $room->update($data);

        if ($request->hasFile('images')){
            foreach ($request->file('images') as $image) {
                $path = $image->store('rooms/gallery', 'public');
                RoomImage::create([
                    'room_id' => $room->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('admin.rooms.index')->with('success', 'Room updated Successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room): RedirectResponse
    {
        if ($room->thumbnail){
            Storage::disk('public')->delete($room->thumbnail);
        }

        foreach ($room->image as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Room Deleted Successfully');
    }
}
