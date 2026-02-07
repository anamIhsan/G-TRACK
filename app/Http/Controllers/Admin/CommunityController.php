<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
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
        $query = Community::query();

        if ($this->authData->role === 'MASTER') {
            // No filter
        } elseif ($this->authData->role === 'ADMIN_DAERAH') {
            $query->where('zone_id', $this->authData->zoneAdmin->id);
        } else {
            $query->where('zone_id', $this->authData->zone_id);
        }

        $communities = $query->latest()->paginate(10);

        return view('admin.community.index', [
            'communities' => $communities
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $zones = Zone::get();

        return view('admin.community.create', [
            'zones' => $zones
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $requestData = $request->validate([
            'nama' => 'required|string|max:255',
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

        Community::create($requestData);

        return redirect()
            ->route('admin.communities.index')
            ->with('success', 'Data komunitas berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Community::findOrFail($id);
        $zones = Zone::get();

        return view('admin.community.edit', [
            'data' => $data,
            'zones' => $zones
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = Community::findOrFail($id);
        $requestData = $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        if ($this->authData->role === 'MASTER') {
            $requestData = $request->validate([
                'zone_id' => 'required|exists:zones,id',
            ]);
            $requestData['zone_id'] = $request->zone_id;
        } elseif ($this->authData->role === 'ADMIN_DAERAH') {
            $requestData['zone_id'] = $this->authData->zoneAdmin->id;
        } else {
            return redirect()->route('403');
        }

        $data->update($requestData);

        return redirect()
            ->route('admin.communities.index')
            ->with('success', 'Data komunitas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Community::findOrFail($id);
        $data->delete();

        return redirect()
            ->route('admin.communities.index')
            ->with('success', 'Data komunitas berhasil dihapus.');
    }
}
