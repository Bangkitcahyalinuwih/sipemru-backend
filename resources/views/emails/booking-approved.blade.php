```blade
@component('mail::message')
    # Peminjaman Ruang Disetujui ✓

    Halo **{{ $booking->user->name }}**,

    Peminjaman ruangan Anda telah **disetujui**.

    <table width="100%" cellpadding="6">
        <tr>
            <td><strong>Ruangan</strong></td>
            <td>{{ $booking->room->code }} — {{ $booking->room->name }}</td>
        </tr>

        <tr>
            <td><strong>Gedung</strong></td>
            <td>{{ $booking->room->building->name }}</td>
        </tr>

        <tr>
            <td><strong>Tanggal</strong></td>
            <td>
                {{ \Carbon\Carbon::parse($booking->booking_date)->isoFormat('dddd, D MMMM Y') }}
            </td>
        </tr>

        <tr>
            <td><strong>Waktu</strong></td>
            <td>
                {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}
                -
                {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} WIB
            </td>
        </tr>

        <tr>
            <td><strong>Keperluan</strong></td>
            <td>{{ $booking->purpose }}</td>
        </tr>
    </table>

    ---

    ### Informasi Penting
    - Harap datang 10 menit sebelum jadwal dimulai.
    - Tunjukkan QR tiket kepada petugas saat verifikasi.
    - QR tiket hanya berlaku satu kali penggunaan.

    @if ($booking->qrTicket)
        ## QR Tiket

        ![QR Ticket]({{ asset('storage/' . $booking->qrTicket->qr_image_path) }})

        @component('mail::button', [
            'url' => asset('storage/' . $booking->qrTicket->qr_image_path),
        ])
            Download QR Ticket
        @endcomponent
    @else
        QR tiket sedang diproses oleh sistem.
    @endif

    Terima kasih,<br>
    **Admin SIMARU**
@endcomponent
```
