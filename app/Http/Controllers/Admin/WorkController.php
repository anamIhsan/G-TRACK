<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Work;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkController extends Controller
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

    public function index()
    {
        $query = Work::query();

        if ($this->authData->role === 'MASTER') {
        } elseif ($this->authData->role === 'ADMIN_DAERAH') {
            $query->where('zone_id', $this->authData->zoneAdmin->id);
        } else {
            return view('pages.403');
        }

        $works = $query->latest()->paginate(10);

        return view('admin.work.index', compact('works'));
    }

    public function create()
    {
        $zones = Zone::get();

        return view('admin.work.create', [
            'zones' => $zones
        ]);
    }

    public function store(Request $request)
    {
        $requestData = $request->validate([
            'nama' => 'required|string|max:255',
            'zone_id' => 'required|exists:zones,id',
        ]);

        if ($this->authData->role === 'MASTER') {
            $requestData['zone_id'] = $request->zone_id;
        } elseif ($this->authData->role === 'ADMIN_DAERAH') {
            $requestData['zone_id'] = $this->authData->zoneAdmin->id;
        } else {
            return redirect()->route('403');
        }

        Work::create($requestData);

        return redirect()
            ->route('admin.works.index')
            ->with('success', 'Data pekerjaan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = Work::findOrFail($id);
        $zones = Zone::get();

        return view('admin.work.edit', [
            'data' => $data,
            'zones' => $zones
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = Work::findOrFail($id);
        $requestData = $request->validate([
            'nama' => 'required|string|max:255',
            'zone_id' => 'required',
        ]);

        $data->update($requestData);

        return redirect()
            ->route('admin.works.index')
            ->with('success', 'Data pekerjaan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $data = Work::findOrFail($id);
        $data->delete();

        return redirect()
            ->route('admin.works.index')
            ->with('success', 'Data pekerjaan berhasil dihapus.');
    }
}
