<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Models\AgeCategory;
use App\Models\Interest;
use App\Models\SubInterest;
use App\Models\Twibbon;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ZipArchive;

class CardController extends Controller
{
    public $auth;
    public $authData;
    public $public_path;

    public function __construct()
    {
        $auth = Auth::user();
        $this->auth = $auth;
        $this->authData = User::with(['zoneAdmin'])->find($auth->id);
        if (env('TYPE_SERVER') === "CPANEL" && env('APP_ENV') === "production") {
            $this->public_path = env('DOCUMENT_STORAGE');
        } else {
            $this->public_path = public_path();
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query()
            ->where('role', 'USER')
            ->when($request->age_category_id, fn($q, $f) => $q->where('age_category_id', $f))
            ->when($request->kelamin, fn($q, $f) => $q->where('kelamin', $f))
            ->when($request->interest_id, fn($q, $f) => $q->where('interest_id', $f))
            ->when($request->sub_interest_id, fn($q, $f) => $q->where('sub_interest_id', $f));

        if ($this->authData->role === "MASTER") {
            // No extra filter
        } elseif ($this->authData->role === "ADMIN_DAERAH") {
            $query->where('zone_id', $this->authData->zoneAdmin->id);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        $age_category_nama = $request->age_category_id
            ? AgeCategory::find($request->age_category_id)?->nama
            : null;

        $twibbon = Twibbon::where('zone_id', $this->authData->zone_id)->first();
        if ($this->authData->role === "ADMIN_MASTER") {
            $twibbon = Twibbon::first();
        }

        return view('admin.card.index', [
            'users' => $users,
            'twibbon' => $twibbon,
            'authData' => $this->authData,
            'age_category_nama' => $age_category_nama,
            'age_categories' => AgeCategory::get(),
            'interests' => Interest::get(),
            'sub_interests' => SubInterest::get(),
            'zones' => Zone::get(),
            'filters' => $request->all(),
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function twibbon_create()
    {
        $zones = Zone::get();

        return view('admin.card.twibbon_create', [
            'zones' => $zones,
            'authData' => $this->authData
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function twibbon_store(Request $request)
    {
        $requestData = $request->validate([
            'twibbon' => 'required|image',
        ]);

        if ($this->authData->role === 'ADMIN_DAERAH') {
            $profileUsers = User::where('role', 'USER')->where('zone_id', $this->authData->zoneAdmin->id)->get();
            $twibbon = Twibbon::where('zone_id', $this->authData->zoneAdmin->id)->first();
        } else if ($this->authData->role === 'MASTER') {
            $profileUsers = User::where('role', 'USER')->get();
            $twibbon = Twibbon::where('zone_id', null)->first();
            $requestData['zone_id'] = $request->zone_id;
        } else {
            return view('pages.403');
        }

        if (!$twibbon) {
            $twibbon = Twibbon::create($requestData);
        } else {
            if (file_exists($this->public_path . $twibbon->twibbon)) {
                unlink($this->public_path . $twibbon->twibbon);
            }
        }

        if ($request->hasFile('twibbon')) {
            $file = $request->file('twibbon');

            $fileName = time() . '.' . $file->getClientOriginalExtension();

            $file->move($this->public_path . '/twibbons', $fileName);

            $path = "/twibbons/" . $fileName;

            $requestData['twibbon'] = $path;

            $twibbonPath = $path;

            foreach ($profileUsers as $user) {
                $filename = Utils::generateTwibbon($user, $twibbonPath);

                if ($user->twibbon_user && file_exists($this->public_path . $user->twibbon_user)) {
                    unlink($this->public_path . $user->twibbon_user);
                }

                $user->update([
                    'twibbon_user' => $filename
                ]);
            }
        }

        $twibbon->update($requestData);


        return redirect()->route('admin.cards.index');
    }

    public function twibbon_download_all(Request $request)
    {
        $usersValid = User::query()
            ->when($request['age_category_id'], function ($query, $f) {
                $query->where('age_category_id', $f);
            })
            ->when($request['kelamin'], function ($query, $f) {
                $query->where('kelamin', $f);
            })
            ->when($request['interest_id'], function ($query, $f) {
                $query->where('interest_id', $f);
            })
            ->when($request['sub_interest_id'], function ($query, $f) {
                $query->where('sub_interest_id', $f);
            });

        if ($this->authData->role === "MASTER") {
            $users = $usersValid->where('role', 'USER')->get();
        } elseif ($this->authData->role === "ADMIN_DAERAH") {
            $users = $usersValid->where('role', 'USER')->where('zone_id', $this->authData->zoneAdmin->id)->get();
        }

        $zipFileName = 'twibbon.zip';
        $folder = $this->public_path . '/twibbons';

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $zipPath = $folder . '/' . $zipFileName;
        $zip = new \ZipArchive();

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
            foreach ($users as $value) {

                $filePath = $this->public_path . $value->twibbon_user;

                if (file_exists($filePath)) {
                    $zip->addFile($filePath, basename($value->twibbon_user));
                }
            }

            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function twibbon_download($id)
    {
        $user = User::find($id);

        return response()->download($this->public_path . $user->twibbon_user);
    }
}
