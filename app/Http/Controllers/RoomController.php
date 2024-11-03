<?php
namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::latest()->get();
        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:rooms',
            'floor' => 'required|string|max:255',
            'building' => 'required|string|max:255',
            'capacity' => 'nullable|integer',
            'responsible_person' => 'nullable|string|max:255'
        ]);

        Room::create($request->all());
        return redirect()->route('rooms.index')
            ->with('success', 'Room created successfully.');
    }

    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:rooms,name,' . $room->id,
            'floor' => 'required|string|max:255',
            'building' => 'required|string|max:255',
            'capacity' => 'nullable|integer',
            'responsible_person' => 'nullable|string|max:255'
        ]);

        $room->update($request->all());
        return redirect()->route('rooms.index')
            ->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        try {
            $room->delete();
            return redirect()->route('rooms.index')
                ->with('success', 'Room deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Check if the error code is for foreign key constraint violation
            if ($e->getCode() == 23000) {
                return redirect()->route('rooms.index')
                    ->with('error', 'Ruangan ini Dipake di Daftar barang dan tidak bisa Dihapus.');
            }
            // Handle other exceptions if necessary
            return redirect()->route('rooms.index')
                ->with('error', 'An error occurred while trying to delete the room.');
        }
    }
}