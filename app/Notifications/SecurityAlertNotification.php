<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Lchris44\EmailPreferenceCenter\Attributes\EmailCategory;

#[EmailCategory('security')]
class SecurityAlertNotification extends Notification
{
    use Queueable;

    public function __construct(public string $message = 'A new login was detected from an unknown device.') {}

    public function via(object $notifiable): array
    {
        return ['email-preferences'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Security Alert')
            ->greeting('Security Notice')
            ->line($this->message)
            ->line('If this was you, no action is needed.');
    }
}
