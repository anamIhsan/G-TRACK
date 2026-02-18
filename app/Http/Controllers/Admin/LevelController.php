<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgeCategory;
use App\Models\Level;
use App\Models\MetafieldLevel;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LevelController extends Controller
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
        $query = User::query()
            ->where('role', 'USER')
            ->when($request->age_category_id, fn($q, $f) => $q->where('age_category_id', $f));

        if ($this->authData->role === "MASTER") {
            // No extra filter
        } elseif ($this->authData->role === "ADMIN_DAERAH") {
            $query->where('zone_id', $this->authData->zoneAdmin->id);
        } else {
            $query->where('zone_id', $this->authData->zone_id);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        $metafieldLevels = MetafieldLevel::get();
        $age_categories = AgeCategory::get();
        $zones = Zone::get();
        $levels = Level::get();

        $age_category_nama = $request->age_category_id
            ? AgeCategory::find($request->age_category_id)?->nama
            : null;

        return view('admin.level.index', [
            'users' => $users,
            'age_categories' => $age_categories,
            'zones' => $zones,
            'levels' => $levels,
            'metafieldLevels' => $metafieldLevels,
            'filters' => $request->all(),
            'age_category_nama' => $age_category_nama,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = User::where("id", $id)->with(['level'])->first();
        $metafieldLevels = MetafieldLevel::where('zone_id', $data->zone_id)->get();

        return view('admin.level.edit', [
            'data' => $data,
            'metafieldLevels' => $metafieldLevels,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::with(['level'])->find($id);

        $metafieldLevelId = $request->metafield_level_id;
        $metafieldHalaman = $request->halaman;

        $request->validate([
            'metafield_level_id' => 'required|array',
        ], [
            'metafield_level_id.required' => 'Data tingkatan materi diisi.',
        ]);

        foreach ($metafieldLevelId as $key => $value) {
            $data = [
                'value' => $value ? ($value > 100 ? 100 : $value) : 0,
                'user_id' => $user->id,
                'metafield_level_id' => $key,
                'status' => 'ACCEPTED'
            ];

            $metafieldLevel = MetafieldLevel::find($key);

            if ($metafieldLevel->zone_id !== $user->zone_id) {
                continue;
            }

            if (isset($metafieldHalaman[$key]) && $metafieldHalaman[$key]) {
                $data['halaman'] = $metafieldHalaman[$key];
            }

            Level::updateOrCreate(
                ['user_id' => $id, 'metafield_level_id' => $key],
                $data
            );
        }

        return redirect()
            ->route('admin.levels.index')
            ->with('success', 'Data level berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
