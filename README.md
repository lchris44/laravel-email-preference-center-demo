# Laravel Email Preference Center — Demo App

A minimal Laravel application demonstrating every feature of the
[laravel-email-preference-center](https://packagist.org/packages/lchris44/laravel-email-preference-center) package.

## What it shows

| Feature | Where |
|---|---|
| `'email-preferences'` notification channel | `SecurityAlertNotification`, `MarketingNotification`, `DigestNotification`, `BillingNotification` |
| Category via PHP attribute `#[EmailCategory]` | `SecurityAlertNotification`, `DigestNotification` |
| Category via interface `HasEmailCategory` | `MarketingNotification` |
| Category via config map | `BillingNotification` → `config/email-preferences.php` |
| `BelongsToCategory` mailable + `List-Unsubscribe` headers | `NewsletterMail` |
| `DigestQueue::dispatch()` — `instant` / `daily` / `weekly` routing | Dashboard "Dispatch digest item" button |
| `email-preferences:send-digests daily` command | Dashboard "Send daily digests now" button |
| `email-preferences:seed` command | Dashboard "Seed" button |
| Preference center UI (signed URL, no login) | Per-user "Preference Center" link |
| `PreferenceUpdated`, `UserUnsubscribed`, `DigestQueued`, `DigestSent` events | Live Event Feed section |
| GDPR consent log | Per-user card |

## Setup (5 steps)

```bash
git clone <this-repo> laravel-email-preference-center-demo
cd laravel-email-preference-center-demo

composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

## Pre-seeded users

| User | marketing | digest frequency |
|------|-----------|-----------------|
| Alice | `subscribed` | `instant` |
| Bob | `unsubscribed` | `daily` |
| Carol | `subscribed` | `weekly` |

Select a user in the dropdown then click any action. The Event Feed at the bottom refreshes on page load to show which package events fired.

Mail uses the `log` driver — check `storage/logs/laravel.log` to inspect outgoing emails and `List-Unsubscribe` headers.

## Requirements

- PHP 8.2+
- Laravel 12 (pulled automatically via Composer)
