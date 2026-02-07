<?php

namespace App\Http\Controllers\User;

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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

class ProfileController extends Controller
{
    public $auth;
    public $authData;
    public $public_path;

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
        if (env('TYPE_SERVER') === "CPANEL" && env('APP_ENV') === "production") {
            $this->public_path = env('DOCUMENT_STORAGE');
        } else {
            $this->public_path = public_path();
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $auth = Auth::user();
        $data = User::with(['zoneAdmin'])->find($auth->id);
        $works = Work::where('zone_id', $data->zone_id)->get();
        $interests = Interest::where('zone_id', $data->zone_id)->get();
        $sub_interests = SubInterest::get();
        $metafieldUsers = MetafieldUser::get();

        return view('user.profile.show', [
            'data' => $data,
            'works' => $works,
            'interests' => $interests,
            'sub_interests' => $sub_interests,
            'metafieldUsers' => $metafieldUsers,
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
        $auth = Auth::user();
        $user = User::find($auth->id);

        $requestData = $request->validate([
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'nama' => ['required', 'string', 'max:255'],
            'tempat_lahir' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['required', 'date'],
            'kelamin' => ['nullable', 'in:PR,LK'],
            'status' => ['nullable', 'in:REGULER,MT,MS'],
            'siap_nikah' => ['nullable', 'in:SIAP,TIDAK'],
            'pendidikan_terakhir' => ['nullable', 'string', 'max:255'],
            'pendidikan' => ['nullable', 'string', 'max:255'],
            'jurusan' => ['nullable', 'string', 'max:255'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'domisili' => ['nullable', 'string', 'max:255'],
            'status_domisili' => ['nullable', 'string', 'max:255'],
            'no_tlp' => ['nullable', 'string', 'max:20'],
            'nama_ayah' => ['nullable', 'string', 'max:255'],
            'status_ayah' => ['nullable', 'in:KUM,HUM'],
            'depukan_ayah' => ['nullable', 'in:JAMAAH,PENGURUS'],
            'nama_ibu' => ['nullable', 'string', 'max:255'],
            'status_ibu' => ['nullable', 'in:KUM,HUM'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'work_id' => ['nullable', 'exists:works,id'],
            'status_kawin' => ['required', 'in:BELUM,SUDAH'],
            'interest_id' => ['nullable', 'exists:interests,id'],
            'sub_interest_id' => ['nullable', 'exists:sub_interests,id'],
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

        $user->update($requestData);

        if (isset($request->value) && Arr::first($request->value) != null && count($request->value) > 0) {
            foreach ($request->value as $key => $value) {
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

        $twibbon = Twibbon::where('zone_id', $user->zone_id)->first();

        if (!$twibbon) {
            $twibbon = Twibbon::first();
        }

        if ($twibbon && file_exists($this->public_path . $twibbon->twibbon)) {
            $twibbonPath = $twibbon->twibbon;

            $filenameTwibon = Utils::generateTwibbon($user, $twibbonPath);

            if ($user->twibbon_user && file_exists($this->public_path . $user->twibbon_user)) {
                unlink($this->public_path . $user->twibbon_user);
            }

            $user->update([
                'twibbon_user' => $filenameTwibon
            ]);
        }

        return redirect()
            ->route('user.profiles.index')
            ->with('success', 'Profile berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $auth = Auth::user();
        $user = User::find($auth->id);
        $user->update([
            'reason_deleted' => 'Dihapus oleh pengguna.',
        ]);

        $user->delete();
        return redirect()->route('auth.login');
    }

    public function updatePhoto(Request $request)
    {
        $user = User::find($this->authData->id);

        $request->validate([
            'gambar' => 'required|image',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');

            $fileName = time() . uniqid() . '.webp';

            $savePath = $this->public_path . '/profiles/' . $fileName;

            $path = "/profiles/$fileName";

            $manager = new ImageManager(new GdDriver());

            $img = $manager->read($file->getRealPath());

            $img->encode(new WebpEncoder(quality: 80));

            $img->save($savePath);

            $requestData['gambar'] = $path;
        }

        $user->update([
            'gambar' => $requestData['gambar'],
        ]);

        $twibbon = Twibbon::where('zone_id', $user->zone_id)->first();

        if (!$twibbon) {
            $twibbon = Twibbon::first();
        }

        if ($twibbon && file_exists($this->public_path . $twibbon->twibbon)) {
            $twibbonPath = $twibbon->twibbon;

            $filenameTwibon = Utils::generateTwibbon($user, $twibbonPath);

            if ($user->twibbon_user && file_exists($this->public_path . $user->twibbon_user)) {
                unlink($this->public_path . $user->twibbon_user);
            }

            $user->update([
                'twibbon_user' => $filenameTwibon
            ]);
        }

        return redirect()->back()->with('success', 'Foto berhasil diperbarui.');
    }

    public function twibbon_download()
    {
        $user = User::find($this->authData->id);

        $twibbon = Twibbon::where('zone_id', $user->zone_id)->first();

        if (!$twibbon) {
            $twibbon = Twibbon::first();
        }

        if (!$twibbon && !$user->twibbon_user) {
            return response()->json([
                'error' => 'Kartu tidak ditemukan.',
                'message' => 'Kartu tidak ditemukan.',
            ]);
        }

        return response()->download($this->public_path . $user->twibbon_user);
    }
}
