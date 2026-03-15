<?php

namespace App\Http\Controllers;

use App\Models\DemoEvent;
use App\Models\User;
use Illuminate\Http\Request;
use Lchris44\EmailPreferenceCenter\Models\PendingDigestItem;
use Lchris44\EmailPreferenceCenter\Support\SignedUnsubscribeUrl;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all()->map(function (User $user) {
            $categories = config('email-preferences.categories', []);
            $prefs = [];
            foreach ($categories as $key => $config) {
                $subscribed = $user->prefersEmail($key);
                $frequency  = $user->emailFrequency($key);
                $prefs[$key] = [
                    'label'      => $config['label'],
                    'required'   => $config['required'] ?? false,
                    'subscribed' => $subscribed,
                    'frequency'  => $frequency,
                    'has_freq'   => isset($config['frequency']),
                    'center_url' => SignedUnsubscribeUrl::generateForCenter($user),
                ];
            }

            return [
                'id'           => $user->id,
                'name'         => $user->name,
                'email'        => $user->email,
                'prefs'        => $prefs,
                'pending_items' => PendingDigestItem::where('notifiable_type', User::class)
                    ->where('notifiable_id', $user->id)
                    ->count(),
                'center_url'   => SignedUnsubscribeUrl::generateForCenter($user),
                'consent_log'  => $user->emailPreferenceLogs()
                    ->latest()
                    ->take(5)
                    ->get(['category', 'action', 'via', 'created_at']),
            ];
        });

        $demoEvents = DemoEvent::latest()->take(30)->get();
        $mailLog    = $this->parseMailLog();

        return view('dashboard', compact('users', 'demoEvents', 'mailLog'));
    }

    private function parseMailLog(): array
    {
        $logPath = storage_path('logs/mail.log');

        if (! file_exists($logPath)) {
            return [];
        }

        $content = file_get_contents($logPath);

        // Split into individual log entries on lines starting with [YYYY-MM-DD
        $entries = preg_split('/^(?=\[\d{4}-\d{2}-\d{2})/m', $content, -1, PREG_SPLIT_NO_EMPTY);

        $emails = [];

        foreach (array_reverse($entries) as $entry) {
            if (! str_contains($entry, 'Subject:')) {
                continue;
            }

            // Timestamp is inside the first [ ]
            $timestamp = '';
            if (preg_match('/^\[([^\]]+)\]/', $entry, $tm)) {
                $timestamp = $tm[1];
            }

            // The From: header is on the same line as the log prefix
            // e.g. "[...] local.DEBUG: From: Name <email>"
            $from    = '';
            if (preg_match('/local\.DEBUG:\s*From:\s*(.+)/i', $entry, $m)) {
                $from = trim($m[1]);
            }

            $to      = $this->extractHeader($entry, 'To');
            $subject = $this->extractHeader($entry, 'Subject');

            // Extract HTML body — lines use CRLF, blank line separates headers from body
            $htmlBody = '';
            if (preg_match('/Content-Type: text\/html[^\r\n]*\r\n(?:[^\r\n]+\r\n)*\r\n(.+?)(?:\r\n--|\z)/s', $entry, $bm)) {
                $htmlBody = $bm[1];
            }

            $emails[] = [
                'timestamp' => $timestamp,
                'to'        => $to,
                'from'      => $from,
                'subject'   => $subject,
                'html'      => $htmlBody,
            ];

            if (count($emails) >= 10) {
                break;
            }
        }

        return $emails;
    }

    private function extractHeader(string $body, string $header): string
    {
        if (preg_match('/^' . $header . ':\s*(.+)$/im', $body, $m)) {
            return trim($m[1]);
        }
        return '';
    }
}
