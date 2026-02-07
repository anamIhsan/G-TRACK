<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\MetafieldUser;
use App\Models\User;
use App\Models\Village;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MetafieldUserController extends Controller
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
        $query = MetafieldUser::query();

        if ($this->authData->role === 'MASTER') {
            // All access
        } elseif ($this->authData->role === 'ADMIN_DAERAH') {
            $query->where('zone_id', $this->authData->zoneAdmin->id);
        } else {
            abort(403);
        }

        $metafieldUsers = $query->latest()->paginate(10);

        return view('admin.metafield_user.index', [
            'metafieldUsers' => $metafieldUsers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $zones = Zone::get();

        return view('admin.metafield_user.create', [
            'zones' => $zones,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $requestData = $request->validate([
            'field' => 'required|string',
            'type' => 'required|in:STRING,ENUM',
            'enum_values.*' => 'nullable|required_if:type,ENUM',
            'zone_id' => 'nullable|exists:zones,id',
        ]);

        if ($requestData['enum_values'][0] != null) {
            foreach ($request->enum_values as $key => $value) {
                $enumValues[] = $value;
            }

            $requestData['enum_values'] = json_encode($enumValues);
        } else {
            $requestData['enum_values'] = null;
        }

        MetafieldUser::create($requestData);

        return redirect()->route('admin.metafield_user.index');
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
        $data = MetafieldUser::find($id);

        $zones = Zone::get();

        return view('admin.metafield_user.edit', [
            'data' => $data,
            'zones' => $zones,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $metafieldUser = MetafieldUser::find($id);

        $requestData = $request->validate([
            'field' => 'required|string',
            'type' => 'required|in:STRING,ENUM',
            'enum_values.*' => 'nullable|required_if:type,ENUM',
            'zone_id' => 'nullable|exists:zones,id',
        ]);

        if ($requestData['enum_values'][0] != null) {
            foreach ($request->enum_values as $key => $value) {
                $enumValues[] = $value;
            }

            $requestData['enum_values'] = json_encode($enumValues);
        } else {
            $requestData['enum_values'] = null;
        }

        $metafieldUser->update($requestData);

        return redirect()->route('admin.metafield_user.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $metafieldUser = MetafieldUser::find($id);

        $metafieldUser->delete();

        return redirect()->route('admin.metafield_user.index');
    }
}
