<?php

namespace App\Notifications;

use App\Models\Copropiedad;
use App\Modules\IAM\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeAdminNotification extends Notification
{
    use Queueable;

    private $user;
    private $copropiedad;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, Copropiedad $copropiedad)
    {
        $this->user = $user;
        $this->copropiedad = $copropiedad;
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
            ->subject('¡Bienvenido a NEXO-PRO - ' . $this->copropiedad->nombre . '!')
            ->view('emails.welcome-admin', [
                'user' => $this->user,
                'copropiedad' => $this->copropiedad
            ]);
    }
}
