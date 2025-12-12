<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingPaidMail;
use App\Mail\NewBookingAdminMail;

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
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'tanggal' => 'required|date',
            'jam' => 'nullable',
            'alamat' => 'required|string',
            'tipe' => 'required|string',
            'payment_method' => 'required|string',
            'catatan' => 'nullable|string',
            'bukti_transfer' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // ✅ SIMPAN FILE & PATH
        if ($request->hasFile('bukti_transfer')) {
            $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');
            $validated['bukti_transfer'] = $path; // <--- WAJIB ADA INI
        }

        // ✅ SIMPAN DATA KE DB
        $booking = Booking::create($validated);

        // 📧 KIRIM EMAIL KE USER (Nota & Bukti)
        try {
            Mail::to($booking->email)->send(new BookingPaidMail($booking));
        } catch (\Exception $e) {
            // Log error jika email gagal, tapi jangan hentikan proses
            \Illuminate\Support\Facades\Log::error('Gagal kirim email ke user: ' . $e->getMessage());
        }

        // 📧 KIRIM EMAIL KE ADMIN (Notifikasi)
        try {
            Mail::to('ikadeksinduarta@gmail.com')->send(new NewBookingAdminMail($booking));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal kirim email ke admin: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Booking berhasil'
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
            'name'           => 'sometimes|string|max:255',
            'email'          => 'sometimes|email',
            'tanggal'        => 'sometimes|date',
            'jam'            => 'sometimes|string',
            'alamat'         => 'sometimes|string',
            'tipe'           => 'sometimes|in:Perorangan,Instansi',
            'payment_method' => 'sometimes|in:Cash,Transfer',
            'bukti_transfer' => 'sometimes|string|nullable',
            'status'         => 'sometimes|in:pending,confirmed,cancelled',
            'order_id'       => 'sometimes|string|nullable',
            'price'          => 'sometimes|integer|min:0',
        ]);

        $booking->update($validated);

        return response()->json([
            'message' => 'Data booking berhasil diperbarui',
            'data'    => $booking
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
