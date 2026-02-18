<?php

use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\AgeCategoryController;
use App\Http\Controllers\Admin\CardController;
use App\Http\Controllers\Admin\InterestController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\MetafieldLevelController;
use App\Http\Controllers\Admin\MetafieldUserController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PresenceController;
use App\Http\Controllers\Admin\SubAdminController;
use App\Http\Controllers\Admin\SubInterestController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WorkController;
use App\Http\Controllers\Admin\ZoneController;
use App\Http\Controllers\Export\PresenceExportController;
use App\Http\Controllers\Import\UserImportController;
use App\Http\Controllers\Export\UserExportController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

$prefix = 'admin';

Route::prefix($prefix)->middleware(['auth', 'role:MASTER|ADMIN_DAERAH'])->name('admin.')->group(function () {
    Route::post('users/image/crop', [UserController::class, 'imageCrop'])->name('users.image.crop');
    Route::resource('users', UserController::class);
    Route::get('history_users', [UserController::class, 'history_users_index'])->name('history_users.index');
    Route::put('history_users/{id}/update', [UserController::class, 'history_users_update'])->name('history_users.update');
    Route::resource('levels', LevelController::class);
    Route::resource('works', WorkController::class);
    Route::resource('interests', InterestController::class);
    Route::resource('sub_interests', SubInterestController::class)->only(['update', 'store', 'destroy']);
    Route::resource('activities', ActivityController::class)->except(['show']);
    Route::post('send_recap', [NotificationController::class, 'sendRecap'])->name('send_recap');
    Route::resource('notifications', NotificationController::class);
    Route::get('cards/twibbon/download/all', [CardController::class, 'twibbon_download_all'])->name('cards.twibbon.download.all');
    Route::get('cards/twibbon/download/{id}', [CardController::class, 'twibbon_download'])->name('cards.twibbon.download');
    Route::get('cards/twibbon/create', [CardController::class, 'twibbon_create'])->name('cards.twibbon.create');
    Route::post('cards/twibbon/store', [CardController::class, 'twibbon_store'])->name('cards.twibbon.store');
    Route::resource('cards', CardController::class);
    Route::get('presences/history/{activity_id}', [PresenceController::class, 'history'])->name('presences.history');
    Route::get('presences/scanner/{activity_id}', [PresenceController::class, 'scanner'])->name('presences.scanner');
    Route::put('presences/update/{activity_id}', [PresenceController::class, 'update'])->name('presence.update');
    Route::resource('presences', PresenceController::class);
    Route::resource('zones', ZoneController::class);
    Route::get('download/template/users', [UserImportController::class, 'download_template'])->name('import.users.download_template');
    Route::get('import/users', [UserImportController::class, 'index'])->name('import.users.index');
    Route::post('import/users', [UserImportController::class, 'importUsers'])->name('import.users.store');
    Route::get('export/users', [UserExportController::class, 'exportUsers'])->name('export.users.store');
    Route::get('export/users/single/{id}', [UserExportController::class, 'exportUserSingle'])->name('export.users.single');
    Route::get('/export/presences', [PresenceExportController::class, 'export'])
    ->name('export.presences');

    Route::post('setting/whatsapp-gateway', [SettingController::class, 'whatsappGateway'])->name('setting.whatsapp-gateway');
    // Route::post('presences/scanner/nvc')
    Route::resource('metafield_users/metafield_user', MetafieldUserController::class);
    Route::resource('metafield_levels/metafield_level', MetafieldLevelController::class)->except(['show']);
    Route::middleware('role:MASTER')->group(function () {
        Route::resource('age_categories', AgeCategoryController::class);
        Route::resource('subadmins', SubAdminController::class);
    });
});
