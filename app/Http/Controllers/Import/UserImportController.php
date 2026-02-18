<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Models\AgeCategory;
use App\Models\Interest;
use App\Models\User;
use App\Models\Zone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\SimpleExcel\SimpleExcelReader;

class UserImportController extends Controller
{
    public function index()
    {
        return view('import.user');
    }

    public function importUsers(Request $request)
    {
        $file = $request->file('file');

        $destination = public_path('imports');
        if (!is_dir($destination)) {
            mkdir($destination, 0777, true);
        }

        $filename = time() . '-' . $file->getClientOriginalName();
        $file->move($destination, $filename);

        $path = public_path('imports/' . $filename);

        $handle = fopen($path, 'r');

        $firstLine = fgets($handle);

        if (str_contains($firstLine, ';')) {
            $delimiter = ';';
        } elseif (str_contains($firstLine, ',')) {
            $delimiter = ',';
        } else {
            $delimiter = ';';
        }

        $rows = SimpleExcelReader::create($path)->useDelimiter($delimiter);

        $rows->getRows()->each(function ($row) {
            $zone = Zone::where('nama', 'LIKE', '%'.$row['daerah'].'%')->first();

            if(!$zone){
                $username = strtolower(str_replace(' ', '', $row['daerah']));

                $user = User::create([
                    'username' => 'admin_'. $username,
                    'nama'     => 'Admin '.$row['daerah'],
                    'role'     => 'ADMIN_DAERAH',
                    'password'       => Hash::make('123456789'),
                    'hint_password'  => '123456789',
                ]);

                $zone = Zone::create([
                    'nama' => $row['daerah'],
                    'user_id' => $user->id,
                ]);
            }
            $interest = Interest::where('nama', 'LIKE', '%'.$row['minat'].'%')->first();
            if(!$interest){
                $interest = Interest::create([
                    'nama' => $row['minat'],
                    'zone_id' => $zone->id,
                ]);
            }

            $tanggalInput = $row['tanggal_lahir'];
            $tanggalahir = Carbon::createFromFormat('d-m-Y', $tanggalInput)->format('Y-m-d');
            $resultUmur = Carbon::parse($tanggalahir)->age;
            $ageCategory_id = "";

            if ($resultUmur && $row['status_kawin'] === "BELUM") {
                $ageCategory = AgeCategory::where('range_one', '<=', $resultUmur)->where('range_two', '>=', $resultUmur)->first();

                if ($ageCategory) {
                    $ageCategory_id = $ageCategory->id;
                }
            }

            User::create([
                'nama' => $row['nama'],
                'email' => $row['email'],
                'no_tlp' => $row['no_tlp'],
                'username' => $row['username'],
                'umur' => $resultUmur,
                'nfc_id' => $row['nfc_id'],
                'password' => Hash::make($row['password']),
                'hint_password' => $row['password'],
                'kelamin' => strtoupper($row['kelamin']),
                'status_kawin' => strtoupper($row['status_kawin']),
                'interest_id' => $interest->id,
                'tanggal_lahir' => $tanggalahir,
                'zone_id' => $zone->id,
                'age_category_id' => $ageCategory_id,
            ]);
        });

        fclose($handle);
        $rows->close();
        unset($rows);
        gc_collect_cycles();

        sleep(1);

        if (file_exists($path)) {
            unlink($path);
        }

        return back()->with('success', 'Data pengguna berhasil diimport!');
    }

    public function download_template()
    {
        $path = public_path('template-imports/' . 'template_users_import.csv');

        if (!file_exists($path)) {
            return view('pages.404', [
                'message' => 'File template tidak ditemukan',
            ]);
        }

        return response()->download($path);
    }
}
