<?php

namespace App\Traits;

use App\Events\RoomStatusUpdated;
use App\Models\Room;
use App\Services\RoomStatusService;

trait BroadcastsRoomStatus
{
    /**
     * Hitung status terkini ruangan lalu broadcast ke semua browser.
     * Dipanggil setiap kali ada perubahan booking.
     */
    protected function broadcastRoomStatus(?int $roomId): void
    {
        if (!$roomId) return;

        $room = Room::find($roomId);

        if (!$room) return;

        $status = (new RoomStatusService())->getStatus(
            $room,
            today()->toDateString(),
            now()->format('H:i')
        );

        RoomStatusUpdated::dispatch($room, $status);
    }
}
