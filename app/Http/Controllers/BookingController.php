<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Ambil semua data booking
     */
    public function index()
    {
        $bookings = Booking::orderBy('created_at', 'desc')->get();

        return response()->json([
            'message' => 'Data booking berhasil diambil',
            'data' => $bookings
        ], 200);
    }

    /**
     * Simpan data booking baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email',
            'tanggal'   => 'required|date',
            'jam'       => 'required|string',
            'alamat'    => 'required|string',
            'tipe'      => 'required|in:Perorangan,Instansi',
            'price'     => 'nullable|integer',
            'status'    => 'nullable|string',
            'order_id'  => 'nullable|string',
        ]);

        $booking = Booking::create($validated);

        return response()->json([
            'message' => 'Booking berhasil disimpan!',
            'data' => $booking,
        ], 201);
    }

    /**
     * Menampilkan detail booking berdasarkan ID
     */
    public function show($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json([
                'message' => 'Data booking tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'message' => 'Detail booking berhasil diambil',
            'data' => $booking
        ], 200);
    }

    /**
     * Update data booking
     */
    public function update(Request $request, $id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['message' => 'Data booking tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'name'      => 'sometimes|string|max:255',
            'email'     => 'sometimes|email',
            'tanggal'   => 'sometimes|date',
            'jam'       => 'sometimes|string',
            'alamat'    => 'sometimes|string',
            'tipe'      => 'sometimes|in:Perorangan,Instansi',
            'price'     => 'sometimes|integer',
            'status'    => 'sometimes|string',
            'order_id'  => 'sometimes|string|nullable',
        ]);

        $booking->update($validated);

        return response()->json([
            'message' => 'Data booking berhasil diperbarui',
            'data' => $booking
        ], 200);
    }

    /**
     * Hapus data booking
     */
    public function destroy($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json([
                'message' => 'Data booking tidak ditemukan'
            ], 404);
        }

        $booking->delete();

        return response()->json([
            'message' => 'Data booking berhasil dihapus'
        ], 200);
    }
}
