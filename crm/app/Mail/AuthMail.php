<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AuthMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $loginlink;

    public function __construct($user, $loginLink)
    {
        $this->user = $user;
        $this->loginlink = $loginLink;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Auth Mail',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
