<?php

namespace App\Mail\Admin;

use App\Models\Copropiedad;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeManagementMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Copropiedad $copropiedad
    ) {}

    public function build()
    {
        return $this->subject("✅ Bienvenido a la administración de {$this->copropiedad->nombre}")
                    ->markdown('emails.admin.welcome_management');
    }
}
