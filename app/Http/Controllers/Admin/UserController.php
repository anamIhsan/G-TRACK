<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Models\AgeCategory;
use App\Models\BaseMetafieldUser;
use App\Models\Interest;
use App\Models\MetafieldUser;
use App\Models\SubInterest;
use App\Models\Twibbon;
use App\Models\User;
use App\Models\Work;
use App\Models\Zone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;


class UserController extends Controller
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

        $usersQuery = $usersValid->where('role', 'USER');

        switch ($this->authData->role) {
            case 'MASTER':
                // no extra filter
                break;

            case 'ADMIN_DAERAH':
                $usersQuery->where('zone_id', $this->authData->zoneAdmin->id);
                break;
        }

        $users = $usersQuery->latest()->paginate(10);


        $filters = $request->all();
        if ($request->age_category_id) {
            $age_category = AgeCategory::find($request['age_category_id']);
            $age_category_nama = $age_category->nama;
        } else {
            $age_category_nama = null;
        }

        $age_categories = AgeCategory::get();
        $zones = Zone::get();

        $role = $this->authData->role;
        $roleFilters = [
            'ADMIN_DAERAH' => fn($q) => $q->where('zone_id', $this->authData->zoneAdmin->id),
        ];

        $applyRoleFilter = function ($query) use ($role, $roleFilters) {
            if (isset($roleFilters[$role])) {
                $roleFilters[$role]($query);
            }

            return $query;
        };


        $interests = $applyRoleFilter(Interest::query())->get();
        $sub_interests = $applyRoleFilter(SubInterest::query())->get();
        $metafieldUsers = $applyRoleFilter(MetafieldUser::query())->get();


        return view('admin.user.index', [
            'users' => $users,
            'zones' => $zones,
            'interests' => $interests,
            'sub_interests' => $sub_interests,
            'age_categories' => $age_categories,
            'metafieldUsers' => $metafieldUsers,
            'filters' => $filters,
            'age_category_nama' => $age_category_nama,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $zonesQuery = Zone::query();

        $role = $this->authData->role;

        switch ($role) {
            case 'MASTER':
                break;

            case 'ADMIN_DAERAH':
                $zonesQuery->where('id', $this->authData->zoneAdmin->id);
                break;

            default:
                $zonesQuery->whereRaw('1 = 0');
                break;
        }

        $zones = $zonesQuery->get();

        $roleFilters = [
            'ADMIN_DAERAH' => fn($q) => $q->where('zone_id', $this->authData->zoneAdmin->id),
        ];

        $applyRoleFilter = function ($query) use ($role, $roleFilters) {
            if (isset($roleFilters[$role])) {
                $roleFilters[$role]($query);
            }

            return $query;
        };


        $interests = $applyRoleFilter(Interest::query())->get();
        $sub_interests = $applyRoleFilter(SubInterest::query())->get();
        $works = $applyRoleFilter(Work::query())->get();
        $metafieldUsers = $applyRoleFilter(MetafieldUser::query())->get();
        $metafieldUsers = $metafieldUsers->map(function ($m) {
            $m->value = old("value.{$m->id}", $m->value);
            return $m;
        });

        return view('admin.user.create', [
            'interests' => $interests,
            'sub_interests' => $sub_interests,
            'works' => $works,
            'zones' => $zones,
            'metafieldUsers' => $metafieldUsers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $requestData = $request->validate([
            'gambar' => ['nullable'],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'unique:users,email'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'no_tlp' => ['nullable', 'string', 'max:20'],
            'tanggal_lahir' => ['required', 'date'],
            'kelamin' => ['nullable', 'in:PR,LK'],
            'interest_id' => ['nullable', 'exists:interests,id'],
            'sub_interest_id' => ['nullable', 'exists:sub_interests,id'],
            'work_id' => ['nullable', 'exists:works,id'],
            'zone_id' => ['nullable', 'exists:zones,id'],
            'status_kawin' => ['required', 'in:BELUM,SUDAH'],
            'nfc_id' => ['required'],
            'password' => ['required'],
        ]);

        $tanggalLahir = $requestData['tanggal_lahir'];
        $resultUmur = Carbon::parse($tanggalLahir)->age;

        $umur = $resultUmur;

        if ($umur && $requestData['status_kawin'] === "BELUM") {
            $ageCategory = AgeCategory::where('range_one', '<=', $umur)->where('range_two', '>=', $umur)->first();

            if ($ageCategory) {
                $requestData['age_category_id'] = $ageCategory->id;
            }
        }

        $requestData['umur'] = $umur;
        $requestData['password'] = Hash::make($request->password);
        $requestData['hint_password'] = $request->password;
        $requestData['role'] = "USER";

        if ($this->authData->role === "MASTER") {
            $requestData['zone_id'] = $request->zone_id;
        } else if ($this->authData->role === "ADMIN_DAERAH") {
            $requestData['zone_id'] = $this->authData->zoneAdmin->id ?? null;
        }

        if ($request->gambar) {
            $fileName = $request->gambar;

            $requestData['gambar'] = $fileName;
        }

        $user = User::create($requestData);

        $twibbon = Twibbon::where('zone_id', $user->zone_id)->first();

        if (!$twibbon) {
            $twibbon = Twibbon::first();
        }

        if ($twibbon && file_exists($this->public_path . $twibbon->twibbon)) {
            $twibbonPath = $twibbon->twibbon;

            $filename = Utils::generateTwibbon($user, $twibbonPath);

            $user->update([
                'twibbon_user' => $filename
            ]);
        }

        if (isset($request->value) && Arr::first($request->value) != null && count($request->value) > 0) {
            foreach ($request->value as $key => $value) {
                BaseMetafieldUser::create([
                    'value' => $value,
                    'user_id' => $user->id,
                    'metafield_user_id' => $key
                ]);
            }
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = User::find($id);
        $metafieldUsers = MetafieldUser::get();

        return view('admin.user.show', [
            'data' => $data,
            'metafieldUsers' => $metafieldUsers,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = User::find($id);
        $zonesQuery = Zone::query();

        $role = $this->authData->role;

        switch ($role) {
            case 'MASTER':
                break;

            case 'ADMIN_DAERAH':
                $zonesQuery->where('id', $this->authData->zoneAdmin->id);
                break;

            default:
                $zonesQuery->whereRaw('1 = 0');
                break;
        }

        $zones = $zonesQuery->get();

        $roleFilters = [
            'ADMIN_DAERAH' => fn($q) => $q->where('zone_id', $this->authData->zoneAdmin->id),
        ];

        $applyRoleFilter = function ($query) use ($role, $roleFilters) {
            if (isset($roleFilters[$role])) {
                $roleFilters[$role]($query);
            }

            return $query;
        };

        $interests = $applyRoleFilter(Interest::query())->get();
        $sub_interests = $applyRoleFilter(SubInterest::query())->get();
        $works = $applyRoleFilter(Work::query())->get();
        $metafieldUsers = $applyRoleFilter(MetafieldUser::query())->get();

        if (!$data) {
            return redirect()->route('404');
        }

        return view('admin.user.edit', [
            'interests' => $interests,
            'sub_interests' => $sub_interests,
            'works' => $works,
            'zones' => $zones,
            'data' => $data,
            'metafieldUsers' => $metafieldUsers,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $requestData = $request->validate([
            'gambar' => ['nullable'],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'unique:users,email,' . $id],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $id],
            'no_tlp' => ['nullable', 'string', 'max:20'],
            'tanggal_lahir' => ['required', 'date'],
            'kelamin' => ['nullable', 'in:PR,LK'],
            'interest_id' => ['nullable', 'exists:interests,id'],
            'sub_interest_id' => ['nullable', 'exists:sub_interests,id'],
            'work_id' => ['nullable', 'exists:works,id'],
            'zone_id' => ['nullable', 'exists:zones,id'],
            'status_kawin' => ['required', 'in:BELUM,SUDAH'],
            'nfc_id' => ['required'],
        ]);

        $tanggalLahir = $requestData['tanggal_lahir'];
        $resultUmur = Carbon::parse($tanggalLahir)->age;

        $umur = $resultUmur;

        if ($umur && $requestData['status_kawin'] === "BELUM") {
            $ageCategory = AgeCategory::where('range_one', '<=', $umur)->where('range_two', '>=', $umur)->first();

            if ($ageCategory) {
                $requestData['age_category_id'] = $ageCategory->id;
            }
        } else {
            $requestData['age_category_id'] = null;
        }

        $requestData['umur'] = $umur;

        if ($request->gambar) {
            $fileName = $request->gambar;

            if ($user->gambar && file_exists($this->public_path . $user->gambar)) {
                unlink($this->public_path . $user->gambar);
            }

            $requestData['gambar'] = $fileName;
        } else {
            $requestData['gambar'] = $user->gambar;
        }

        if ($request->password) {
            $requestData['password'] = Hash::make($request->password);
            $requestData['hint_password'] = $request->password;
        }

        $user->update($requestData);

        $twibbon = Twibbon::where('zone_id', $user->zone_id)->first();

        if (!$twibbon) {
            $twibbon = Twibbon::first();
        }

        if ($twibbon && file_exists($this->public_path . $twibbon->twibbon)) {
            $twibbonPath = $twibbon->twibbon;

            $filename = Utils::generateTwibbon($user, $twibbonPath);

            if ($user->twibbon_user && file_exists($this->public_path . $user->twibbon_user)) {
                unlink($this->public_path . $user->twibbon_user);
            }

            $user->update([
                'twibbon_user' => $filename
            ]);
        }

        if (isset($request->value) && Arr::first($request->value) != null && count($request->value) > 0) {
            foreach ($request->value as $key => $value) {
                $metafieldUser = MetafieldUser::where("id", $key)->where("zone_id", $user->zone_id)->first();

                if ($metafieldUser) {
                    $baseMetafieldUser = BaseMetafieldUser::where("user_id", $user->id)->where("metafield_user_id", $key)->first();

                    if ($baseMetafieldUser) {
                        $baseMetafieldUser->delete();
                    }
                }

                BaseMetafieldUser::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'metafield_user_id' => $key,
                    ],
                    [
                        'value' => $value,
                        'user_id' => $user->id,
                        'metafield_user_id' => $key,
                    ]
                );
            }
        }

        return redirect()
            ->route('admin.users.index', $user->id)
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil dihapus.');
    }

    public function history_users_index(Request $request)
    {
        $usersValid = User::query()
            ->when($request['age_category_id'], function ($query, $f) {
                $query->where('age_category_id', $f);
            })
            ->when($request['kelamin'], function ($query, $f) {
                $query->where('kelamin', $f);
            });

        if ($this->authData->role === "MASTER") {
            $users = $usersValid->onlyTrashed()->where('role', 'USER')->get();
        } elseif ($this->authData->role === "ADMIN_DAERAH") {
            $users = $usersValid->onlyTrashed()->where('role', 'USER')->where('zone_id', $this->authData->zoneAdmin->id)->get();
        } else {
            $users = $usersValid->onlyTrashed()->where('role', 'USER')->where('zone_id', $this->authData->zone_id)->get();
        }

        $filters = $request->all();

        $age_categories = AgeCategory::get();

        return view('admin.user.history_index', [
            "users" => $users,
            'age_categories' => $age_categories,
            'filters' => $filters,
        ]);
    }

    public function history_users_update(string $id)
    {
        $user = User::onlyTrashed()->where('id', $id)->first();
        $user->update([
            'reason_deleted' => null
        ]);
        $user->restore();

        return redirect()
            ->route('admin.history_users.index')
            ->with('success', 'Data pengguna berhasil dipulihkan.');
    }

    public function imageCrop(Request $request)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $fileName = time() . uniqid() . '.webp';

            $savePath = $this->public_path . '/profiles/' . $fileName;

            $path = "/profiles/$fileName";

            if (file_exists($savePath)) {
                unlink($savePath);
            }

            $manager = new ImageManager(new GdDriver());

            $img = $manager->read($file->getRealPath());

            $img->encode(new WebpEncoder(quality: 80));

            $img->save($savePath);

            $requestData['gambar'] = $path;

            return response()->json([
                'gambar' => $path,
                'path' => $savePath,
                'success' => 'Profile berhasil di simpan!',
            ]);
        } else {
            return response()->json([
                'error' => 'Gagal mengupload profile, coba ulangi'
            ]);
        }
    }
}
