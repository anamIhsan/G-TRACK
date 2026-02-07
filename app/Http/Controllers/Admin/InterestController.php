<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterestController extends Controller
{
    public $auth;
    public $authData;

    public function __construct()
    {
        $auth = Auth::user();
        $this->auth = $auth;
        $this->authData = User::with(['zoneAdmin'])->find($auth->id);
    }

    /**
     * List Data Minat
     */
    public function index()
    {
        $query = Interest::query();

        if ($this->authData->role === 'MASTER') {
            // No filter
        } elseif ($this->authData->role === 'ADMIN_DAERAH') {
            $query->where('zone_id', $this->authData->zoneAdmin->id);
        } else {
            $query->where('zone_id', $this->authData->zone_id);
        }

        $interests = $query->latest()->paginate(10);

        return view('admin.interest.index', compact('interests'));
    }

    /**
     * Form Tambah Minat
     */
    public function create()
    {
        $zones = Zone::get();

        // 🔥 suggestion minat dari database
        $existingInterests = Interest::select('nama')
            ->distinct()
            ->orderBy('nama')
            ->pluck('nama');

        return view('admin.interest.create', compact(
            'zones',
            'existingInterests'
        ));
    }

    /**
     * Simpan Data Minat (MULTIPLE)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required|array',
            'nama.*'     => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'zone_id'    => 'nullable|exists:zones,id',
        ]);

        if ($this->authData->role === 'MASTER') {
            $zoneId = $request->zone_id;
        } elseif ($this->authData->role === 'ADMIN_DAERAH') {
            $zoneId = $this->authData->zoneAdmin->id;
        } else {
            return redirect()->route('403');
        }

        foreach ($request->nama as $namaMinat) {
            Interest::firstOrCreate(
                [
                    'nama'    => $namaMinat,
                    'zone_id' => $zoneId,
                ],
                [
                    'keterangan' => $request->keterangan,
                ]
            );
        }

        return redirect()
            ->route('admin.interests.index')
            ->with('success', 'Data minat berhasil ditambahkan.');
    }

    /**
     * Detail Minat
     */
    public function show($id)
    {
        $interest = Interest::with('subInterests')->findOrFail($id);

        if (
            $this->authData->role !== 'MASTER' &&
            $interest->zone_id !== ($this->authData->zoneAdmin->id ?? $this->authData->zone_id)
        ) {
            return redirect()->route('403');
        }

        return view('admin.interest.show', compact('interest'));
    }

    public function edit($id)
    {
        $data = Interest::findOrFail($id);
        $zones = Zone::get();

        return view('admin.interest.edit', [
            'data' => $data,
            'zones' => $zones
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = Interest::findOrFail($id);

        $requestData = $request->validate([
            'nama' => 'required|string|max:255',
            'zone_id' => 'required',
        ]);

        $data->update($requestData);
        //
        return redirect()
            ->route('admin.interests.index')
            ->with('success', 'Data minat berhasil diperbarui.');
    }

    /**
     * Hapus Minat
     */
    public function destroy($id)
    {
        Interest::findOrFail($id)->delete();

        return redirect()
            ->route('admin.interests.index')
            ->with('success', 'Data minat berhasil dihapus.');
    }
}
