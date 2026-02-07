<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use App\Models\Village;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        $zones = Zone::get();
        $villages = Village::get();
        $groups = Group::get();

        return view('pages.signin', [
            'zones' => $zones,
            'villages' => $villages,
            'groups' => $groups,
        ]);
    }

    public function showLoginUser()
    {
        return view('user.signin');
    }

    public function loginUser(Request $request)
    {
        $credentials = $request->validate([
            'password' => 'required',
        ]);

        if ($request->no_tlp) {
            $credentials['identity'] = $request->no_tlp;
            $request->validate([
                'no_tlp' => 'required',
            ]);
        } else {
            $credentials['identity'] = $request->email;
            $request->validate([
                'email' => 'required',
            ]);
        }

        $user = User::onlyTrashed()
            ->where(function ($query) use ($credentials) {
                $query->where('no_tlp', $credentials['identity'])
                    ->orWhere('email', $credentials['identity']);
            })
            ->where('role', 'USER')
            ->first();

        if ($user) {
            return back()->withErrors([
                'identity' => 'Akun anda telah dihapus.',
            ])->withInput($credentials);
        }

        $user = User::where(function ($query) use ($credentials) {
            $query->where('no_tlp', $credentials['identity'])
                ->orWhere('email', $credentials['identity']);
        })
            ->where('role', 'USER')
            ->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'identity' => 'Data tidak cocok.',
        ])->withInput($credentials);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'password' => 'required',
            'level' => 'required|in:MASTER,ADMIN_DAERAH,ADMIN_DESA,ADMIN_KELOMPOK,USER',
            'zone_id' => 'nullable|exists:zones,id|required_if:level,ADMIN_DAERAH,ADMIN_DESA,ADMIN_KELOMPOK',
            'village_id' => 'nullable|exists:villages,id|required_if:level,ADMIN_DESA,ADMIN_KELOMPOK',
            'group_id' => 'nullable|exists:groups,id|required_if:level,ADMIN_KELOMPOK',
            'no_tlp' => 'nullable',
            'email' => 'nullable',
        ]);

        if ($credentials['level'] === 'USER') {
            if ($request->no_tlp) {
                $credentials['identity'] = $request->no_tlp;
                $request->validate([
                    'no_tlp' => 'required',
                ]);
            } else {
                $credentials['identity'] = $request->email;
                $request->validate([
                    'email' => 'required',
                ]);
            }

            $user = User::onlyTrashed()
                ->where(function ($query) use ($credentials) {
                    $query->where('no_tlp', $credentials['identity'])
                        ->orWhere('email', $credentials['identity']);
                })
                ->where('role', 'USER')
                ->first();

            if ($user) {
                return back()->withErrors([
                    'identity' => 'Akun anda telah dihapus.',
                ])->withInput($credentials);
            }

            $user = User::where(function ($query) use ($credentials) {
                $query->where('no_tlp', $credentials['identity'])
                    ->orWhere('email', $credentials['identity']);
            })
                ->where('role', 'USER')
                ->first();
        } elseif ($credentials['level'] === 'MASTER') {
            $user = User::where('role', $credentials['level'])
                ->first();
        } elseif ($credentials['level'] === 'ADMIN_DAERAH') {
            $user = User::whereHas('zoneAdmin', function ($query) use ($credentials) {
                $query->where('id', $credentials['zone_id']);
            })->where('role', $credentials['level'])
                ->first();
        } elseif ($credentials['level'] === 'ADMIN_DESA') {
            $user = User::whereHas('villageAdmin', function ($query) use ($credentials) {
                $query->where('id', $credentials['village_id'])->where('zone_id', $credentials['zone_id']);
            })->where('role', $credentials['level'])->first();
        } elseif ($credentials['level'] === 'ADMIN_KELOMPOK') {
            $user = User::whereHas('groupAdmin', function ($query) use ($credentials) {
                $query->where('id', $credentials['group_id'])->where('zone_id', $credentials['zone_id'])->where('village_id', $credentials['village_id']);
            })->where('role', $credentials['level'])->first();
        }

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'level' => 'Data tidak ada yang cocok.',
        ])->withInput($credentials);
    }

    // public function showRegister()
    // {
    //     return view('auth.register');
    // }

    // public function register(Request $request)
    // {
    //     $data = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users',
    //         'password' => 'required|min:6|confirmed',
    //         'level' => 'required',
    //         'daerah' => 'required',
    //     ]);

    //     $data['password'] = Hash::make($data['password']);

    //     User::create($data);

    //     return redirect('/login')->with('success', 'Akun berhasil dibuat, silakan login.');
    // }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login');
    }
}
