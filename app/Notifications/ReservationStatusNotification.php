<?php

namespace App\Notifications;

use App\Models\Reserva;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Reserva $reserva
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $status = ucfirst($this->reserva->estado);
        $zona = $this->reserva->zona->nombre;
        $fecha = $this->reserva->fecha->format('d/m/Y');
        $hora = $this->reserva->hora_inicio . ' - ' . $this->reserva->hora_fin;

        return (new MailMessage)
            ->subject("Actualización de tu reserva: {$status}")
            ->line("Tu solicitud de reserva para {$zona} ha sido: **{$status}**.")
            ->line("Detalles de la reserva:")
            ->line("- **Fecha:** {$fecha}")
            ->line("- **Horario:** {$hora}")
            ->action('Ver mis reservas', url(route('reservas.index')))
            ->line('Gracias por usar NEXO-PRO.');
    }

    public function toArray($notifiable): array
    {
        return [
            'reserva_id' => $this->reserva->id,
            'zona' => $this->reserva->zona->nombre,
            'estado' => $this->reserva->estado,
            'fecha' => $this->reserva->fecha->toDateString(),
            'message' => "Tu reserva para {$this->reserva->zona->nombre} ha sido {$this->reserva->estado}."
        ];
    }
}
