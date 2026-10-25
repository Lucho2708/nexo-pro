<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (string) $user->id === (string) $id;
});

Broadcast::channel('asamblea.{asambleaId}', function ($user, $asambleaId) {
    // Permitir si el usuario está autenticado (luego podemos refinar si pertenece a la asamblea)
    return Auth::check();
});
