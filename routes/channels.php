<?php

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

Broadcast::channel('user.{username}', function (\App\User $user, $username) {
    return $user->username === $username;
});

Broadcast::channel('conversation.{username1}.{username2}',
    function (\App\User $user, $username1, $username2) {
        return $user->username === $username1 || $user->username === $username2;
    });