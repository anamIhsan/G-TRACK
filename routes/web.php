<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

Route::get('/send-push', function () {

    $subscription = Subscription::create([
        'endpoint' => 'ENDPOINT_DARI_BROWSER',
        'publicKey' => 'PUBKEY_DARI_BROWSER',
        'authToken' => 'AUTH_DARI_BROWSER',
    ]);

    $webPush = new WebPush([
        'VAPID' => [
            'subject' => 'mailto:hudz1357@gmail.com',
            'publicKey' => env('VAPID_PUBLIC_KEY'),
            'privateKey' => env('VAPID_PRIVATE_KEY'),
        ],
    ]);

    $webPush->sendOneNotification(
        $subscription,
        json_encode([
            'title' => 'Halo!',
            'body' => 'Notifikasi dari Laravel.',
        ])
    );

    return 'OK';
});


Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');
Route::resource('setting', SettingController::class)->middleware('auth');
Route::get('/404', fn() => view('pages.404'))->name('404');
Route::get('/403', fn() => view('pages.403'))->name('403');

include __DIR__ . '/user.php';
include __DIR__ . '/admin.php';
include __DIR__ . '/auth.php';
include __DIR__ . '/api.php';
