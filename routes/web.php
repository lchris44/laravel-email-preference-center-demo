<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DemoActionController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => response()->file(public_path('docs/index.html')))->name('docs');
Route::get('/demo', [DashboardController::class, 'index'])->name('dashboard');

// Notifications
Route::post('/demo/notify/marketing',  [DemoActionController::class, 'notifyMarketing'])->name('demo.notify.marketing');
Route::post('/demo/notify/security',   [DemoActionController::class, 'notifySecurity'])->name('demo.notify.security');
Route::post('/demo/notify/digest',     [DemoActionController::class, 'notifyDigest'])->name('demo.notify.digest');
Route::post('/demo/notify/billing',    [DemoActionController::class, 'notifyBilling'])->name('demo.notify.billing');

// Mailable
Route::post('/demo/newsletter',        [DemoActionController::class, 'sendNewsletter'])->name('demo.newsletter');

// DigestQueue direct
Route::post('/demo/digest-dispatch',   [DemoActionController::class, 'dispatchDigest'])->name('demo.digest.dispatch');

// Preference management
Route::post('/demo/subscribe',         [DemoActionController::class, 'subscribe'])->name('demo.subscribe');
Route::post('/demo/unsubscribe',       [DemoActionController::class, 'unsubscribe'])->name('demo.unsubscribe');
Route::post('/demo/frequency',         [DemoActionController::class, 'setFrequency'])->name('demo.frequency');

// Commands
Route::post('/demo/seeder',            [DemoActionController::class, 'runSeeder'])->name('demo.seeder');
Route::post('/demo/send-daily',        [DemoActionController::class, 'sendDailyDigests'])->name('demo.send.daily');
