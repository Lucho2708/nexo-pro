<?php

namespace App\Listeners;

use App\Events\PaymentRegistered;
use App\Notifications\PaymentRegisteredNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPaymentNotificationListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(PaymentRegistered $event): void
    {
        $transaccion = $event->transaccion;
        $unidad = $transaccion->unidad;

        if (!$unidad) return;

        // Notify all users associated with this unit (Owners/Residents)
        $users = $unidad->users;

        foreach ($users as $user) {
            $user->notify(new PaymentRegisteredNotification($transaccion, $user, $unidad));
        }
    }
}
