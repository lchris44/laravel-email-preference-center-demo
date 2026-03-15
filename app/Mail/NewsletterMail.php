<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Lchris44\EmailPreferenceCenter\Traits\BelongsToCategory;

class NewsletterMail extends Mailable
{
    use Queueable, SerializesModels, BelongsToCategory;

    public string $category = 'marketing';

    public function __construct(public User $user)
    {
        $this->withUnsubscribeHeaders($user);
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Your Monthly Newsletter');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.newsletter');
    }
}
