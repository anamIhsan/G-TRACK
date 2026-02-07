<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Group;
use App\Models\Village;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GroupController extends Controller
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
        $user = $this->authData;

        $groups = Group::with(['admin', 'village', 'zone'])
            ->when($user->role === 'ADMIN_DAERAH', function ($query) use ($user) {
                $query->where('zone_id', $user->zoneAdmin->id);
            })
            ->when($user->role === 'ADMIN_DESA', function ($query) use ($user) {
                $query->where('zone_id', $user->villageAdmin->zone_id)
                    ->where('village_id', $user->villageAdmin->id);
            })
            ->latest()
            ->paginate(10);

        return view('admin.group.index', [
            'groups' => $groups
        ]);
    }

    /**
     * Tampilkan form tambah kelompok untuk desa tertentu.
     */
    public function create()
    {
        if ($this->authData->role === 'MASTER') {
            $zones = Zone::get();
            $villages = Village::get();
        } else if ($this->authData->role === 'ADMIN_DAERAH') {
            $zones = Zone::where('id', $this->authData->zoneAdmin->id)->get();
            $villages = Village::where('zone_id', $this->authData->zoneAdmin->id)->get();
        } else if ($this->authData->role === 'ADMIN_DESA') {
            $villages = Village::where('zone_id', $this->authData->villageAdmin->zone_id)->get();
            $zones = Zone::where('id', $this->authData->villageAdmin->zone_id)->get();
        } else {
            return view('pages.403');
        }

        return view('admin.group.create', [
            'villages' => $villages,
            'zones' => $zones,
            'authData' => $this->authData
        ]);
    }

    /**
     * Simpan data kelompok baru beserta user admin-nya.
     */
    public function store(Request $request)
    {
        $requestData = $request->validate([
            'nama'        => 'required|string|max:255',
            'admin_nama'  => 'required|string|max:255',
            'no_tlp'      => 'required|string|max:20',
            'password'    => 'required',
            'email'    => 'required|email|unique:users,email',
        ]);

        if ($this->authData->role === 'MASTER') {
            $request->validate([
                'zone_id' => 'required|exists:zones,id',
                'village_id' => 'required|exists:villages,id',
            ]);

            $requestData['zone_id'] = $request->zone_id;
            $requestData['village_id'] = $request->village_id;
        } elseif ($this->authData->role === 'ADMIN_DAERAH') {
            $request->validate([
                'village_id' => 'required|exists:villages,id',
            ]);

            $requestData['zone_id'] = $this->authData->zoneAdmin->id;
            $requestData['village_id'] = $request->village_id;
        } else {
            return redirect()->route('403');
        }

        // Buat username dari nama admin kelompok
        $username = strtolower(str_replace(' ', '', $requestData['admin_nama']));

        // Buat user admin kelompok
        $user = User::create([
            'username'       => $username,
            'nama'           => $requestData['admin_nama'],
            'no_tlp'         => $requestData['no_tlp'],
            'role'           => 'ADMIN_KELOMPOK',
            'email'          => $requestData["email"],
            'password'       => Hash::make($requestData["password"]),
            'hint_password'  => $requestData["password"],
        ]);

        // Simpan data kelompok
        Group::create([
            'nama'       => $requestData['nama'],
            'village_id' => $requestData['village_id'],
            'zone_id' => $requestData['zone_id'],
            'user_id'    => $user->id,
        ]);

        return redirect()
            ->route('admin.groups.index')
            ->with('success', 'Data kelompok dan admin berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit kelompok.
     */
    public function edit($id)
    {
        $data = Group::with('admin', 'village')->findOrFail($id);
        if ($this->authData->role === 'MASTER') {
            $zones = Zone::get();
            $villages = Village::get();
        } else if ($this->authData->role === 'ADMIN_DAERAH') {
            $zones = Zone::where('id', $this->authData->zoneAdmin->id)->get();
            $villages = Village::where('zone_id', $this->authData->zoneAdmin->id)->get();
        } else if ($this->authData->role === 'ADMIN_DESA') {
            $villages = Village::where('zone_id', $this->authData->villageAdmin->zone_id)->get();
            $zones = Zone::where('id', $this->authData->villageAdmin->zone_id)->get();
        } else {
            return view('pages.403');
        }

        return view('admin.group.edit', [
            'data' => $data,
            'zones' => $zones,
            'villages' => $villages,
        ]);
    }

    /**
     * Perbarui data kelompok dan admin-nya.
     */
    public function update(Request $request, $id)
    {
        $data = Group::with('admin')->findOrFail($id);

        $requestData = $request->validate([
            'nama'        => 'required|string|max:255',
            'admin_nama'  => 'required|string|max:255',
            'no_tlp'      => 'required|string|max:20',
            'password'    => 'nullable',
            'email'       => 'required|email|unique:users,email,' . $data->admin->id,
        ]);

        if ($this->authData->role === 'MASTER') {
            $request->validate([
                'zone_id' => 'required|exists:zones,id',
                'village_id' => 'required|exists:villages,id',
            ]);

            $requestData['zone_id'] = $request->zone_id;
            $requestData['village_id'] = $request->village_id;
        } elseif ($this->authData->role === 'ADMIN_DAERAH') {
            $request->validate([
                'village_id' => 'required|exists:villages,id',
            ]);

            $requestData['zone_id'] = $this->authData->zoneAdmin->id;
            $requestData['village_id'] = $request->village_id;
        } else {
            return redirect()->route('403');
        }

        $users = User::where('role', 'USER')
            ->where('group_id', $data->id)->get();

        foreach ($users as $key => $value) {
            $user = User::find($value->id);

            $user->update([
                'zone_id' => $requestData['zone_id'],
                'village_id' => $requestData['village_id'],
            ]);
        }

        $data->update($requestData);

        // Update data admin kelompok
        if ($data->admin) {
            $password = $requestData['password'] ? $requestData['password'] : $data->admin->hint_password;

            $data->admin->update([
                'nama'     => $requestData['admin_nama'],
                'no_tlp'   => $requestData['no_tlp'],
                'email'          => $requestData["email"],
                'username' => strtolower(str_replace(' ', '', $requestData['admin_nama'])),
                'password' => Hash::make($password),
                'hint_password' => $password,
            ]);
        }

        return redirect()
            ->route('admin.groups.index')
            ->with('success', 'Data kelompok dan admin berhasil diperbarui.');
    }

    /**
     * Hapus data kelompok dan user admin-nya.
     */
    public function destroy($id)
    {
        $data = Group::with('admin')->findOrFail($id);

        // Hapus admin jika ada
        if ($data->admin) {
            $data->admin->delete();
        }

        // Hapus kelompok
        $data->delete();

        return redirect()
            ->route('admin.groups.index')
            ->with('success', 'Data kelompok dan admin berhasil dihapus.');
    }
}
