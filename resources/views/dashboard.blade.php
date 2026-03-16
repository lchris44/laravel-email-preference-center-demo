<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Preference Center — Live Demo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="/docs/style.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* ── Demo page layout ── */
        .demo-wrap {
            padding-top: calc(var(--topbar-h) + 48px);
            padding-bottom: 80px;
            max-width: 1300px;
            margin: 0 auto;
            padding-left: clamp(16px, 4vw, 48px);
            padding-right: clamp(16px, 4vw, 48px);
            overflow-x: hidden;
        }

        /* ── Page header ── */
        .demo-page-header {
            margin-bottom: 36px;
        }
        .demo-page-header h1 {
            font-size: 1.9rem;
            font-weight: 700;
            line-height: 1.25;
            margin-bottom: 8px;
        }
        .demo-page-header p {
            font-size: .95rem;
            color: var(--text-muted);
            margin: 0;
        }

        /* ── Cards ── */
        .demo-card {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 28px;
            box-shadow: var(--shadow);
        }
        .demo-card h2 {
            font-size: 1rem;
            font-weight: 600;
            margin: 0 0 20px;
        }
        .demo-card h3 {
            font-size: .78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--text-muted);
            margin: 0 0 10px;
        }

        /* ── Grids ── */
        .demo-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
            min-width: 0;
        }
        .demo-grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        /* ── Select ── */
        .demo-select-wrap label {
            display: block;
            font-size: .75rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 6px;
        }
        .demo-select {
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 7px 12px;
            font-size: .85rem;
            font-family: var(--font-sans);
            background: var(--bg);
            color: var(--text);
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%235a6475' stroke-width='2' stroke-linecap='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            padding-right: 30px;
            transition: border-color .15s;
            cursor: pointer;
        }
        .demo-select:focus { outline: none; border-color: var(--accent); }

        /* ── Action buttons (small, color variants) ── */
        .btn-sm {
            display: inline-flex;
            align-items: center;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: .8rem;
            font-weight: 500;
            font-family: var(--font-sans);
            border: none;
            cursor: pointer;
            transition: all .15s;
            text-decoration: none;
            line-height: 1.4;
        }
        .btn-sm-indigo { background: rgba(99,102,241,.1);  color: #4338ca; }
        .btn-sm-indigo:hover { background: rgba(99,102,241,.18); }
        .btn-sm-red    { background: rgba(239,68,68,.1);   color: #b91c1c; }
        .btn-sm-red:hover    { background: rgba(239,68,68,.18); }
        .btn-sm-yellow { background: rgba(234,179,8,.1);   color: #92400e; }
        .btn-sm-yellow:hover { background: rgba(234,179,8,.18); }
        .btn-sm-green  { background: rgba(34,197,94,.1);   color: #15803d; }
        .btn-sm-green:hover  { background: rgba(34,197,94,.18); }
        .btn-sm-purple { background: rgba(168,85,247,.1);  color: #6b21a8; }
        .btn-sm-purple:hover { background: rgba(168,85,247,.18); }
        .btn-sm-orange { background: rgba(249,115,22,.1);  color: #9a3412; }
        .btn-sm-orange:hover { background: rgba(249,115,22,.18); }

        [data-theme="dark"] .btn-sm-indigo { color: #a5b4fc; }
        [data-theme="dark"] .btn-sm-red    { color: #fca5a5; }
        [data-theme="dark"] .btn-sm-yellow { color: #fde68a; }
        [data-theme="dark"] .btn-sm-green  { color: #86efac; }
        [data-theme="dark"] .btn-sm-purple { color: #d8b4fe; }
        [data-theme="dark"] .btn-sm-orange { color: #fdba74; }

        .btn-sm-solid-orange {
            background: var(--accent);
            color: #fff;
            box-shadow: 0 1px 3px rgba(240,83,64,.3);
        }
        .btn-sm-solid-orange:hover {
            background: var(--accent-dark);
            transform: translateY(-1px);
        }

        /* ── Section divider inside card ── */
        .card-section { margin-bottom: 24px; }
        .card-section:last-child { margin-bottom: 0; }

        /* ── Button group ── */
        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        /* ── Flash message ── */
        .flash-msg {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(34,197,94,.08);
            border: 1px solid rgba(34,197,94,.25);
            border-left: 3px solid #22c55e;
            border-radius: var(--radius);
            padding: 10px 16px;
            font-size: .875rem;
            color: var(--text);
            margin-bottom: 24px;
        }

        /* ── Mail log ── */
        .mail-log-empty {
            font-size: .875rem;
            color: var(--text-muted);
        }
        .mail-log-list { display: flex; flex-direction: column; gap: 8px; }

        .mail-entry {
            border: 1px solid var(--border);
            border-radius: 6px;
            overflow: hidden;
        }
        .mail-entry summary {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 9px 14px;
            background: var(--bg-subtle);
            cursor: pointer;
            list-style: none;
            font-size: .8rem;
            transition: background .15s;
            user-select: none;
        }
        .mail-entry summary:hover { background: var(--border); }
        .mail-entry summary::-webkit-details-marker { display: none; }
        .mail-entry-time  { color: var(--text-muted); white-space: nowrap; flex-shrink: 0; font-family: var(--font-mono); font-size: .75rem; }
        .mail-entry-subj  { font-weight: 500; color: var(--text); flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .mail-entry-to    { color: var(--text-muted); font-size: .75rem; white-space: nowrap; flex-shrink: 0; display: none; }

        .mail-entry-body {
            padding: 12px 14px;
            font-size: .78rem;
            background: var(--bg);
            border-top: 1px solid var(--border);
        }
        .mail-entry-body .meta-row { display: flex; gap: 6px; margin-bottom: 4px; color: var(--text-muted); }
        .mail-entry-body .meta-row strong { color: var(--text); width: 50px; flex-shrink: 0; }
        .mail-entry-iframe-wrap { margin-top: 12px; border-top: 1px solid var(--border); padding-top: 12px; }
        .mail-entry-iframe-wrap iframe {
            width: 100%;
            max-width: 100%;
            border: 1px solid var(--border);
            border-radius: 6px;
            background: #fff;
            min-height: 400px;
            display: block;
        }

        /* ── Preference state ── */
        .user-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 16px;
            gap: 8px;
        }
        .user-card-name { font-weight: 600; font-size: .9rem; }
        .user-card-email { font-size: .75rem; color: var(--text-muted); margin-top: 2px; }

        .btn-pref-center {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: .72rem;
            font-weight: 600;
            background: var(--accent);
            color: #fff;
            text-decoration: none;
            white-space: nowrap;
            flex-shrink: 0;
            transition: background .15s;
        }
        .btn-pref-center:hover { background: var(--accent-dark); text-decoration: none; color: #fff; }

        .pref-table { width: 100%; border-collapse: collapse; font-size: .78rem; margin-bottom: 0; }
        .pref-table thead tr { background: var(--bg-subtle); }
        .pref-table th {
            padding: 6px 8px;
            text-align: left;
            font-size: .68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
        }
        .pref-table th:not(:first-child) { text-align: center; }
        .pref-table td {
            padding: 6px 8px;
            border-bottom: 1px solid var(--border);
            color: var(--text-muted);
            vertical-align: middle;
        }
        .pref-table tr:last-child td { border-bottom: none; }
        .pref-table tr:hover td { background: var(--bg-subtle); }
        .pref-table td:not(:first-child) { text-align: center; }

        .pref-subscribed   { color: #16a34a; font-weight: 700; }
        .pref-unsubscribed { color: #dc2626; }

        .pending-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 10px;
            padding: 4px 10px;
            border-radius: 20px;
            background: rgba(249,115,22,.1);
            color: #9a3412;
            font-size: .72rem;
            font-weight: 600;
            border: 1px solid rgba(249,115,22,.2);
        }
        [data-theme="dark"] .pending-badge { color: #fdba74; }

        .consent-log { margin-top: 16px; padding-top: 14px; border-top: 1px solid var(--border); }
        .consent-log-title {
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--text-muted);
            margin-bottom: 8px;
        }
        .consent-row {
            display: flex;
            gap: 6px;
            font-size: .75rem;
            color: var(--text-muted);
            padding: 2px 0;
        }
        .consent-cat { width: 70px; flex-shrink: 0; }
        .consent-subscribed   { color: #16a34a; font-weight: 500; }
        .consent-unsubscribed { color: #dc2626; font-weight: 500; }

        /* ── Event feed ── */
        .event-feed-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .event-feed-header h2 { margin: 0; }
        .event-meta-label {
            font-size: .75rem;
            color: var(--text-muted);
        }
        .event-table { width: 100%; border-collapse: collapse; font-size: .8rem; }
        .event-table thead tr { background: var(--bg-subtle); }
        .event-table th {
            padding: 8px 12px;
            text-align: left;
            font-size: .7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        .event-table td {
            padding: 8px 12px;
            border-bottom: 1px solid var(--border);
            color: var(--text-muted);
            vertical-align: top;
        }
        .event-table tr:last-child td { border-bottom: none; }
        .event-table tr:hover td { background: var(--bg-subtle); }
        .event-time { font-family: var(--font-mono); font-size: .72rem; white-space: nowrap; }
        .event-class { font-family: var(--font-mono); font-weight: 600; font-size: .78rem; }
        .event-class-preference { color: #2563eb; }
        .event-class-unsubscribe { color: #dc2626; }
        .event-class-digest-queued { color: #ea580c; }
        .event-class-digest-sent { color: #16a34a; }
        .event-class-default { color: var(--text-muted); }

        [data-theme="dark"] .event-class-preference { color: #60a5fa; }
        [data-theme="dark"] .event-class-unsubscribe { color: #f87171; }
        [data-theme="dark"] .event-class-digest-queued { color: #fb923c; }
        [data-theme="dark"] .event-class-digest-sent { color: #4ade80; }

        /* ── Show Docs + GitHub on mobile ── */
        @media (max-width: 768px) {
            .topbar-links .github-link,
            .topbar-links .docs-link { display: inline-flex !important; }
        }

        /* ── Responsive ── */
        @media (max-width: 860px) {
            .demo-grid-2 { grid-template-columns: 1fr; }
        }
        @media (max-width: 1024px) {
            .demo-grid-3 { grid-template-columns: 1fr 1fr; }
        }
        @media (min-width: 600px) {
            .mail-entry-to { display: block; }
        }
        @media (max-width: 768px) {
            .demo-grid-3 { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- Top bar (matches docs page) -->
<header class="topbar">
    <div class="topbar-inner">
        <a href="/" class="topbar-logo">
            <span>Email Preference Center</span>
        </a>
        <nav class="topbar-links">
            <a href="/" class="docs-link">Docs</a>
            <a href="https://github.com/lchris44/laravel-email-preference-center" target="_blank" rel="noopener" class="github-link">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0 1 12 6.844a9.59 9.59 0 0 1 2.504.337c1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.02 10.02 0 0 0 22 12.017C22 6.484 17.522 2 12 2Z"/></svg>
                <span>GitHub</span>
            </a>
        </nav>
        <button class="theme-toggle" id="themeToggle" title="Toggle dark mode" aria-label="Toggle dark mode">
            <svg class="sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
            <svg class="moon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
        </button>
    </div>
</header>

<div class="demo-wrap">

    {{-- Page header --}}
    <div class="demo-page-header">
        <div class="hero-badge" style="margin-bottom:14px;">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right:5px;" aria-hidden="true"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            LIVE DEMO
        </div>
        <h1>Laravel Email Preference Center</h1>
        <p>Interactive demo — all actions use the real package. Mail goes to the log driver.</p>
    </div>

    @if(session('flash'))
        <div class="flash-msg">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('flash') }}
        </div>
    @endif

    {{-- Trigger Actions + Mail Log --}}
    <div class="demo-grid-2">

        {{-- Trigger Actions --}}
        <div class="demo-card">
            <h2>Trigger Actions</h2>

            <div class="card-section">
                <div class="demo-select-wrap">
                    <label for="userSelect">Active user</label>
                    <select id="userSelect" class="demo-select">
                        @foreach($users as $u)
                            <option value="{{ $u['id'] }}">{{ $u['name'] }} ({{ $u['email'] }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Notification Channel --}}
            <div class="card-section">
                <h3>Notification Channel <span style="font-weight:400;text-transform:none;letter-spacing:0;font-size:.75rem;color:var(--text-muted);">via: 'email-preferences'</span></h3>
                <div class="btn-group">
                    @foreach([
                        ['route' => 'demo.notify.marketing', 'label' => 'Marketing notify',              'cls' => 'btn-sm-indigo'],
                        ['route' => 'demo.notify.security',  'label' => 'Security notify',               'cls' => 'btn-sm-red'],
                        ['route' => 'demo.notify.digest',    'label' => 'Digest notify',                 'cls' => 'btn-sm-yellow'],
                        ['route' => 'demo.notify.billing',   'label' => 'Billing notify (config map)',   'cls' => 'btn-sm-green'],
                    ] as $btn)
                        <form method="POST" action="{{ route($btn['route']) }}" style="display:inline;">
                            @csrf
                            <input type="hidden" name="user_id" class="userIdField" value="{{ $users->first()['id'] }}">
                            <button type="submit" class="btn-sm {{ $btn['cls'] }}">{{ $btn['label'] }}</button>
                        </form>
                    @endforeach
                </div>
            </div>

            {{-- Mailable --}}
            <div class="card-section">
                <h3>Mailable with List-Unsubscribe Headers</h3>
                <div class="btn-group">
                    <form method="POST" action="{{ route('demo.newsletter') }}" style="display:inline;">
                        @csrf
                        <input type="hidden" name="user_id" class="userIdField" value="{{ $users->first()['id'] }}">
                        <button type="submit" class="btn-sm btn-sm-purple">Send Newsletter (BelongsToCategory mailable)</button>
                    </form>
                </div>
            </div>

            {{-- DigestQueue --}}
            <div class="card-section">
                <h3>DigestQueue::dispatch() direct</h3>
                <div class="btn-group">
                    <form method="POST" action="{{ route('demo.digest.dispatch') }}" style="display:inline;">
                        @csrf
                        <input type="hidden" name="user_id" class="userIdField" value="{{ $users->first()['id'] }}">
                        <button type="submit" class="btn-sm btn-sm-orange">Dispatch digest item</button>
                    </form>
                    <form method="POST" action="{{ route('demo.send.daily') }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-sm btn-sm-solid-orange">Send daily digests now</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Mail Log --}}
        <div class="demo-card" style="display:flex;flex-direction:column;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                <h2 style="margin:0;">Mail Log</h2>
                <span class="event-meta-label">Last 10 emails · refresh to update</span>
            </div>

            @if(empty($mailLog))
                <p class="mail-log-empty">No emails logged yet. Trigger a notification above.</p>
            @else
                <div class="mail-log-list">
                    @foreach($mailLog as $mail)
                    <details class="mail-entry">
                        <summary>
                            <span class="mail-entry-time">{{ $mail['timestamp'] }}</span>
                            <span class="mail-entry-subj">{{ $mail['subject'] ?: '(no subject)' }}</span>
                            <span class="mail-entry-to">{{ $mail['to'] }}</span>
                        </summary>
                        <div class="mail-entry-body">
                            <div class="meta-row"><strong>From:</strong> {{ $mail['from'] }}</div>
                            <div class="meta-row"><strong>To:</strong> {{ $mail['to'] }}</div>
                            <div class="meta-row"><strong>Subject:</strong> {{ $mail['subject'] }}</div>
                            @if($mail['html'])
                            <div class="mail-entry-iframe-wrap">
                                <iframe srcdoc="{!! htmlspecialchars($mail['html'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') !!}"
                                        sandbox="allow-same-origin"
                                        onload="var d=this.contentDocument;this.style.height=(d.body.scrollHeight||d.documentElement.scrollHeight)+'px'">
                                </iframe>
                            </div>
                            @endif
                        </div>
                    </details>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

    {{-- User preference state --}}
    <div class="demo-grid-3">
        @foreach($users as $u)
        <div class="demo-card">
            <div class="user-card-header">
                <div>
                    <div class="user-card-name">{{ $u['name'] }}</div>
                    <div class="user-card-email">{{ $u['email'] }}</div>
                </div>
                <a href="{{ $u['center_url'] }}" target="_blank" class="btn-pref-center">Preference Center</a>
            </div>

            <table class="pref-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Sub</th>
                        <th>Freq</th>
                        <th>Req</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($u['prefs'] as $key => $pref)
                    <tr>
                        <td style="color:var(--text);">{{ $pref['label'] }}</td>
                        <td>
                            @if($pref['subscribed'])
                                <span class="pref-subscribed">✓</span>
                            @else
                                <span class="pref-unsubscribed">✗</span>
                            @endif
                        </td>
                        <td>{{ $pref['has_freq'] ? $pref['frequency'] : '—' }}</td>
                        <td>
                            @if($pref['required'])
                                <span style="color:#d97706;">🔒</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($u['pending_items'] > 0)
            <div>
                <span class="pending-badge">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    {{ $u['pending_items'] }} pending digest item{{ $u['pending_items'] !== 1 ? 's' : '' }}
                </span>
            </div>
            @endif

            @if($u['consent_log']->count())
            <div class="consent-log">
                <div class="consent-log-title">GDPR Consent Log (last 5)</div>
                @foreach($u['consent_log'] as $log)
                <div class="consent-row">
                    <span class="consent-cat">{{ $log->category }}</span>
                    <span class="{{ $log->action === 'subscribed' ? 'consent-subscribed' : 'consent-unsubscribed' }}">{{ $log->action }}</span>
                    <span>via {{ $log->via }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @endforeach
    </div>

    {{-- Event feed --}}
    <div class="demo-card">
        <div class="event-feed-header">
            <h2>Live Event Feed</h2>
            <span class="event-meta-label">Last 30 events · refresh to update</span>
        </div>

        @if($demoEvents->isEmpty())
            <p style="font-size:.875rem;color:var(--text-muted);">No events fired yet. Trigger an action above.</p>
        @else
        <div style="overflow-x:auto;">
            <table class="event-table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Event</th>
                        <th>User</th>
                        <th>Category</th>
                        <th>Action / Freq</th>
                        <th>Via</th>
                        <th>Items</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($demoEvents as $ev)
                    @php
                        $cls = match($ev->event_class) {
                            'PreferenceUpdated' => 'event-class-preference',
                            'UserUnsubscribed'  => 'event-class-unsubscribe',
                            'DigestQueued'      => 'event-class-digest-queued',
                            'DigestSent'        => 'event-class-digest-sent',
                            default             => 'event-class-default',
                        };
                    @endphp
                    <tr>
                        <td class="event-time">{{ $ev->created_at->format('H:i:s') }}</td>
                        <td><span class="event-class {{ $cls }}">{{ $ev->event_class }}</span></td>
                        <td style="color:var(--text);">{{ $ev->user_name }}</td>
                        <td>{{ $ev->category }}</td>
                        <td>{{ $ev->action ?? $ev->frequency }}</td>
                        <td>{{ $ev->via }}</td>
                        <td>{{ $ev->item_count !== null ? $ev->item_count . ' item' . ($ev->item_count !== 1 ? 's' : '') : '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>

<script>
// Keep user_id in sync across all forms
document.getElementById('userSelect').addEventListener('change', function () {
    document.querySelectorAll('.userIdField').forEach(function (field) {
        field.value = this.value;
    }.bind(this));
});

// Theme toggle (same as docs page)
(function () {
    const toggle = document.getElementById('themeToggle');
    const html   = document.documentElement;
    const saved  = localStorage.getItem('theme');
    if (saved === 'dark') html.setAttribute('data-theme', 'dark');

    toggle.addEventListener('click', function () {
        const isDark = html.getAttribute('data-theme') === 'dark';
        html.setAttribute('data-theme', isDark ? 'light' : 'dark');
        localStorage.setItem('theme', isDark ? 'light' : 'dark');
    });
})();
</script>

</body>
</html>
