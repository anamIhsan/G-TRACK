<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Presence;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
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
        $activities = Activity::where('zone_id', $this->authData->zone_id)
            ->whereHas('ageCategories', function ($query) {
                $query->where('zone_id', $this->authData->zone_id)
                    ->whereHas('users', function ($query) {
                        $query->where('id', $this->authData->id);
                    });
            })
            ->with(['ageCategories.users'])
            ->latest()
            ->paginate(10);

        return view('user.activity.index', [
            'activities' => $activities,
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
        $presences = Presence::where('user_id', $this->authData->id)->where('activity_id', $id)->get();

        $activity = Activity::find($id);

        return view('user.activity.show', [
            'presences' => $presences,
            'authData' => $this->authData,
            'activity' => $activity
        ]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
