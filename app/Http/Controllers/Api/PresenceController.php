<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Presence;
use App\Models\User;
use Illuminate\Http\Request;

class PresenceController extends Controller
{
    public function presence_scan(Request $request)
    {
        $request->validate([
            'nfc_id' => 'required',
            'activity_id' => 'required'
        ]);

        // 1. Cek user berdasarkan NFC
        $user = User::where('nfc_id', $request->nfc_id)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User tidak terdaftar'
            ], 404);
        }

        // 2. Ambil activity
        $activity = Activity::with('ageCategories')
            ->where('id', $request->activity_id)
            ->first();

        if (!$activity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kegiatan tidak ditemukan'
            ], 404);
        }

        // 3. Validasi user terdaftar dalam kegiatan
        $ageCategoryIds = $activity->ageCategories->pluck('id')->toArray();
        $allowedStatusKawin = json_decode($activity->for_status_kawin, true) ?? [];

        $isAllowed = User::where('id', $user->id)
            ->where('role', 'USER')
            ->where(function ($query) use ($ageCategoryIds, $allowedStatusKawin) {
                $query->whereIn('age_category_id', $ageCategoryIds);

                if (in_array('SUDAH', $allowedStatusKawin)) {
                    $query->orWhere('status_kawin', 'SUDAH');
                }
            })
            ->exists();

        if (!$isAllowed) {
            return response()->json([
                'status' => 'error',
                'message' => 'User tidak terdaftar dalam kegiatan ini'
            ], 403);
        }

        // 4. Cek apakah user sudah presensi
        $alreadyPresence = Presence::where('user_id', $user->id)
            ->where('activity_id', $activity->id)
            ->exists();

        if ($alreadyPresence) {
            return response()->json([
                'status' => 'error',
                'message' => 'User sudah melakukan presensi'
            ], 409);
        }

        // 5. Simpan presensi
        Presence::create([
            'user_id' => $user->id,
            'activity_id' => $activity->id,
            'jam_datang' => now()->format('H:i:s'),
            'status' => 'HADIR'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Presensi berhasil',
            'data' => $user->nama
        ]);
    }

    public function presence_tap(Request $request)
    {
        $request->validate([
            'nfc_id' => 'required',
        ]);

        // try {
        $user = User::where('nfc_id', $request->nfc_id)->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Kartu tidak terdaftar']);
        }

        Presence::create([
            'user_id' => $user->id,
            'jam_datang' => now()->format('H:i:s'),
            'status' => 'HADIR',
            'activity_id' => $request->activity_id
        ]);

        return response()->json(['status' => 'success', 'message' => "Presensi berhasil", 'data' => $user->nama]);
        // } catch (\Throwable $th) {
        //     return response()->json(['status' => 'error', 'message' => $th->getMessage(), 'requests' => $request->all()]);
        // }
    }
}
