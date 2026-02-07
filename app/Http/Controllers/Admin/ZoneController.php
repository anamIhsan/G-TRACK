<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ZoneController extends Controller
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
        $zones = Zone::latest()->paginate(10);

        return view('admin.zone.index', [
            'zones' => $zones
        ]);
    }

    public function create()
    {
        return view('admin.zone.create');
    }

    public function store(Request $request)
    {
        $requestData = $request->validate([
            'nama'        => 'required|string|max:255',
            'admin_nama'  => 'nullable|string|max:255',
            'no_tlp'       => 'nullable|string|max:20|required_with:admin_nama',
            'password'  => 'required',
            'email'     => 'required|email|unique:users,email',
        ]);

        if ($requestData['admin_nama'] && $requestData['no_tlp']) {
            $username = strtolower(str_replace(' ', '', $requestData['admin_nama']));

            $user = User::create([
                'username' => $username,
                'nama'     => $requestData['admin_nama'],
                'no_tlp'    => $requestData['no_tlp'],
                'role'     => 'ADMIN_DAERAH',
                'email'     => $requestData['email'],
                'password'       => Hash::make($requestData["password"]),
                'hint_password'  => $requestData["password"],
            ]);
        }

        if ($this->authData->role === 'MASTER') {
            $requestData['zone_id'] = $request->zone_id;
        } elseif ($this->authData->role === 'ADMIN_DAERAH') {
            $requestData['zone_id'] = $this->authData->zoneAdmin->id;
        } else {
            return redirect()->route('403');
        }

        Zone::create([
            'nama' => $requestData['nama'],
            'user_id' => $user->id ?? null
        ]);

        return redirect()
            ->route('admin.zones.index')
            ->with('success', 'Data daerah berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = Zone::with('admin')->findOrFail($id);

        return view('admin.zone.edit', [
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = Zone::with('admin')->findOrFail($id);

        $requestData = $request->validate([
            'nama'        => 'required|string|max:255',
            'admin_nama'  => 'nullable|string|max:255',
            'no_tlp'      => 'nullable|string|max:20|required_with:admin_nama',
            'password'  => 'nullable',
            'email'  => 'required|email|unique:users,email,' . $data->admin->id,
        ]);

        $data->update([
            'nama' => $requestData['nama'],
        ]);

        if ($requestData['admin_nama'] && $requestData['no_tlp']) {
            if ($data->admin) {
                if ($requestData['password']) {
                    $data->admin->update([
                        'password' => Hash::make($requestData['password']),
                        'hint_password' => $requestData['password']
                    ]);
                }

                $data->admin->update([
                    'nama'   => $requestData['admin_nama'],
                    'no_tlp' => $requestData['no_tlp'],
                    'email' => $requestData['email'],
                    'username' => strtolower(str_replace(' ', '', $requestData['admin_nama'])),
                ]);
            } else {
                $username = strtolower(str_replace(' ', '', $requestData['admin_nama']));

                $request->validate([
                    'password'  => 'required',
                ]);

                User::create([
                    'username' => $username,
                    'nama'     => $requestData['admin_nama'],
                    'no_tlp'    => $requestData['no_tlp'],
                    'role'     => 'ADMIN_DESA',
                    'email'     => $requestData['email'],
                    'password'       => Hash::make($request->password),
                    'hint_password'  => $request->password,
                ]);
            }
        }

        return redirect()
            ->route('admin.zones.index')
            ->with('success', 'Data daerah berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $zone = Zone::findOrFail($id);

        foreach ($zone->villages as $village) {
            foreach ($village->groups as $group) {
                if ($group->admin) {
                    $group->admin()->delete();
                }
                $group->delete();
            }

            if ($village->admin) {
                $village->admin()->delete();
            }
            $village->delete();
        }

        if ($zone->admin) {
            $zone->admin()->delete();
        }

        $zone->delete();

        return redirect()
            ->route('admin.zones.index')
            ->with('success', 'Data daerah berhasil dihapus.');
    }
}
