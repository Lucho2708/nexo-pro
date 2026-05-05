<?php

namespace App\Notifications;

use App\Models\Transaccion;
use App\Modules\IAM\Models\User;
use App\Models\Unidad;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentRegisteredNotification extends Notification
{
    use Queueable;

    private $transaccion;
    private $user;
    private $unidad;

    /**
     * Create a new notification instance.
     */
    public function __construct(Transaccion $transaccion, User $user, Unidad $unidad)
    {
        $this->transaccion = $transaccion;
        $this->user = $user;
        $this->unidad = $unidad;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Confirmación de Pago - ' . $this->unidad->nombre)
            ->view('emails.payment-registered', [
                'transaccion' => $this->transaccion,
                'user' => $this->user,
                'unidad' => $this->unidad
            ]);
    }
}
