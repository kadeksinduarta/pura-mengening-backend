@component('mail::message')
# Terima kasih, {{ $booking->name }} 🎉

Pembayaran Anda telah kami terima.

**Detail Booking:**
- Tanggal: {{ $booking->tanggal }}
- Jam: {{ $booking->jam }}
- Tipe: {{ $booking->tipe }}
- Harga: Rp{{ number_format($booking->price, 0, ',', '.') }}

PDF Nota terlampir di email ini.

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent
