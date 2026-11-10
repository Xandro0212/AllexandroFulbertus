<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendaftarkan channel event broadcast yang didukung
| aplikasi Anda. Callback otorisasi yang diberikan digunakan untuk
| memeriksa apakah pengguna yang terautentikasi dapat mendengarkan channel.
|
*/

// Channel privat per pengguna — untuk notifikasi & pembaruan status personal
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
