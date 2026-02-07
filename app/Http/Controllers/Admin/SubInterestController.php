<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use App\Models\SubInterest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubInterestController extends Controller
{
    public $auth;
    public $authData;

    /**
     * Constructor
     * Set auth & authData
     */
    public function __construct()
    {
        $auth = Auth::user();
        $this->auth = $auth;
        $this->authData = User::with(['zoneAdmin'])->find($auth->id);
    }

    /**
     * Simpan Sub Minat
     */
    public function store(Request $request)
    {
        $requestData = $request->validate([
            'interest_id' => 'required|exists:interests,id',
            'nama'        => 'required|string|max:255',
        ]);

        // Ambil interest
        $interest = Interest::findOrFail($requestData['interest_id']);

        /**
         * Validasi akses berdasarkan role
         */
        if ($this->authData->role === 'ADMIN_DAERAH') {
            if ($interest->zone_id !== $this->authData->zoneAdmin->id) {
                return redirect()->route('403');
            }
        }

        if ($this->authData->role === 'USER') {
            if ($interest->zone_id !== $this->authData->zone_id) {
                return redirect()->route('403');
            }
        }

        /**
         * 🔥 zone_id diwariskan dari Interest
         */
        $requestData['zone_id'] = $interest->zone_id;

        SubInterest::create($requestData);

        return redirect()
            ->route('admin.interests.show', $interest->id)
            ->with('success', 'Sub minat berhasil ditambahkan.');
    }

    /**
     * Update Sub Minat
     */
    public function update(Request $request, string $id)
    {
        $subInterest = SubInterest::findOrFail($id);

        // Validasi akses
        if ($this->authData->role === 'ADMIN_DAERAH') {
            if ($subInterest->zone_id !== $this->authData->zoneAdmin->id) {
                return redirect()->route('403');
            }
        }

        if ($this->authData->role === 'USER') {
            if ($subInterest->zone_id !== $this->authData->zone_id) {
                return redirect()->route('403');
            }
        }

        $requestData = $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $subInterest->update($requestData);

        return redirect()
            ->route('admin.interests.show', $subInterest->interest_id)
            ->with('success', 'Sub minat berhasil diperbarui.');
    }

    /**
     * Hapus Sub Minat
     */
    public function destroy(string $id)
    {
        $subInterest = SubInterest::findOrFail($id);

        // Validasi akses
        if ($this->authData->role === 'ADMIN_DAERAH') {
            if ($subInterest->zone_id !== $this->authData->zoneAdmin->id) {
                return redirect()->route('403');
            }
        }

        if ($this->authData->role === 'USER') {
            if ($subInterest->zone_id !== $this->authData->zone_id) {
                return redirect()->route('403');
            }
        }

        $interestId = $subInterest->interest_id;

        $subInterest->delete();

        return redirect()
            ->route('admin.interests.show', $interestId)
            ->with('success', 'Sub minat berhasil dihapus.');
    }
}
