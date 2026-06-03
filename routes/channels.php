<?php

use Illuminate\Support\Facades\Broadcast;

// Channel publik — semua orang bisa subscribe
// tidak perlu login untuk lihat status ruangan
Broadcast::channel('rooms', function () {
    return true;
});
// <?php

// use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });
