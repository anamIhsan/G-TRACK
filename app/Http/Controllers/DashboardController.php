<?php

namespace App\Http\Controllers;

use App\Models\AgeCategory;
use App\Models\MetafieldLevel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /* ===============================
         * AUTH DATA
         * =============================== */
        $authData = Auth::user();

        if (!$authData) {
            abort(401);
        }

        $authData = User::with(['zoneAdmin', 'baseMetafieldUsers'])
            ->find($authData->id);

        /* ===============================
         * FILTER TANGGAL (DEFAULT HARI INI)
         * =============================== */
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::today()->startOfDay();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::today()->endOfDay();

        /* ===============================
         * USER & PIE CHART (GENDER)
         * =============================== */
        $users = User::where('role', 'user')->get();
        $usersPR = $users->where('kelamin', 'PR');
        $usersLK = $users->where('kelamin', 'LK');
        $age_categories = AgeCategory::with('users')->get();

        /* ===============================
         * KEHADIRAN (TABLE: presences)
         * =============================== */
        $presenceQuery = DB::table('presences')
            ->whereBetween('created_at', [$startDate, $endDate]);

        // ROLE USER → hanya kehadiran dirinya
        if ($authData->role === 'USER') {

            $presenceQuery->where('user_id', $authData->id);

        } else {

            // ADMIN / MASTER
            if ($authData->role !== 'MASTER') {

                $zoneId = $authData->zoneAdmin->id ?? $authData->zone_id;

                $presenceQuery->whereIn('user_id', function ($q) use ($zoneId) {
                    $q->select('id')
                        ->from('users')
                        ->where('zone_id', $zoneId);
                });
            }
        }

        $kehadiran = [
            'hadir'     => (clone $presenceQuery)->where('status', 'HADIR')->count(),
            'izin'      => (clone $presenceQuery)->where('status', 'IZIN')->count(),
            'terlambat' => (clone $presenceQuery)->where('status', 'TERLAMBAT')->count(),
            'sakit'     => (clone $presenceQuery)->where('status', 'SAKIT')->count(),
        ];

        /* ===============================
         * KEKHATAMAN
         * =============================== */
        $metafieldLevels = MetafieldLevel::latest()->limit(5)->get();
        $metafieldStats = [];

        foreach (MetafieldLevel::all() as $field) {

            $values = DB::table('levels')
                ->select('levels.user_id', DB::raw('MAX(levels.value) as max_value'))
                ->join('users', 'levels.user_id', '=', 'users.id')
                ->where('levels.metafield_level_id', $field->id)
                ->where('users.role', 'user')
                ->groupBy('levels.user_id')
                ->pluck('max_value');

            $metafieldStats[] = [
                'label' => $field->field_name,
                'stats' => [
                    '0_39'   => $values->filter(fn ($v) => $v <= 39)->count(),
                    '40_69'  => $values->filter(fn ($v) => $v >= 40 && $v <= 69)->count(),
                    '70_100' => $values->filter(fn ($v) => $v >= 70)->count(),
                ],
            ];
        }

        /* ===============================
         * RETURN VIEW
         * =============================== */
        return view('dashboard', [
            'users'           => $users,
            'usersPR'         => $usersPR,
            'usersLK'         => $usersLK,
            'age_categories'  => $age_categories,
            'metafieldLevels' => $metafieldLevels,
            'metafieldStats'  => $metafieldStats,
            'kehadiran'       => $kehadiran,
            'startDate'       => $startDate->toDateString(),
            'endDate'         => $endDate->toDateString(),
            'authData'        => $authData,
        ]);
    }
}
