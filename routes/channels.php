<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('asamblea.{asambleaId}', function ($user, $asambleaId) {
    // Permitir si el usuario está autenticado (luego podemos refinar si pertenece a la asamblea)
    return Auth::check();
});
