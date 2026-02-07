<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Group;
use App\Models\User;
use App\Models\Village;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VillageController extends Controller
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
     * Tampilkan daftar desa.
     */
    public function index()
    {
        $query = Village::query();

        if ($this->authData->role === 'MASTER') {
            // No filter
        } elseif ($this->authData->role === 'ADMIN_DAERAH') {
            $query->where('zone_id', $this->authData->zoneAdmin->id);
        } else {
            $query->where('zone_id', $this->authData->zone_id);
        }

        $villages = $query->latest()->paginate(10);

        return view('admin.village.index', [
            'villages' => $villages
        ]);
    }

    /**
     * Tampilkan form tambah desa.
     */
    public function create()
    {
        if ($this->authData->role === 'MASTER') {
            $zones = Zone::get();
        } else if ($this->authData->role === 'ADMIN_DAERAH') {
            $zones = Zone::where('id', $this->authData->zoneAdmin->id)->get();
        } else {
            return view('pages.403');
        }

        return view('admin.village.create', [
            'zones' => $zones
        ]);
    }

    /**
     * Simpan data desa baru beserta user admin.
     */
    public function store(Request $request)
    {
        $requestData = $request->validate([
            'nama'        => 'required|string|max:255',
            'admin_nama'  => 'nullable|string|max:255',
            'no_tlp'       => 'nullable|string|max:20',
            'password'       => 'required',
            'email'       => 'required|email|unique:users,email',
        ]);

        if ($requestData['admin_nama'] && $requestData['no_tlp']) {
            $username = strtolower(str_replace(' ', '', $requestData['admin_nama']));

            $user = User::create([
                'username' => $username,
                'nama'     => $requestData['admin_nama'],
                'no_tlp'    => $requestData['no_tlp'],
                'role'     => 'ADMIN_DESA',
                'email'     => $requestData['email'],
                'password'       => Hash::make($requestData['password']),
                'hint_password'  => $requestData['password'],
            ]);
        }

        if ($this->authData->role === 'MASTER') {
            $requestData['zone_id'] = $request->zone_id;
        } elseif ($this->authData->role === 'ADMIN_DAERAH') {
            $requestData['zone_id'] = $this->authData->zoneAdmin->id;
        } else {
            return redirect()->route('403');
        }

        Village::create([
            'nama'    => $requestData['nama'],
            'user_id' => $user->id ?? null,
            'zone_id' => $requestData['zone_id'],
        ]);

        return redirect()
            ->route('admin.villages.index')
            ->with('success', 'Data desa dan admin berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit desa.
     */
    public function edit($id)
    {
        $data = Village::with('admin')->findOrFail($id);
        if ($this->authData->role === 'MASTER') {
            $zones = Zone::get();
        } else if ($this->authData->role === 'ADMIN_DAERAH') {
            $zones = Zone::where('id', $this->authData->zoneAdmin->id)->get();
        } else {
            return view('pages.403');
        }
        return view('admin.village.edit', [
            'data' => $data,
            'zones' => $zones
        ]);
    }

    /**
     * Perbarui data desa dan user admin.
     */
    public function update(Request $request, $id)
    {
        $data = Village::with('admin')->findOrFail($id);

        $requestData = $request->validate([
            'nama'        => 'required|string|max:255',
            'admin_nama'  => 'nullable|string|max:255',
            'no_tlp'      => 'nullable|string|max:20|required_with:admin_nama',
            'password'    => 'nullable',
            'email'    => 'required|email|unique:users,email,' . $data->admin->id,
            'zone_id'    => 'required|exists:zones,id',
        ]);

        DB::transaction(function () use ($id, $request, $data, $requestData) {
            User::where('role', 'USER')
                ->where('village_id', $id)
                ->update([
                    'zone_id' => $request->zone_id
                ]);

            Group::where('village_id', $id)
                ->update([
                    'zone_id' => $request->zone_id
                ]);

            Activity::where('village_id', $id)
                ->update([
                    'zone_id' => $request->zone_id
                ]);

            $data->update($requestData);
        });


        if ($requestData['admin_nama'] && $requestData['no_tlp']) {
            if ($data->admin) {
                $password = $requestData['password'] ? $requestData['password'] : $data->admin->hint_password;

                $data->admin->update([
                    'nama'   => $requestData['admin_nama'],
                    'no_tlp' => $requestData['no_tlp'],
                    'username' => strtolower(str_replace(' ', '', $requestData['admin_nama'])),
                    'email'     => $requestData['email'],
                    'password' => Hash::make($password),
                    'hint_password' => $password,
                ]);
            } else {
                $username = strtolower(str_replace(' ', '', $requestData['admin_nama']));

                $request->validate([
                    'password'    => 'required'
                ]);

                User::create([
                    'username' => $username,
                    'nama'     => $requestData['admin_nama'],
                    'no_tlp'    => $requestData['no_tlp'],
                    'role'     => 'ADMIN_DESA',
                    'email'     => $requestData['email'],
                    'password'       => Hash::make($requestData['password']),
                    'hint_password'  => $requestData['password'],
                ]);
            }
        }

        return redirect()
            ->route('admin.villages.index')
            ->with('success', 'Data desa dan admin berhasil diperbarui.');
    }

    /**
     * Hapus data desa dan user admin.
     */
    public function destroy($id)
    {
        $village = Village::with(['admin', 'groups.admin'])->findOrFail($id);

        // Hapus semua kelompok dan admin-nya
        foreach ($village->groups as $group) {
            if ($group->admin) {
                $group->admin->delete(); // hapus user admin kelompok
            }
            $group->delete(); // hapus kelompok
        }

        // Hapus user admin desa
        if ($village->admin) {
            $village->admin->delete();
        }

        // Hapus desa
        $village->delete();

        return redirect()
            ->route('admin.villages.index')
            ->with('success', 'Data desa, kelompok, dan semua admin terkait berhasil dihapus.');
    }
}
