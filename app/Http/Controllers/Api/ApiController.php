<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use App\Models\Presences;
use App\Models\User;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function reason_deleted(Request $request, $id)
    {
        $user = User::find($id);

        $user->update([
            'reason_deleted' => $request->reason,
        ]);

        return response()->json(['reason' => $request->reason]);
    }
}
