<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterMail;
use App\Models\User;
use App\Notifications\BillingNotification;
use App\Notifications\DigestNotification;
use App\Notifications\MarketingNotification;
use App\Notifications\SecurityAlertNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Lchris44\EmailPreferenceCenter\Events\DigestReadyToSend;
use Lchris44\EmailPreferenceCenter\Models\EmailPreference;
use Lchris44\EmailPreferenceCenter\Support\CategoryRegistry;
use Lchris44\EmailPreferenceCenter\Support\DigestQueue;
use Lchris44\EmailPreferenceCenter\Support\SignedUnsubscribeUrl;

class DemoActionController extends Controller
{
    // ── Notifications ────────────────────────────────────────────────────────

    public function notifyMarketing(Request $request): RedirectResponse
    {
        $user = $this->resolveUser($request);
        $user->notify(new MarketingNotification());
        return back()->with('flash', "Marketing notification sent to {$user->name}.");
    }

    public function notifySecurity(Request $request): RedirectResponse
    {
        $user = $this->resolveUser($request);
        $user->notify(new SecurityAlertNotification());
        return back()->with('flash', "Security alert sent to {$user->name}.");
    }

    public function notifyDigest(Request $request): RedirectResponse
    {
        $user = $this->resolveUser($request);
        $user->notify(new DigestNotification('You have new activity on your account.'));
        return back()->with('flash', "Digest notification sent to {$user->name} (routed by frequency).");
    }

    public function notifyBilling(Request $request): RedirectResponse
    {
        $user = $this->resolveUser($request);
        $user->notify(new BillingNotification());
        return back()->with('flash', "Billing notification sent to {$user->name} (category from config map).");
    }

    // ── Mailable with headers ─────────────────────────────────────────────────

    public function sendNewsletter(Request $request): RedirectResponse
    {
        $user = $this->resolveUser($request);

        if (! $user->prefersEmail('marketing')) {
            return back()->with('flash', "{$user->name} is unsubscribed from marketing — email skipped.");
        }

        Mail::to($user->email)->send(new NewsletterMail($user));
        return back()->with('flash', "Newsletter mailable sent to {$user->name} with List-Unsubscribe headers.");
    }

    // ── DigestQueue direct dispatch ───────────────────────────────────────────

    public function dispatchDigest(Request $request): RedirectResponse
    {
        $user = $this->resolveUser($request);
        DigestQueue::dispatch($user, 'digest', 'demo_item', [
            'title' => 'Demo digest item dispatched at ' . now()->toTimeString(),
            'body'  => 'This item was queued via DigestQueue::dispatch().',
        ]);
        return back()->with('flash', "DigestQueue::dispatch() called for {$user->name}.");
    }

    // ── Preferences ───────────────────────────────────────────────────────────

    public function subscribe(Request $request): RedirectResponse
    {
        $user     = $this->resolveUser($request);
        $category = $request->input('category', 'marketing');
        $user->subscribe($category, 'admin');
        return back()->with('flash', "{$user->name} subscribed to {$category}.");
    }

    public function unsubscribe(Request $request): RedirectResponse
    {
        $user     = $this->resolveUser($request);
        $category = $request->input('category', 'marketing');
        $user->unsubscribe($category, 'admin');
        return back()->with('flash', "{$user->name} unsubscribed from {$category}.");
    }

    public function setFrequency(Request $request): RedirectResponse
    {
        $user      = $this->resolveUser($request);
        $category  = $request->input('category', 'digest');
        $frequency = $request->input('frequency', 'daily');
        $user->setEmailFrequency($category, $frequency);
        return back()->with('flash', "{$user->name} digest frequency set to {$frequency}.");
    }

    // ── Seeder ────────────────────────────────────────────────────────────────

    public function runSeeder(Request $request): RedirectResponse
    {
        $model     = $request->input('model', User::class);
        $registry  = app(CategoryRegistry::class);
        $instances = $model::all();
        $seeded    = 0;

        foreach ($instances as $notifiable) {
            foreach ($registry->all() as $key => $def) {
                $exists = $notifiable->emailPreferences()->forCategory($key)->exists();
                if (! $exists) {
                    $notifiable->emailPreferences()->create([
                        'category'        => $key,
                        'frequency'       => 'instant',
                        'unsubscribed_at' => null,
                    ]);
                    $seeded++;
                }
            }
        }

        return back()->with('flash', "Seeder ran for {$model}: {$seeded} preference row(s) created.");
    }

    // ── Send daily digests ────────────────────────────────────────────────────

    public function sendDailyDigests(): RedirectResponse
    {
        $dispatched = $this->fireDigestEvents('daily');
        return back()->with('flash', "Daily digests sent: {$dispatched} digest(s) dispatched.");
    }

    private function fireDigestEvents(string $frequency): int
    {
        $registry   = app(CategoryRegistry::class);
        $dispatched = 0;

        foreach ($registry->all() as $key => $def) {
            if (! $registry->supportsFrequency($key)) {
                continue;
            }

            EmailPreference::query()
                ->where('category', $key)
                ->where('frequency', $frequency)
                ->whereNull('unsubscribed_at')
                ->each(function (EmailPreference $pref) use ($key, $frequency, &$dispatched) {
                    $notifiable = SignedUnsubscribeUrl::resolveNotifiable(
                        $pref->notifiable_type,
                        $pref->notifiable_id
                    );

                    if (! $notifiable) {
                        return;
                    }

                    event(new DigestReadyToSend($notifiable, $key, $frequency));
                    $dispatched++;
                });
        }

        return $dispatched;
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function resolveUser(Request $request): User
    {
        return User::findOrFail($request->input('user_id', 1));
    }
}
