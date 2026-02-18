<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\AgeCategory;
use App\Models\User;
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
        $this->authData = User::with(['zoneAdmin'])->find($auth->id);
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

        return view('admin.activity.create', [
            'age_categories' => $age_categories,
            'zones' => $zones,
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
            ]);

            $requestData['zone_id'] = $request->zone_id;
        } elseif ($this->authData->role === 'ADMIN_DAERAH') {

            $requestData['zone_id'] = $this->authData->zoneAdmin->id;
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

        if (!$data) {
            return redirect()
                ->route('admin.activities.index')
                ->with('error', 'Data kegiatan tidak ditemukan.');
        }

        return view('admin.activity.edit', [
            'age_categories' => $age_categories,
            'data' => $data,
            'zones' => $zones,
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
            ]);

            $requestData['zone_id'] = $request->zone_id;
        } elseif ($this->authData->role === 'ADMIN_DAERAH') {

            $requestData['zone_id'] = $this->authData->zoneAdmin->id;
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
