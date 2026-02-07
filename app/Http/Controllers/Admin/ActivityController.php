<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\AgeCategory;
use App\Models\Group;
use App\Models\User;
use App\Models\Village;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
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
        $this->authData = User::with(['zoneAdmin', 'villageAdmin', 'groupAdmin'])->find($auth->id);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Activity::query();

        if ($this->authData->role === "MASTER") {
            // No filter
        } elseif ($this->authData->role === "ADMIN_DAERAH") {
            $query->where('zone_id', $this->authData->zoneAdmin->id);
        } elseif ($this->authData->role === 'ADMIN_DESA') {
            $query->where('zone_id', $this->authData->villageAdmin->zone_id)
                ->where('village_id', $this->authData->villageAdmin->id);
        } elseif ($this->authData->role === 'ADMIN_KELOMPOK') {
            $query->where('zone_id', $this->authData->groupAdmin->zone_id)
                ->where('village_id', $this->authData->groupAdmin->village_id)
                ->where('group_id', $this->authData->groupAdmin->id);
        }

        $activities = $query->latest()->paginate(10);

        return view('admin.activity.index', [
            'activities' => $activities,
            'age_categories' => AgeCategory::get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $age_categories = AgeCategory::get();
        $zones = Zone::get();
        $villages = Village::get();
        $groups = Group::get();

        return view('admin.activity.create', [
            'age_categories' => $age_categories,
            'zones' => $zones,
            'villages' => $villages,
            'groups' => $groups,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $requestData = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal' => 'nullable|date|required_if:type,SEKALI',
            'hari' => 'nullable|integer|required_if:type,TERJADWAL',
            'jam' => 'required|date_format:H:i',
            'materi' => 'required|string|max:255',
            'tempat' => 'required|string|max:255',
            'type' => 'required|in:SEKALI,TERJADWAL',
            'for_status_kawin*' => 'required|array',
            'no_pj' => 'required',
        ]);

        if ($this->authData->role === 'MASTER') {
            $request->validate([
                'zone_id' => 'required|exists:zones,id',
                'village_id' => 'required|exists:villages,id',
                'group_id' => 'required|exists:groups,id',
            ]);

            $requestData['zone_id'] = $request->zone_id;
            $requestData['village_id'] = $request->village_id;
            $requestData['group_id'] = $request->group_id;
        } elseif ($this->authData->role === 'ADMIN_DAERAH') {
            $request->validate([
                'village_id' => 'required|exists:villages,id',
                'group_id' => 'required|exists:groups,id',
            ]);

            $requestData['zone_id'] = $this->authData->zoneAdmin->id;
            $requestData['village_id'] = $request->village_id;
            $requestData['group_id'] = $request->group_id;
        } elseif ($this->authData->role === 'ADMIN_DESA') {
            $request->validate([
                'group_id' => 'required|exists:groups,id',
            ]);

            $requestData['zone_id'] = $this->authData->villageAdmin->zone->id;
            $requestData['village_id'] = $this->authData->villageAdmin->id;
            $requestData['group_id'] = $request->group_id;
        } elseif ($this->authData->role === 'ADMIN_KELOMPOK') {
            $requestData['zone_id'] = $this->authData->groupAdmin->zone->id;
            $requestData['village_id'] = $this->authData->groupAdmin->village->id;
            $requestData['group_id'] = $this->authData->groupAdmin->id;
        } else {
            return redirect()->route('403');
        }

        $requestData['for_status_kawin'] = json_encode($request->for_status_kawin);

        $activity = Activity::create($requestData);

        if (in_array('BELUM', $request->for_status_kawin)) {
            $activity->ageCategories()->sync($request->age_category_ids ?? []);
        }

        return redirect()
            ->route('admin.activities.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $activity = Activity::find($id);
        $age_categories = AgeCategory::get();
        $data = Activity::find($id);
        $zones = Zone::get();
        $villages = Village::get();
        $groups = Group::get();

        if (!$data) {
            return redirect()
                ->route('admin.activities.index')
                ->with('error', 'Data kegiatan tidak ditemukan.');
        }

        return view('admin.activity.edit', [
            'age_categories' => $age_categories,
            'data' => $data,
            'zones' => $zones,
            'villages' => $villages,
            'groups' => $groups,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return redirect()
                ->route('admin.activities.index')
                ->with('error', 'Data kegiatan tidak ditemukan.');
        }

        $request->merge([
            'jam' => substr($request->jam, 0, 5) // ambil hanya jam & menit
        ]);

        $requestData = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal' => 'nullable|date|required_if:type,SEKALI',
            'hari' => 'nullable|integer|required_if:type,TERJADWAL',
            'jam' => 'required|date_format:H:i',
            'materi' => 'required|string|max:255',
            'tempat' => 'required|string|max:255',
            'type' => 'required|in:SEKALI,TERJADWAL',
            'for_status_kawin*' => 'required|array',
            'no_pj' => 'required',
        ]);

        if ($this->authData->role === 'MASTER') {
            $request->validate([
                'zone_id' => 'required|exists:zones,id',
                'village_id' => 'required|exists:villages,id',
                'group_id' => 'required|exists:groups,id',
            ]);

            $requestData['zone_id'] = $request->zone_id;
            $requestData['village_id'] = $request->village_id;
            $requestData['group_id'] = $request->group_id;
        } elseif ($this->authData->role === 'ADMIN_DAERAH') {
            $request->validate([
                'village_id' => 'required|exists:villages,id',
                'group_id' => 'required|exists:groups,id',
            ]);

            $requestData['zone_id'] = $this->authData->zoneAdmin->id;
            $requestData['village_id'] = $request->village_id;
            $requestData['group_id'] = $request->group_id;
        } elseif ($this->authData->role === 'ADMIN_DESA') {
            $request->validate([
                'group_id' => 'required|exists:groups,id',
            ]);

            $requestData['zone_id'] = $this->authData->villageAdmin->zone->id;
            $requestData['village_id'] = $this->authData->villageAdmin->id;
            $requestData['group_id'] = $request->group_id;
        } elseif ($this->authData->role === 'ADMIN_KELOMPOK') {
            $requestData['zone_id'] = $this->authData->groupAdmin->zone->id;
            $requestData['village_id'] = $this->authData->groupAdmin->village->id;
            $requestData['group_id'] = $this->authData->groupAdmin->id;
        } else {
            return redirect()->route('403');
        }

        if ($request->type === 'SEKALI') {
            $requestData['hari'] = null;
        } else {
            $requestData['tanggal'] = null;
        }

        $requestData['for_status_kawin'] = $request->for_status_kawin;

        $activity->update($requestData);

        if ($request->for_status_kawin[0] === 'BELUM') {
            $activity->ageCategories()->sync($request->age_category_ids ?? []);
        } else {
            $activity->ageCategories()->detach();
        }

        return redirect()
            ->route('admin.activities.index')
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return redirect()
                ->route('admin.activities.index')
                ->with('error', 'Data kegiatan tidak ditemukan.');
        }

        $activity->ageCategories()->detach();
        $activity->delete();

        return redirect()
            ->route('admin.activities.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }
}
