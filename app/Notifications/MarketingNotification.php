<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Lchris44\EmailPreferenceCenter\Contracts\HasEmailCategory;

class MarketingNotification extends Notification implements HasEmailCategory
{
    use Queueable;

    public function emailCategory(): string
    {
        return 'marketing';
    }

    public function via(object $notifiable): array
    {
        return ['email-preferences'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('What\'s new this month')
            ->greeting('Hello!')
            ->line('Here is your monthly product update with new features and promotions.')
            ->action('View Updates', url('/'));
    }
}
