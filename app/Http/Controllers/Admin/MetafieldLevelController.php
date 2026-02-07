<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\MetafieldLevel;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MetafieldLevelController extends Controller
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
        $query = MetafieldLevel::query();

        if ($this->authData->role === 'MASTER') {
            // No filter
        } elseif ($this->authData->role === 'ADMIN_DAERAH') {
            $query->where('zone_id', $this->authData->zoneAdmin->id);
        } else {
            abort(403);
        }

        $metafieldLevels = $query->latest()->paginate(10);

        return view('admin.level.metafield_level.index', [
            'metafieldLevels' => $metafieldLevels
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $userId = $request->query('user');
        $zones = Zone::get();

        return view('admin.level.metafield_level.create', [
            'userId' => $userId,
            'zones' => $zones
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $requestData = $request->validate([
            'field_name' => 'required|string|max:255',
            'halaman' => 'nullable|integer',
            'zone_id' => 'required|exists:zones,id',
        ]);

        MetafieldLevel::create($requestData);

        if ($request->query('user')) {
            return redirect()->route('admin.levels.edit', $request->query('user'));
        }

        return redirect()->route('admin.metafield_level.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = MetafieldLevel::findOrFail($id);
        $zones = Zone::get();

        return view('admin.level.metafield_level.edit', [
            'data' => $data,
            'zones' => $zones
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $metafieldLevel = MetafieldLevel::findOrFail($id);

        $requestData = $request->validate([
            'field_name' => 'required|string|max:255',
            'halaman' => 'nullable|integer',
            'zone_id' => 'required|exists:zones,id',
        ]);

        $metafieldLevel->update($requestData);

        return redirect()->route('admin.metafield_level.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $metafieldLevel = MetafieldLevel::findOrFail($id);

        $metafieldLevel->delete();

        return redirect()->route('admin.metafield_level.index');
    }
}
