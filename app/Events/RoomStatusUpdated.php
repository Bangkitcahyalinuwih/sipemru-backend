<?php

namespace App\Events;

use App\Models\Room;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class RoomStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        public Room $room,
        public string $status
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('rooms'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'room.status.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'room_id'        => $this->room->id,
            'code'           => $this->room->code,
            'name'           => $this->room->name,
            'current_status' => $this->status,
        ];
    }
}
