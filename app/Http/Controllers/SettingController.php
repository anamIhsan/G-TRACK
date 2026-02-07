<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WhatsappGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingController extends Controller
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
        $this->authData = User::with(['zoneAdmin', 'whatsappGateway'])->find($auth->id);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        return view('admin.setting.index', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Cek apakah request untuk update password atau profil
        if ($request->has('password_lama')) {
            return $this->updatePassword($request, $id);
        } else {
            return $this->updateProfile($request, $id);
        }
    }

    /**
     * Update admin profile (name and phone number)
     */
    private function updateProfile(Request $request, string $id)
    {
        $requestData = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'no_tlp' => ['required', 'integer'],
        ], [
            'nama.required' => 'Nama admin harus diisi.',
            'nama.max' => 'Nama admin maksimal 255 karakter.',
            'no_tlp.required' => 'Nomor HP harus diisi.',
        ]);

        try {
            $user = User::findOrFail($id);

            // Pastikan user hanya bisa update datanya sendiri
            if ($user->id !== Auth::id()) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengubah data ini.');
            }

            $user->update($requestData);

            return redirect()
                ->back()
                ->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui profil. Silakan coba lagi.');
        }
    }

    /**
     * Update admin password
     */
    private function updatePassword(Request $request, string $id)
    {
        $request->validate([
            'password_lama' => ['required'],
            'password_baru' => ['required', 'confirmed', Password::min(8)],
            'password_baru_confirmation' => ['required'],
        ], [
            'password_lama.required' => 'Password lama harus diisi.',
            'password_baru.required' => 'Password baru harus diisi.',
            'password_baru.min' => 'Password baru minimal 8 karakter.',
            'password_baru.confirmed' => 'Konfirmasi password tidak cocok.',
            'password_baru_confirmation.required' => 'Konfirmasi password harus diisi.',
        ]);

        try {
            $user = User::findOrFail($id);

            // Pastikan user hanya bisa update passwordnya sendiri
            if ($user->id !== Auth::id()) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengubah data ini.');
            }

            // Cek apakah password lama benar
            if (!Hash::check($request->password_lama, $user->password)) {
                return redirect()->back()
                    ->withErrors(['password_lama' => 'Password lama tidak sesuai.'])
                    ->withInput()
                    ->with('error', 'Password lama salah.');
            }

            // Cek apakah password baru sama dengan password lama
            if (Hash::check($request->password_baru, $user->password)) {
                return redirect()->back()
                    ->withErrors(['password_baru' => 'Password baru tidak boleh sama dengan password lama.'])
                    ->withInput()
                    ->with('error', 'Password baru tidak boleh sama dengan yang lama.');
            }

            // Update password
            $user->password = Hash::make($request->password_baru);
            $user->hint_password = $request->password_baru;
            $user->save();

            return redirect()
                ->back()
                ->with('success', 'Password berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui password. Silakan coba lagi.');
        }
    }

    public function whatsappGateway(Request $request)
    {
        $request->validate([
            'whatsapp_gateway_provider' => 'required',
        ]);

        $provider = $request->whatsapp_gateway_provider;

        if ($provider === 'FONTEE') {
            $request->validate([
                'fontee_token' => 'required',
            ]);
            $config  = [
                'fontee_token' => $request->fontee_token
            ];
        } else if ($provider === 'WABLAS') {
            $request->validate([
                'wablas_token' => 'required',
                'wablas_secret_key' => 'required',
            ]);
            $config = [
                'wablas_token' => $request->wablas_token,
                'wablas_secret_key' => $request->wablas_secret_key
            ];
        } else if ($provider === 'FLOWKIRIM') {
            $request->validate([
                'flowkirim_deviceId' => 'required',
            ]);
            $config = [
                'flowkirim_deviceId' => $request->flowkirim_deviceId,
            ];
        }
        else {
            return redirect()->route('404')->with('message', 'Gateway whatsapp tidak ditemukan.');
        }

        WhatsappGateway::updateOrCreate(
            ['user_id' => $this->authData->id, 'type' => $provider],
            [
                'type' => $provider,
                'config' => $config,
            ]
        );

        $user = User::findOrFail($this->authData->id);
        $user->whatsapp_gateway_provider = $provider;
        $user->save();

        return redirect()
            ->back()
            ->with('success', 'Whatsapp Gateway berhasil diperbarui.');
    }
}
