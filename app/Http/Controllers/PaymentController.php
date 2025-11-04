<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function createTransaction(Request $request)
    {
        $booking = Booking::create([
            'name' => $request->name,
            'email' => $request->email,
            'tanggal' => $request->tanggal,
            'jam' => $request->jam,
            'alamat' => $request->alamat,
            'tipe' => $request->tipe,
            'price' => 50000,
            'status' => 'pending',
        ]);

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $booking->id . '-' . time(),
                'gross_amount' => $booking->price,
            ],
            'customer_details' => [
                'first_name' => $booking->name,
                'email' => $booking->email,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        $booking->update(['order_id' => $params['transaction_details']['order_id']]);

        return response()->json([
            'message' => 'Transaksi berhasil dibuat',
            'snap_token' => $snapToken,
        ]);
    }

    // CALLBACK dari Midtrans
    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed === $request->signature_key) {
            $booking = Booking::where('order_id', $request->order_id)->first();

            if ($booking) {
                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    $booking->update(['status' => 'success']);
                    // TODO: kirim email + nota PDF di sini
                } elseif ($request->transaction_status == 'cancel' || $request->transaction_status == 'deny') {
                    $booking->update(['status' => 'failed']);
                }
            }
        }

        return response()->json(['message' => 'Callback processed']);
    }
}
