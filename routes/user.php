<?php

use App\Http\Controllers\User\ActivityController;
use App\Http\Controllers\User\LevelController;
use App\Http\Controllers\User\PresenceController;
use App\Http\Controllers\User\ProfileController;
use Illuminate\Support\Facades\Route;

$prefix = 'user';

Route::prefix($prefix)->name('user.')->middleware(['auth', 'role:USER'])->group(function () {
    Route::get('user_presences/nfc', [PresenceController::class, 'nfc'])->name('user_presences.nfc');
    Route::resource('user_presences', PresenceController::class);
    Route::resource('user_activities', ActivityController::class);
    Route::put('profiles/photo', [ProfileController::class, 'updatePhoto'])->name('profiles.update.photo');
    Route::get('profiles/download/card', [ProfileController::class, 'twibbon_download'])->name('profiles.download.card');
    Route::resource('profiles', ProfileController::class);
    Route::resource('user_levels', LevelController::class);
});
