<!DOCTYPE html>
<html>
<head>
  <style>
    body { font-family: sans-serif; }
    .nota { border: 1px solid #ccc; padding: 20px; border-radius: 10px; }
  </style>
</head>
<body>
  <div class="nota">
    <h2>Nota Booking Melukat</h2>
    <p><b>Nama:</b> {{ $booking->name }}</p>
    <p><b>Email:</b> {{ $booking->email }}</p>
    <p><b>Tanggal:</b> {{ $booking->tanggal }}</p>
    <p><b>Jam:</b> {{ $booking->jam }}</p>
    <p><b>Tipe:</b> {{ $booking->tipe }}</p>
    <p><b>Harga:</b> Rp{{ number_format($booking->price, 0, ',', '.') }}</p>
    <p><b>Status:</b> LUNAS ✅</p>
  </div>
</body>
</html>
