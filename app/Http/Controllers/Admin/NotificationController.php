<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Notification;
use App\Models\User;
use App\Models\Zone;
use App\Notifications\SendWebPush;
use App\Services\FonteeService;
use App\Services\WablasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification as FacadesNotification;

class NotificationController extends Controller
{
    public $auth;
    public $authData;

    /**
     * Constructor method for ActivityController.
     *
     * It sets the auth and authData properties with the current user.
     */
    public function __construct()
    {
        $auth = Auth::user();
        $this->auth = $auth;
        $this->authData = User::with(['zoneAdmin', 'whatsappGateway'])->find($auth->id);
    }

    /**
     * Tampilkan daftar notifikasi.
     */
    public function index()
    {
        if ($this->authData->role === 'MASTER') {
            $notifications = Notification::get();
        } elseif ($this->authData->role === 'ADMIN_DAERAH') {
            $notifications = Notification::where('zone_id', $this->authData->zoneAdmin->id)->get();
        } else {
            $notifications = Notification::where('zone_id', $this->authData->zone_id)->get();
        }

        return view('admin.notification.index', [
            'notifications' => $notifications,
        ]);
    }

    /**
     * Tampilkan form tambah notifikasi.
     */
    public function create()
    {
        $zones = Zone::get();
        $activities = Activity::get();

        return view('admin.notification.create', [
            'zones' => $zones,
            'activities' => $activities
        ]);
    }

    /**
     * Simpan data notifikasi baru.
     */
    public function store(Request $request)
    {
        $requestData = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'jenis_notification' => ['required', 'in:SEKARANG,TERJADWAL'],
            'jenis_pengiriman' => ['nullable', 'in:SEKALI,TIAP_HARI'],
            'tanggal' => ['nullable', 'date'],
            'jam' => ['nullable', 'date_format:H:i'],
            'activity_id' => ['required', 'exists:activities,id'],
        ]);

        if ($this->authData->role === "MASTER" && !$request->zone_id) {
            return redirect()->back()
                ->withErrors(['zone_id' => 'Pilih daerah terlebih dahulu.'])
                ->withInput();
        }

        if ($this->authData->role === 'MASTER') {
            $requestData['zone_id'] = $request->zone_id;
        } elseif ($this->authData->role === 'ADMIN_DAERAH') {
            $requestData['zone_id'] = $this->authData->zoneAdmin->id;
        } else {
            return redirect()->route('403');
        }

        // Untuk WA Gateway
        $response = $this->sendNotification($request);

        if ($response || $this->authData->whatsappGateway()->count() === 0) {
            return redirect()->back()->with('error', 'Gagal mengirim notifikasi, perikasa token anda');
        }
        // End Untuk WA Gateway

        Notification::create($requestData);

        if ($request->fromActivity) {
            return redirect()
                ->route('admin.activities.index')
                ->with('success', 'Notifikasi berhasil dikirim.');
        }

        return redirect()
            ->route('admin.notifications.index')
            ->with('success', 'Data notifikasi berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit notifikasi.
     */
    public function edit(string $id)
    {
        $data = Notification::find($id);
        $zones = Zone::get();

        return view('admin.notification.edit', [
            'data' => $data,
            'zones' => $zones
        ]);
    }

    /**
     * Perbarui data notifikasi.
     */
    public function update(Request $request, string $id)
    {
        $notification = Notification::findOrFail($id);

        $requestData = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'jenis_notification' => ['required', 'in:SEKARANG,TERJADWAL'],
            'jenis_pengiriman' => ['nullable', 'in:SEKALI,TIAP_HARI'],
            'tanggal' => ['nullable', 'date'],
            'jam' => ['nullable', 'date_format:H:i'],
        ]);

        if ($this->authData->role === "MASTER" && !$request->zone_id) {
            return redirect()->back()
                ->withErrors(['zone_id' => 'Pilih daerah terlebih dahulu.'])
                ->withInput();
        }

        $notification->update($requestData);

        return redirect()
            ->route('admin.notifications.index')
            ->with('success', 'Data notifikasi berhasil diperbarui.');
    }

    /**
     * Hapus data notifikasi.
     */
    public function destroy(string $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return redirect()
            ->route('admin.notifications.index')
            ->with('success', 'Data notifikasi berhasil dihapus.');
    }

    public function sendNotification($request)
    {
        try {
            $activity = Activity::with(['ageCategories'])->find($request->activity_id);
            $ageCategories = $activity->ageCategories->pluck('id');

            $users = User::where('role', 'USER')->where('zone_id', $request->zone_id)
                ->whereIn('age_category_id', $ageCategories)
                ->get();

            $request->validate([
                'message' => 'required',
            ]);

            $message = "*{$request->judul}*\n\n{$request->message}";

            $no_tlp_admin = Utils::phoneFormat($this->authData->no_tlp);

            if (!$no_tlp_admin) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid phone number',
                ]);
            }

            FacadesNotification::send($users, new SendWebPush($request->judul, $request->message, route('dashboard')));

            $provider = $this->authData->whatsapp_gateway_provider;
            foreach ($users as $key => $user) {
                $no_tlp = Utils::phoneFormat($user->no_tlp);

                if ($provider === "FONTEE") {
                    $config = $this->authData->whatsappGateway->config;
                    $fontee = new FonteeService();
                    $fontee->sendMessage($no_tlp, $message, $no_tlp_admin, $config);
                } else if ($provider === "WABLAS") {
                    $config = $this->authData->whatsappGateway->config;
                    $wablas = new WablasService();
                    $wablas->sendMessage($no_tlp, $message, $config);
                }
            }

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function sendRecap(Request $request)
    {
        // try {
        $request->validate([
            'activity_id' => 'required|exists:activities,id'
        ]);

        $activity = Activity::with('ageCategories')->findOrFail($request->activity_id);

        // Ambil semua age category di activity
        $ageCategoryIds = $activity->ageCategories->pluck('id');

        // Total user terdaftar di kegiatan
        $totalUsers = User::where('role', 'USER')
            ->where('zone_id', $activity->zone_id)
            ->where(function ($query) use ($ageCategoryIds, $activity) {
                $query->whereIn('age_category_id', $ageCategoryIds)
                    ->orWhereIn(
                        'status_kawin',
                        in_array("SUDAH", json_decode($activity->for_status_kawin, true)) ? ["SUDAH"] : []
                    );
            })
            ->count();

        // Total user hadir
        $totalHadir = User::where('role', 'USER')
            ->where('zone_id', $activity->zone_id)
            ->where(function ($query) use ($ageCategoryIds, $activity) {
                $query->whereIn('age_category_id', $ageCategoryIds)
                    ->orWhereIn(
                        'status_kawin',
                        in_array("SUDAH", json_decode($activity->for_status_kawin, true)) ? ["SUDAH"] : []
                    );
            })
            ->whereHas('presences', function ($query) use ($activity) {
                $query->where('activity_id', $activity->id)
                    ->where('status', 'HADIR');
            })
            ->count();

        $totalTerlambat = User::where('role', 'USER')
            ->where('zone_id', $activity->zone_id)
            ->where(function ($query) use ($ageCategoryIds, $activity) {
                $query->whereIn('age_category_id', $ageCategoryIds)
                    ->orWhereIn(
                        'status_kawin',
                        in_array("SUDAH", json_decode($activity->for_status_kawin, true)) ? ["SUDAH"] : []
                    );
            })
            ->whereHas('presences', function ($query) use ($activity) {
                $query->where('activity_id', $activity->id)
                    ->where('status', 'TERLAMBAT');
            })
            ->count();

        $totalIzin = User::where('role', 'USER')
            ->where('zone_id', $activity->zone_id)
            ->where(function ($query) use ($ageCategoryIds, $activity) {
                $query->whereIn('age_category_id', $ageCategoryIds)
                    ->orWhereIn(
                        'status_kawin',
                        in_array("SUDAH", json_decode($activity->for_status_kawin, true)) ? ["SUDAH"] : []
                    );
            })
            ->whereHas('presences', function ($query) use ($activity) {
                $query->where('activity_id', $activity->id)
                    ->where('status', 'IZIN');
            })
            ->count();

        $totalSakit = User::where('role', 'USER')
            ->where('zone_id', $activity->zone_id)
            ->where(function ($query) use ($ageCategoryIds, $activity) {
                $query->whereIn('age_category_id', $ageCategoryIds)
                    ->orWhereIn(
                        'status_kawin',
                        in_array("SUDAH", json_decode($activity->for_status_kawin, true)) ? ["SUDAH"] : []
                    );
            })
            ->whereHas('presences', function ($query) use ($activity) {
                $query->where('activity_id', $activity->id)
                    ->where('status', 'SAKIT');
            })
            ->count();

        $totalAlpha = User::where('role', 'USER')
            ->where('zone_id', $activity->zone_id)
            ->where(function ($query) use ($ageCategoryIds, $activity) {
                $query->whereIn('age_category_id', $ageCategoryIds)
                    ->orWhereIn(
                        'status_kawin',
                        in_array("SUDAH", json_decode($activity->for_status_kawin, true)) ? ["SUDAH"] : []
                    );
            })
            ->whereHas('presences', function ($query) use ($activity) {
                $query->where('activity_id', $activity->id)
                    ->where('status', 'ALPHA');
            })
            ->count();

        $totalNotPresence = User::where('role', 'USER')
            ->where('zone_id', $activity->zone_id)
            ->where(function ($query) use ($ageCategoryIds, $activity) {
                $query->whereIn('age_category_id', $ageCategoryIds)
                    ->orWhereIn(
                        'status_kawin',
                        in_array("SUDAH", json_decode($activity->for_status_kawin, true)) ? ["SUDAH"] : []
                    );
            })
            ->whereDoesntHave('presences', function ($query) use ($activity) {
                $query->where('activity_id', $activity->id);
            })
            ->count();


        // Format pesan WA
        $message = "*REKAP KEHADIRAN KEGIATAN*\n\n"
            . "*Nama Kegiatan:* {$activity->nama}\n"
            . "*Tanggal:* {$activity->tanggal}\n"
            . "*Jam:* {$activity->jam}\n\n"
            . "*Total Peserta:* {$totalUsers}\n"
            . "*Hadir:* {$totalHadir}\n"
            . "*Terlambat:* {$totalTerlambat}\n"
            . "*Izin:* {$totalIzin}\n"
            . "*Sakit:* {$totalSakit}\n"
            . "*Alpha:* " . $totalAlpha + $totalNotPresence;

        // Nomor PJ
        $noPJ = Utils::phoneFormat($activity->no_pj);

        if (!$noPJ) {
            return back()->with('error', 'Nomor PJ tidak valid');
        }

        // Kirim WA (pakai gateway yg sama)
        $provider = $this->authData->whatsapp_gateway_provider;
        $config = $this->authData->whatsappGateway->config;

        if ($provider === "FONTEE") {
            (new FonteeService())->sendMessage(
                $noPJ,
                $message,
                Utils::phoneFormat($this->authData->no_tlp),
                $config
            );
        } elseif ($provider === "WABLAS") {
            (new WablasService())->sendMessage($noPJ, $message, $config);
        }

        return back()->with('success', 'Rekap kehadiran berhasil dikirim ke PJ');

        // } catch (\Throwable $th) {
        //     return back()->with('error', 'Gagal mengirim rekap kehadiran');
        // }
    }
}
