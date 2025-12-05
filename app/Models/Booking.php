<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'tanggal',
        'jam',
        'alamat',
        'tipe',
        'payment_method',
        'catatan',
        'bukti_transfer',
        'status',
        'order_id',
        'price',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {

            $prefix = 'TSY';

            // Tahun 2 digit dan bulan 2 digit
            $year  = date('y'); // misal 25
            $month = date('m'); // misal 12

            // Cari booking terakhir pada bulan yang sama
            $lastBooking = Booking::whereYear('created_at', date('Y'))
                ->whereMonth('created_at', date('m'))
                ->orderBy('id', 'desc')
                ->first();

            if ($lastBooking) {
                // Ambil 2 digit terakhir
                $lastNumber = intval(substr($lastBooking->order_id, -2));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            // Format final: TSY-2512-01
            $booking->order_id = $prefix . '-' . $year . $month . '-' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
        });
    }
}
