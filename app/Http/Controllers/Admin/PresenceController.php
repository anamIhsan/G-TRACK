<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Presence;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresenceController extends Controller
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
        $this->authData = User::with(['zoneAdmin'])->find($auth->id);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($this->authData->role === 'MASTER') {
            $activities = Activity::get();
        } elseif ($this->authData->role === 'ADMIN_DAERAH') {
            $activities = Activity::where('zone_id', $this->authData->zoneAdmin->id)->get();
        }

        $today = Carbon::today();
        $dayOfWeek = Carbon::now()->dayOfWeek;

        // $activities->where(function ($q) use ($today, $dayOfWeek) {
        //     $q->where('type', 'SEKALI')
        //         ->whereDate('tanggal', $today);
        // })->orWhere(function ($q) use ($dayOfWeek) {
        //     $q->where('type', 'TERJADWAL')
        //         ->where('hari', $dayOfWeek);
        // })->get();

        if ($request->activity_id) {
            $activity = Activity::with(["ageCategories"])
                ->where("id", $request->activity_id)
                ->first();

            $ageCategoryIds = $activity->ageCategories->pluck('id');

            if ($activity->type === "SEKALI") {
                $usersWithPresence = User::where('role', 'USER')
                    ->where('zone_id', $activity->zone_id)
                    ->where(function ($query) use ($ageCategoryIds, $activity) {
                        $query->whereIn('age_category_id', $ageCategoryIds)
                            ->orWhereIn(
                                'status_kawin',
                                in_array("SUDAH", json_decode($activity->for_status_kawin, true)) ? ["SUDAH"] : []
                            );
                    })
                    ->with(['presences' => function ($query) {
                        $query->latest();
                    }])
                    ->get();

                $usersNotPresence = User::where('role', 'USER')
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
                    ->get();

                $users = $usersWithPresence->merge($usersNotPresence)->unique('id')->values();
            } else {
                $usersWithPresence = User::where('role', 'USER')
                    ->where('zone_id', $activity->zone_id)
                    ->where(function ($query) use ($ageCategoryIds, $activity) {
                        $query->whereIn('age_category_id', $ageCategoryIds)
                            ->orWhereIn(
                                'status_kawin',
                                in_array("SUDAH", json_decode($activity->for_status_kawin, true)) ? ["SUDAH"] : []
                            );
                    })
                    ->with(['presences' => function ($query) {
                        $query->latest();
                    }])
                    ->whereHas('presences', function ($query) use ($activity) {
                        $query->where('activity_id', $activity->id)->where('created_at', '>=', today()->startOfDay());
                    })
                    ->get();

                $usersNotPresence = User::where('role', 'USER')
                    ->where('zone_id', $activity->zone_id)
                    ->where(function ($query) use ($ageCategoryIds, $activity) {
                        $query->whereIn('age_category_id', $ageCategoryIds)
                            ->orWhereIn(
                                'status_kawin',
                                in_array("SUDAH", json_decode($activity->for_status_kawin, true)) ? ["SUDAH"] : []
                            );
                    })
                    ->whereDoesntHave('presences', function ($query) use ($activity) {
                        $query->where('activity_id', $activity->id)->where('created_at', '>=', today()->startOfDay());
                    })
                    ->get();

                $users = $usersWithPresence->merge($usersNotPresence)->unique('id')->values();
            }
        } else {
            $users = [];
            $activity = null;
        }

        // $users = User::where('role', 'USER')
        //     ->with(['presences' => function ($q) {
        //         $q->latest();
        //     }])
        //     ->get();

        return view('admin.presence.index', [
            'activities' => $activities,
            'users' => $users,
            'activity' => $activity,
        ]);
    }

    public function scanner($activity_id)
    {
        // Mengambil semua aktivitas untuk dipilih di halaman scanner
        $activity = Activity::find($activity_id);
        return view('admin.presence.scanner', [
            'activity' => $activity
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * (Biasanya tidak digunakan untuk sistem scanner, biarkan kosong atau hapus jika tidak perlu)
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource (Check-in) in storage.
     * Logika utama untuk mencatat presensi dari hasil scanning.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     * (Untuk admin melihat detail satu data presensi)
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     * (Biasanya tidak digunakan untuk sistem scanner, biarkan kosong atau hapus jika tidak perlu)
     */
    public function edit(Request $request, $id) {}

    /**
     * Update the specified resource in storage.
     * (Untuk admin melakukan koreksi data presensi, misalnya mengubah status atau waktu)
     */
    public function update(Request $request, $activity_id)
    {
        foreach ($request->status as $userId => $status) {
            $activity = Activity::find($activity_id);

            $user = User::with(['presences' => function ($query) {
                $query->latest();
            }])
                ->where('id', $userId)
                ->first();

            if ($activity->type == "SEKALI") {
                $presence = $user->presences
                    ->where("activity_id", $activity->id)
                    ->sortByDesc("created_at")
                    ->first();
            } else {
                $presence = $user->presences
                    ->where("activity_id", $activity->id)
                    ->where("created_at", ">=", today()->startOfDay())
                    ->first();
            }

            if ($activity->type === "TERJADWAL") {
                $dateNow = Carbon::now()->dayOfWeekIso;

                if ($dateNow == $activity->hari) {
                    if (isset($user) && isset($presence) && $presence->id) {
                        $presence = Presence::find($presence->id);

                        $presence->update([
                            'status' => $status,
                            'jam_datang' => $request->jam_datang[$userId] ?? null,
                            'keterangan' => $request->keterangan[$userId] ?? null
                        ]);
                    } else {
                        $presence = Presence::create([
                            'user_id' => $user->id,
                            'activity_id' => $activity->id,
                            'jam_datang' => $request->jam_datang[$userId] ?? null,
                            'status' => $status,
                            'keterangan' => $request->keterangan[$userId] ?? null
                        ]);
                    }
                } else {
                    return redirect()->route('admin.presences.index', ['activity_id' => $activity_id])->with('error', 'Presensi hanya dapat dilakukan pada hari ' . $activity->nama_hari . ' !');
                }
            } else {
                if (isset($user) && isset($presence) && $presence->id) {
                    $presence = Presence::find($presence->id);

                    $presence->update([
                        'status' => $status,
                        'jam_datang' => $request->jam_datang[$userId] ?? null,
                        'keterangan' => $request->keterangan[$userId] ?? null
                    ]);
                } else {
                    $presence = Presence::create([
                        'user_id' => $user->id,
                        'activity_id' => $activity->id,
                        'jam_datang' => $request->jam_datang[$userId] ?? null,
                        'status' => $status,
                        'keterangan' => $request->keterangan[$userId] ?? null
                    ]);
                }
            }
        }

        return redirect()->route('admin.presences.index', ['activity_id' => $activity_id]);
    }


    /**
     * Remove the specified resource from storage.
     * (Untuk admin menghapus data presensi yang salah)
     */
    public function destroy(string $id) {}

    public function history(Request $request, $activity_id)
    {
        $filter_tanggal = $request->tanggal_presensi ?? null;
        $filter_tanggal_format = $filter_tanggal
            ? Carbon::parse($filter_tanggal)
            : null;

        $today = now()->startOfDay();

        $isPast = $filter_tanggal_format
            ? $filter_tanggal_format->lt($today)
            : false;

        $isToday = $filter_tanggal_format
            ? $filter_tanggal_format->isSameDay($today)
            : true; // default = hari ini

        $isFuture = $filter_tanggal_format
            ? $filter_tanggal_format->gt($today)
            : false;


        $activity = Activity::with('ageCategories')->findOrFail($activity_id);
        $ageCategoryIds = $activity->ageCategories->pluck('id');

        $presenceByUser = Presence::with('user')
            ->where('activity_id', $activity->id)
            ->when($filter_tanggal_format, function ($q) use ($filter_tanggal_format) {
                $q->whereDate('created_at', $filter_tanggal_format->toDateString());
            })
            ->get()
            ->groupBy('user_id');


        $shouldLoadCurrentUsers = $isToday || $isFuture;

        $currentUsers = collect();

        if ($shouldLoadCurrentUsers) {
            $currentUsers = User::where('role', 'USER')
                ->where('zone_id', $activity->zone_id)
                ->where(function ($query) use ($ageCategoryIds, $activity) {
                    $query->whereIn('age_category_id', $ageCategoryIds)
                        ->orWhereIn(
                            'status_kawin',
                            in_array(
                                'SUDAH',
                                json_decode($activity->for_status_kawin, true) ?? []
                            ) ? ['SUDAH'] : []
                        );
                })
                ->get();
        }

        $users = collect();

        foreach ($presenceByUser as $userId => $presences) {
            $presence = $presences->sortByDesc('created_at')->first();

            $users->put($userId, (object) [
                'id'             => $userId,
                'nama'           => $presence->user?->nama ?? '[User lama]',
                'presence_today' => $presence,
            ]);
        }

        if ($shouldLoadCurrentUsers) {
            foreach ($currentUsers as $user) {
                if (! $users->has($user->id)) {
                    $users->put($user->id, (object) [
                        'id'             => $user->id,
                        'nama'           => $user->nama,
                        'presence_today' => null,
                    ]);
                }
            }
        }


        $colors = [
            'HADIR'     => 'bg-green-400 text-green-800',
            'TERLAMBAT' => 'bg-green-200 text-green-800',
            'IZIN'      => 'bg-orange-200 text-orange-800',
            'SAKIT'     => 'bg-yellow-200 text-yellow-800',
            'ALPHA'     => 'bg-red-200 text-red-800',
        ];

        $users = $users->values()->map(function ($user) use ($colors) {
            $status = $user->presence_today?->status ?? 'ALPHA';

            $user->presence_status = $status;
            $user->presence_color  = $colors[$status] ?? 'bg-gray-200 text-gray-800';

            return $user;
        });

        return view('admin.presence.history', compact(
            'activity',
            'filter_tanggal',
            'filter_tanggal_format',
            'users'
        ));
    }
}
