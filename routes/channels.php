<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;


Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('new-messages.{user_id}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('online-users', function ($users) {
    return $users->toArray();
});