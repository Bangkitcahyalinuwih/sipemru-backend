@component('mail::message')
# Peminjaman Ruang Disetujui ✓

Halo **{{ $booking->user->name }}**, peminjaman Anda telah **disetujui**.

| | |
|---|---|
| **Ruangan** | {{ $booking->room->code }} — {{ $booking->room->name }} |
| **Gedung** | {{ $booking->room->building->name }} |
| **Tanggal** | {{ \Carbon\Carbon::parse($booking->booking_date)->isoFormat('dddd, D MMMM Y') }} |
| **Waktu** | {{ substr($booking->start_time,0,5) }} – {{ substr($booking->end_time,0,5) }} |
| **Keperluan** | {{ $booking->purpose }} |

@if($booking->qrTicket)
Tunjukkan QR tiket berikut saat verifikasi di lokasi:

@component('mail::button', ['url' => asset('storage/' . $booking->qrTicket->qr_image_path)])
Lihat QR Tiket
@endcomponent
@endif

Salam,
**Admin SIMARU**
@endcomponent