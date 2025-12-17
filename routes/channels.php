<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('employee-import.{importId}', function ($user, $importId) {
    // Allow authenticated users to listen to their import progress
    return $user !== null;
});

Broadcast::channel('employee-export.{exportId}', function ($user, $exportId) {
    // Allow authenticated users to listen to their export progress
    return $user !== null;
});