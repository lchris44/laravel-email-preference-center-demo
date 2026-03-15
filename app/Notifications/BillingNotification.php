<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// Category declared via config map in config/email-preferences.php
class BillingNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['email-preferences'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment Receipt')
            ->line('Your payment of $29.00 was processed successfully.')
            ->line('Thank you for your subscription!');
    }
}
