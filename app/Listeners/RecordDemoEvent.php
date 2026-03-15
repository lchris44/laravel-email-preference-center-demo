<?php

namespace App\Listeners;

use App\Models\DemoEvent;
use Lchris44\EmailPreferenceCenter\Events\DigestQueued;
use Lchris44\EmailPreferenceCenter\Events\DigestSent;
use Lchris44\EmailPreferenceCenter\Events\PreferenceUpdated;
use Lchris44\EmailPreferenceCenter\Events\UserUnsubscribed;

class RecordDemoEvent
{
    public function handlePreferenceUpdated(PreferenceUpdated $event): void
    {
        DemoEvent::create([
            'event_class' => 'PreferenceUpdated',
            'user_name'   => $event->notifiable->name ?? 'unknown',
            'category'    => $event->category,
            'action'      => $event->action,
            'via'         => $event->via,
        ]);
    }

    public function handleUserUnsubscribed(UserUnsubscribed $event): void
    {
        DemoEvent::create([
            'event_class' => 'UserUnsubscribed',
            'user_name'   => $event->notifiable->name ?? 'unknown',
            'category'    => $event->category,
            'via'         => $event->via,
        ]);
    }

    public function handleDigestQueued(DigestQueued $event): void
    {
        DemoEvent::create([
            'event_class' => 'DigestQueued',
            'user_name'   => $event->notifiable->name ?? 'unknown',
            'category'    => $event->category,
            'frequency'   => $event->frequency,
        ]);
    }

    public function handleDigestSent(DigestSent $event): void
    {
        DemoEvent::create([
            'event_class' => 'DigestSent',
            'user_name'   => $event->notifiable->name ?? 'unknown',
            'category'    => $event->category,
            'frequency'   => $event->frequency,
            'item_count'  => $event->itemCount,
        ]);
    }
}
