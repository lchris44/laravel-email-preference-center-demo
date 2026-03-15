<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Lchris44\EmailPreferenceCenter\Attributes\EmailCategory;

#[EmailCategory('digest')]
class DigestNotification extends Notification
{
    use Queueable;

    public function __construct(public string $title = 'New activity in your account') {}

    public function via(object $notifiable): array
    {
        return ['email-preferences'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Activity Digest')
            ->line($this->title)
            ->line('Check your dashboard for more details.');
    }
}
