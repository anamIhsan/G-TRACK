<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\SimpleExcel\SimpleExcelWriter;

class PresenceExportController extends Controller
{
    public function export(Request $request)
    {
        $request->validate([
            'activity_id' => 'required|exists:activities,id'
        ]);

        return response()->streamDownload(function () use ($request) {

            $activity = Activity::with('ageCategories')
                ->where('id', $request->activity_id)
                ->first();

            $ageCategoryIds = $activity->ageCategories->pluck('id');
            $allowedStatusKawin = json_decode($activity->for_status_kawin, true) ?? [];

            // ======================
            // USER KEGIATAN
            // ======================
            $baseUserQuery = User::where('role', 'USER')
                ->where('zone_id', $activity->zone_id)
                ->where(function ($query) use ($ageCategoryIds, $allowedStatusKawin) {
                    $query->whereIn('age_category_id', $ageCategoryIds);

                    if (in_array('SUDAH', $allowedStatusKawin)) {
                        $query->orWhere('status_kawin', 'SUDAH');
                    }
                });

            if ($activity->type === "SEKALI") {
                $users = $baseUserQuery
                    ->with(['presences' => function ($q) use ($activity) {
                        $q->where('activity_id', $activity->id)->latest();
                    }])
                    ->get();
            } else {
                $users = $baseUserQuery
                    ->with(['presences' => function ($q) use ($activity) {
                        $q->where('activity_id', $activity->id)
                          ->where('created_at', '>=', today()->startOfDay())
                          ->latest();
                    }])
                    ->get();
            }

            // ===== EXCEL =====
            $writer = SimpleExcelWriter::streamDownload(
                'absensi_' . date('y-m-d_H-i-s') . '.xlsx'
            );

            // ===== INFO KEGIATAN =====
            $writer->addRow([
                'Info' => 'Nama Kegiatan',
                'Value' => $activity->nama
            ]);

            $writer->addRow([
                'Info' => 'Tipe Kegiatan',
                'Value' => $activity->type
            ]);

            $writer->addRow([
                'Info' => 'Jam Mulai',
                'Value' => $activity->jam
            ]);

            if ($activity->type === 'SEKALI') {
                $writer->addRow([
                    'Info' => 'Tanggal Kegiatan',
                    'Value' => $activity->tanggal
                ]);
            } else {
                $writer->addRow([
                    'Info' => 'Hari Kegiatan',
                    'Value' => $activity->nama_hari
                ]);
            }

            $writer->addRow([]); // spasi

            // ===== HEADER TABEL =====
            $writer->addHeader([
                'No',
                'Nama',
                'Jam Datang',
                'Status',
                'Keterangan'
            ]);

            // ===== DATA =====
            $no = 1;
            foreach ($users as $user) {
                $presence = $user->presences->first();

                $writer->addRow([
                    'No' => $no++,
                    'Nama' => $user->nama,
                    'Jam Datang' => $presence->jam_datang ?? '-',
                    'Status' => $presence->status ?? 'ALPHA',
                    'Keterangan' => $presence->keterangan ?? '-',
                ]);
            }
        }, 'absensi_' . date('y-m-d_H-i-s') . '.xlsx');
    }
}
