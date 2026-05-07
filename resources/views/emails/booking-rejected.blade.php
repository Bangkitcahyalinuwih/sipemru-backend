@component('mail::message')
# Peminjaman Ruang Ditolak

Halo **{{ $booking->user->name }}**, maaf peminjaman Anda **ditolak**.

| | |
|---|---|
| **Ruangan** | {{ $booking->room->code }} — {{ $booking->room->name }} |
| **Tanggal** | {{ \Carbon\Carbon::parse($booking->booking_date)->isoFormat('dddd, D MMMM Y') }} |
| **Waktu** | {{ substr($booking->start_time,0,5) }} – {{ substr($booking->end_time,0,5) }} |

**Alasan penolakan:**
> {{ $booking->admin_notes }}

Anda dapat mengajukan peminjaman di waktu lain melalui SIMARU.

Salam,
**Admin SIMARU**
@endcomponent