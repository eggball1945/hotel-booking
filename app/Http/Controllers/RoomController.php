<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    /**
     * Display a listing of rooms
     */
    public function index(Request $request)
    {
        $query = Room::with('roomType');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('room_number', 'LIKE', "%{$search}%")
                  ->orWhere('note', 'LIKE', "%{$search}%")
                  ->orWhereHas('roomType', fn($q) => $q->where('name', 'LIKE', "%{$search}%"));
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter room type
        if ($request->filled('room_type_id')) {
            $query->where('room_type_id', $request->room_type_id);
        }

        // Sorting
        $sortField = $request->get('sort_by', 'created_at');
        $sortDir   = $request->get('sort_dir', 'desc');
        if (!in_array($sortField, ['room_number', 'status', 'created_at'])) {
            $sortField = 'created_at';
        }

        $query->orderBy($sortField, $sortDir);

        $rooms = $query->paginate(10)->withQueryString();
        $roomTypes = RoomType::ordered()->get();

        $stats = [
            'total' => Room::count(),
            'available' => Room::where('status', 'available')->count(),
            'booked' => Room::where('status', 'booked')->count(),
            'maintenance' => Room::where('status', 'maintenance')->count(),
        ];

        return view('admin.rooms.index', compact('rooms', 'roomTypes', 'stats'));
    }

    /**
     * Show form to create a new room
     */
    public function create()
    {
        $roomTypes = RoomType::ordered()->get();
        return view('admin.rooms.create', compact('roomTypes'));
    }

    /**
     * Store new room
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_number' => 'required|string|max:20|unique:rooms,room_number',
            'room_type_id' => 'required|exists:room_types,id',
            'status' => 'required|in:available,booked,maintenance',
            'note' => 'nullable|string|max:500',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam pengisian form.');
        }

        $data = $request->only(['room_number', 'room_type_id', 'status', 'note']);

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $filename = time() . '_' . uniqid() . '.' . $request->foto->extension();
            $path = $request->foto->storeAs('rooms', $filename, 'public');
            $data['foto'] = $path;
        }

        Room::create($data);

        return redirect()->route('admin.rooms.index')->with('success', 'Kamar berhasil ditambahkan!');
    }

    /**
     * Show room details
     */
    public function show(Room $room)
    {
        $room->load('roomType');
        return view('admin.rooms.show', compact('room'));
    }

    /**
     * Show form to edit room
     */
    public function edit(Room $room)
    {
        $roomTypes = RoomType::ordered()->get();
        return view('admin.rooms.edit', compact('room', 'roomTypes'));
    }

    /**
     * Update room
     */
    public function update(Request $request, Room $room)
    {
        $validator = Validator::make($request->all(), [
            'room_number' => 'required|string|max:20|unique:rooms,room_number,' . $room->id,
            'room_type_id' => 'required|exists:room_types,id',
            'status' => 'required|in:available,booked,maintenance',
            'note' => 'nullable|string|max:500',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam pengisian form.');
        }

        $data = $request->only(['room_number', 'room_type_id', 'status', 'note']);

        // Upload foto baru dan hapus lama
        if ($request->hasFile('foto')) {
            if ($room->foto && Storage::disk('public')->exists($room->foto)) {
                Storage::disk('public')->delete($room->foto);
            }

            $filename = time() . '_' . uniqid() . '.' . $request->foto->extension();
            $path = $request->foto->storeAs('rooms', $filename, 'public');
            $data['foto'] = $path;
        }

        $room->update($data);

        return redirect()->route('admin.rooms.index')->with('success', 'Kamar berhasil diperbarui!');
    }

    /**
     * Delete room
     */
    public function destroy(Room $room)
    {
        if ($room->status == 'booked') {
            return redirect()->back()->with('error', 'Tidak dapat menghapus kamar yang sedang dipesan!');
        }

        if ($room->foto && Storage::disk('public')->exists($room->foto)) {
            Storage::disk('public')->delete($room->foto);
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Kamar berhasil dihapus!');
    }

    /**
     * Update room status
     */
    public function updateStatus(Request $request, Room $room)
    {
        $request->validate([
            'status' => 'required|in:available,booked,maintenance'
        ]);

        $oldStatus = $room->status;
        $room->status = $request->status;
        $room->save();

        return redirect()->back()->with('success', "Status kamar berhasil diubah dari {$oldStatus} menjadi {$request->status}");
    }

    /**
     * AJAX: check room number availability
     */
    public function checkRoomNumber(Request $request)
    {
        $roomNumber = $request->query('room_number');
        $roomId = $request->query('room_id');

        $query = Room::where('room_number', $roomNumber);
        if ($roomId) {
            $query->where('id', '!=', $roomId);
        }

        $exists = $query->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Nomor kamar sudah digunakan' : 'Nomor kamar tersedia'
        ]);
    }
}
