@component('mail::message')
# Booking Baru Diterima

Halo Admin,

Ada booking baru yang masuk dengan detail sebagai berikut:

**Nama:** {{ $booking->name }}  
**Email:** {{ $booking->email }}  
**Telepon:** {{ $booking->phone }}  
**Tanggal:** {{ $booking->tanggal }}  
**Jam:** {{ $booking->jam }}  
**Tipe:** {{ $booking->tipe }}  
**Pembayaran:** {{ $booking->payment_method }}  
**Catatan:** {{ $booking->catatan ?? '-' }}

@if($booking->bukti_transfer)
**Bukti Transfer:**
[Lihat Bukti Transfer]({{ asset('storage/' . $booking->bukti_transfer) }})
@endif

Mohon segera diproses.

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent
