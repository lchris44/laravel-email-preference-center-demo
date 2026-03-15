<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Preference Center — Demo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 text-gray-800">

<div class="max-w-7xl mx-auto px-4 py-8 space-y-8">

    {{-- Header --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold text-indigo-700">Laravel Email Preference Center</h1>
        <p class="text-gray-500 mt-1">Interactive demo — all actions use the real package. Mail goes to the log driver.</p>
        @if(session('flash'))
            <div class="mt-3 bg-green-50 border border-green-300 text-green-700 rounded px-4 py-2 text-sm">
                {{ session('flash') }}
            </div>
        @endif
    </div>

    {{-- Trigger Actions + Mail Log side by side --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Left: Trigger Actions --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Trigger Actions</h2>

            <div class="flex flex-wrap gap-3 items-end mb-6">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Active user</label>
                    <select id="userSelect" class="border rounded px-3 py-2 text-sm">
                        @foreach($users as $u)
                            <option value="{{ $u['id'] }}">{{ $u['name'] }} ({{ $u['email'] }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Notification Channel --}}
            <h3 class="font-medium text-sm text-gray-600 mb-2">Notification Channel <span class="text-gray-400 font-normal">(via: 'email-preferences')</span></h3>
            <div class="flex flex-wrap gap-2 mb-5">
                @foreach([
                    ['route' => 'demo.notify.marketing', 'label' => 'Marketing notify', 'color' => 'indigo'],
                    ['route' => 'demo.notify.security',  'label' => 'Security notify',  'color' => 'red'],
                    ['route' => 'demo.notify.digest',    'label' => 'Digest notify',    'color' => 'yellow'],
                    ['route' => 'demo.notify.billing',   'label' => 'Billing notify (config map)', 'color' => 'green'],
                ] as $btn)
                    <form method="POST" action="{{ route($btn['route']) }}" class="inline">
                        @csrf
                        <input type="hidden" name="user_id" class="userIdField" value="{{ $users->first()['id'] }}">
                        <button class="px-3 py-1.5 rounded text-sm font-medium bg-{{ $btn['color'] }}-100 text-{{ $btn['color'] }}-800 hover:bg-{{ $btn['color'] }}-200">
                            {{ $btn['label'] }}
                        </button>
                    </form>
                @endforeach
            </div>

            {{-- Mailable --}}
            <h3 class="font-medium text-sm text-gray-600 mb-2">Mailable with List-Unsubscribe Headers</h3>
            <div class="flex flex-wrap gap-2 mb-5">
                <form method="POST" action="{{ route('demo.newsletter') }}" class="inline">
                    @csrf
                    <input type="hidden" name="user_id" class="userIdField" value="{{ $users->first()['id'] }}">
                    <button class="px-3 py-1.5 rounded text-sm font-medium bg-purple-100 text-purple-800 hover:bg-purple-200">
                        Send Newsletter (BelongsToCategory mailable)
                    </button>
                </form>
            </div>

            {{-- DigestQueue --}}
            <h3 class="font-medium text-sm text-gray-600 mb-2">DigestQueue::dispatch() direct</h3>
            <div class="flex flex-wrap gap-2">
                <form method="POST" action="{{ route('demo.digest.dispatch') }}" class="inline">
                    @csrf
                    <input type="hidden" name="user_id" class="userIdField" value="{{ $users->first()['id'] }}">
                    <button class="px-3 py-1.5 rounded text-sm font-medium bg-orange-100 text-orange-800 hover:bg-orange-200">
                        Dispatch digest item
                    </button>
                </form>
                <form method="POST" action="{{ route('demo.send.daily') }}" class="inline">
                    @csrf
                    <button class="px-3 py-1.5 rounded text-sm font-medium bg-orange-600 text-white hover:bg-orange-700">
                        Send daily digests now
                    </button>
                </form>
            </div>
        </div>

        {{-- Right: Mail Log --}}
        <div class="bg-white rounded-xl shadow p-6 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Mail Log</h2>
                <span class="text-xs text-gray-400">Last 10 email · refresh to update</span>
            </div>

            @if(empty($mailLog))
                <p class="text-sm text-gray-400">No emails logged yet. Trigger a notification above.</p>
            @else
                <div class="space-y-3 overflow-y-auto">
                    @foreach($mailLog as $mail)
                    <details class="border rounded-lg overflow-hidden">
                        <summary class="flex items-center gap-4 px-4 py-2 bg-gray-50 cursor-pointer hover:bg-gray-100 text-sm list-none">
                            <span class="text-gray-400 text-xs whitespace-nowrap">{{ $mail['timestamp'] }}</span>
                            <span class="font-medium text-gray-800 truncate">{{ $mail['subject'] ?: '(no subject)' }}</span>
                            <span class="text-gray-500 text-xs ml-auto shrink-0">{{ $mail['to'] }}</span>
                        </summary>
                        <div class="px-4 py-3 text-xs space-y-1 bg-white border-t">
                            <div><span class="text-gray-400 w-14 inline-block">From:</span> {{ $mail['from'] }}</div>
                            <div><span class="text-gray-400 w-14 inline-block">To:</span> {{ $mail['to'] }}</div>
                            <div><span class="text-gray-400 w-14 inline-block">Subject:</span> {{ $mail['subject'] }}</div>
                            @if($mail['html'])
                            <div class="mt-3 border-t pt-3">
                                <iframe srcdoc="{!! htmlspecialchars($mail['html'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') !!}"
                                        sandbox="allow-same-origin"
                                        class="w-full rounded border bg-white"
                                        style="min-height:500px;"
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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($users as $u)
        <div class="bg-white rounded-xl shadow p-5">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="font-semibold">{{ $u['name'] }}</p>
                    <p class="text-xs text-gray-400">{{ $u['email'] }}</p>
                </div>
                <a href="{{ $u['center_url'] }}" target="_blank"
                   class="text-xs bg-indigo-600 text-white px-2 py-1 rounded hover:bg-indigo-700">
                    Preference Center
                </a>
            </div>

            <table class="w-full text-xs">
                <thead>
                    <tr class="text-gray-400 border-b">
                        <th class="text-left pb-1">Category</th>
                        <th class="text-center pb-1">Sub</th>
                        <th class="text-center pb-1">Freq</th>
                        <th class="text-center pb-1">Req</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($u['prefs'] as $key => $pref)
                    <tr class="border-b last:border-0">
                        <td class="py-1 text-gray-600">{{ $pref['label'] }}</td>
                        <td class="py-1 text-center">
                            @if($pref['subscribed'])
                                <span class="text-green-600 font-semibold">✓</span>
                            @else
                                <span class="text-red-400">✗</span>
                            @endif
                        </td>
                        <td class="py-1 text-center text-gray-500">
                            {{ $pref['has_freq'] ? $pref['frequency'] : '—' }}
                        </td>
                        <td class="py-1 text-center">
                            @if($pref['required'])
                                <span class="text-amber-600">🔒</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($u['pending_items'] > 0)
            <p class="mt-2 text-xs text-orange-600 font-medium">
                {{ $u['pending_items'] }} pending digest item(s)
            </p>
            @endif

            {{-- Consent Log --}}
            @if($u['consent_log']->count())
            <div class="mt-3">
                <p class="text-xs text-gray-400 font-medium mb-1">GDPR Consent Log (last 5)</p>
                @foreach($u['consent_log'] as $log)
                <div class="text-xs text-gray-500 flex gap-1">
                    <span class="w-16 shrink-0">{{ $log->category }}</span>
                    <span class="{{ $log->action === 'subscribed' ? 'text-green-600' : 'text-red-500' }}">{{ $log->action }}</span>
                    <span class="text-gray-400">via {{ $log->via }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @endforeach
    </div>

    {{-- Event feed --}}
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Live Event Feed</h2>
            <span class="text-xs text-gray-400">Last 30 events · refresh to update</span>
        </div>

        @if($demoEvents->isEmpty())
            <p class="text-sm text-gray-400">No events fired yet. Trigger an action above.</p>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-gray-400 border-b">
                        <th class="text-left pb-2">Time</th>
                        <th class="text-left pb-2">Event</th>
                        <th class="text-left pb-2">User</th>
                        <th class="text-left pb-2">Category</th>
                        <th class="text-left pb-2">Action / Freq</th>
                        <th class="text-left pb-2">Via</th>
                        <th class="text-left pb-2">Items</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($demoEvents as $ev)
                    @php
                        $colors = [
                            'PreferenceUpdated' => 'text-blue-600',
                            'UserUnsubscribed'  => 'text-red-600',
                            'DigestQueued'      => 'text-orange-600',
                            'DigestSent'        => 'text-green-600',
                        ];
                        $color = $colors[$ev->event_class] ?? 'text-gray-600';
                    @endphp
                    <tr class="border-b last:border-0">
                        <td class="py-1 text-gray-400 whitespace-nowrap">{{ $ev->created_at->format('H:i:s') }}</td>
                        <td class="py-1 font-mono font-semibold {{ $color }}">{{ $ev->event_class }}</td>
                        <td class="py-1">{{ $ev->user_name }}</td>
                        <td class="py-1 text-gray-500">{{ $ev->category }}</td>
                        <td class="py-1 text-gray-500">{{ $ev->action ?? $ev->frequency }}</td>
                        <td class="py-1 text-gray-400">{{ $ev->via }}</td>
                        <td class="py-1 text-gray-400">
                            {{ $ev->item_count !== null ? $ev->item_count . ' item' . ($ev->item_count !== 1 ? 's' : '') : '—' }}
                        </td>
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
</script>

</body>
</html>
