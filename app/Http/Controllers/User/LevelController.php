<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\MetafieldLevel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LevelController extends Controller
{
    public $auth;
    public $authData;

    /**
     * Constructor method for DashboardController.
     *
     * It sets the auth and authData properties with the current user.
     */
    public function __construct()
    {
        $auth = Auth::user();
        $this->auth = $auth;
        $this->authData = User::with(['zoneAdmin', 'baseMetafieldUsers'])->find($auth->id);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $metafieldLevels = MetafieldLevel::get();

        return view('user.level.index', [
            'metafieldLevels' => $metafieldLevels,
            'authData' => $this->authData,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $metafieldHalaman = $request->halaman;

        $data = [
            'value' => $request->value ? ($request->value > 100 ? 100 : $request->value) : 0,
            'user_id' => $this->authData->id,
            'metafield_level_id' => $id,
            'status' => 'PENDING'
        ];

        if ($metafieldHalaman) {
            $data['halaman'] = $metafieldHalaman;
        }

        Level::updateOrCreate(
            ['user_id' => $this->authData->id, 'metafield_level_id' => $id],
            $data
        );

        return redirect()->back()->with('success', 'Tunggu admin menerima perubahan anda');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
