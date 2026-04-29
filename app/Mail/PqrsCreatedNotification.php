<?php

namespace App\Mail;

use App\Models\Pqrs;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PqrsCreatedNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Pqrs $pqrs
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $priorityText = strtoupper($this->pqrs->prioridad);
        $conjunto = $this->pqrs->unidad->copropiedad->nombre;
        
        return new Envelope(
            subject: "[{$priorityText}] Nueva PQRS Radicada - {$conjunto}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.pqrs.created',
        );
    }
}
