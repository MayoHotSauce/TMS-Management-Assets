<?php
namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Asset;
use Illuminate\Http\Request;
use App\Services\ActivityLogger;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view rooms', ['only' => ['index', 'show']]);
        $this->middleware('permission:create room', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit room', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete room', ['only' => ['destroy']]);
        $this->middleware('permission:view room assets', ['only' => ['show']]);
    }

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

        $room = Room::create($request->all());

        ActivityLogger::log(
            'create',
            'room',
            'Created new room: ' . $room->name,
            null,
            $room->toArray()
        );

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

        $oldValues = $room->toArray();
        $room->update($request->all());

        $changes = [];
        if ($oldValues['name'] !== $room->name) {
            $changes[] = "name from '{$oldValues['name']}' to '{$room->name}'";
        }
        if ($oldValues['floor'] !== $room->floor) {
            $changes[] = "floor from '{$oldValues['floor']}' to '{$room->floor}'";
        }
        if ($oldValues['building'] !== $room->building) {
            $changes[] = "building from '{$oldValues['building']}' to '{$room->building}'";
        }
        if ($oldValues['capacity'] !== $room->capacity) {
            $changes[] = "capacity from '{$oldValues['capacity']}' to '{$room->capacity}'";
        }
        if ($oldValues['responsible_person'] !== $room->responsible_person) {
            $changes[] = "responsible person from '{$oldValues['responsible_person']}' to '{$room->responsible_person}'";
        }

        ActivityLogger::log(
            'update',
            'room',
            'Updated room: ' . implode(', ', $changes),
            $oldValues,
            $room->toArray()
        );

        return redirect()->route('rooms.index')
            ->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        $roomName = $room->name;
        $oldValues = $room->toArray();
        
        $room->delete();

        ActivityLogger::log(
            'delete',
            'room',
            'Deleted room: ' . $roomName,
            $oldValues,
            null
        );

        return redirect()->route('rooms.index')
            ->with('success', 'Room deleted successfully.');
    }

    public function show(Room $room)
    {
        $assets = Asset::where('room_id', $room->id)
            ->with(['category'])
            ->get();
        
        return view('rooms.show', compact('room', 'assets'));
    }
}