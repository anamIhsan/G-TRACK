<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\PresenceController;
use App\Http\Controllers\Api\WhatsappController;
use App\Models\BaseMetafieldUser;
use Illuminate\Support\Facades\Route;
use App\Models\Interest;
use App\Models\MetafieldUser;
use App\Models\SubInterest;
use App\Models\User;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

$prefix = 'api';

Route::prefix($prefix)->name('api.')->group(function () {
    Route::put('users/reason/{id}', [ApiController::class, 'reason_deleted'])->name('users.reason_deleted');

    Route::get('/test/notif', [WhatsappController::class, 'testNotif'])->name('test.notif');
    Route::get('/test/notif/browser', [WhatsappController::class, 'testNotifBrowser'])->name('test.notif.browser');

    Route::post('/save-subscriptions', function (Request $request) {
        $user = User::find(Auth::user()->id);

        $user->updatePushSubscription(
            $request->endpoint,
            $request->keys['p256dh'],
            $request->keys['auth']
        );

        return response()->json(['status' => 'success']);
    })->name('save.subscriptions');

    Route::post('/presence/scan', [PresenceController::class, 'presence_scan'])->name('presence.scan');
    Route::post('/presence/tap', [PresenceController::class, 'presence_tap'])->name('presence.tap');

    Route::post('/auto-kirim-notifikasi', function ($oke) {
        return response()->json(['status' => 'success']);
    })->name('auto.kirim.notifikasi');

    Route::get('/get-interests-by-zone/{zone}', function ($zone) {
        return Interest::where('zone_id', $zone)->get();
    });

    Route::get('/get-sub_interest/{interest_id}', function ($interest_id) {
        return SubInterest::where('interest_id', $interest_id)->get();
    });

    Route::get('/get-works/{zone}', function ($zone_id) {
        return Work::where('zone_id', $zone_id)->get();
    });

    // create
    Route::get('/get-metafields_user/{zone}', function ($zone_id) {
        return MetafieldUser::where('zone_id', $zone_id)->get();
    });
    // edit
    Route::get('/get-metafields-by-zone/{zone}/{user}', function ($zone_id, $user_id) {
        return MetafieldUser::where('zone_id', $zone_id)
            ->get()
            ->map(function ($metafield) use ($user_id) {
                $value = BaseMetafieldUser::where('user_id', $user_id)
                    ->where('metafield_user_id', $metafield->id)
                    ->value('value');

                $metafield->value = $value;
                return $metafield;
            });
    });
});
