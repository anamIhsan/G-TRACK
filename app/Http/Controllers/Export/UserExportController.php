<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Models\MetafieldUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Barryvdh\DomPDF\Facade\Pdf;

class UserExportController extends Controller
{
    public $auth;
    public $authData;

    public function __construct()
    {
        $auth = Auth::user();
        $this->auth = $auth;
        $this->authData = User::with(['zoneAdmin'])->find($auth->id);
    }

    public function exportUsers(Request $request)
    {
        return response()->streamDownload(function () use ($request) {

            $filename = 'users_' . date('y-m-d_h-i-s') . '.xlsx';
            $writer = SimpleExcelWriter::streamDownload($filename);

            $baseHeaders = [
                'Nama',
                'Email',
                'No Telepon',
                'NFC ID',
                'Username',
                'Jenis kelamin',
                'Tanggal lahir',
                'Umur',
                'Password',
                'Status Kawin',
                'Daerah',
                'Pekerjaan',
                'Minat',
                'Sub minat',
            ];

            $metafields = MetafieldUser::orderBy('field')->get();
            $metafieldHeaders = $metafields->pluck('field')->toArray();

            $writer->addHeader(array_merge($baseHeaders, $metafieldHeaders));

            $usersValid = User::query()
                ->when($request['age_category_id'], fn($q, $f) => $q->where('age_category_id', $f))
                ->when($request['kelamin'], fn($q, $f) => $q->where('kelamin', $f))
                ->when($request['interest_id'], fn($q, $f) => $q->where('interest_id', $f))
                ->when($request['sub_interest_id'], fn($q, $f) => $q->where('sub_interest_id', $f));

            $relations = [
                'zone',
                'work',
                'interest',
                'subInterest',
                'baseMetafieldUsers',
                'baseMetafieldUsers.metafieldUser',
            ];

            if ($this->authData->role === "MASTER") {
                $users = $usersValid->with($relations)
                    ->where('role', 'USER')
                    ->get();
            } elseif ($this->authData->role === "ADMIN_DAERAH") {
                $users = $usersValid->with($relations)
                    ->where('role', 'USER')
                    ->where('zone_id', $this->authData->zoneAdmin->id)
                    ->get();
            }

            $rowTemplate = array_fill_keys(
                array_merge($baseHeaders, $metafieldHeaders),
                ''
            );

            foreach ($users as $user) {

                $row = $rowTemplate;

                // --- data utama ---
                $row['Nama'] = $user->nama;
                $row['Email'] = $user->email;
                $row['No Telepon'] = $user->no_tlp;
                $row['NFC ID'] = $user->nfc_id;
                $row['Username'] = $user->username;
                $row['Jenis kelamin'] = $user->kelamin === "PR" ? "Perempuan" : "Laki-laki";
                $row['Tanggal lahir'] = $user->tanggal_lahir;
                $row['Umur'] = $user->umur;
                $row['Password'] = $user->hint_password;
                $row['Status Kawin'] = $user->status_kawin ?? '';
                $row['Daerah'] = $user->zone->nama ?? '';
                $row['Pekerjaan'] = $user->work->nama ?? '';
                $row['Minat'] = $user->interest->nama ?? '';
                $row['Sub minat'] = $user->subInterest->nama ?? '';

                // --- metafield value ---
                foreach ($user->baseMetafieldUsers as $mfValue) {
                    $fieldName = $mfValue->metafieldUser?->field;
                    if ($fieldName) {
                        $row[$fieldName] = $mfValue->value;
                    }
                }

                $writer->addRow($row);
            }
        }, 'users_' . date('y-m-d_h-i-s') . '.xlsx');
    }

    public function exportUserSingle($id)
    {
        $user = User::where('id', $id)->with(['zone', 'work', 'interest', 'subInterest', 'baseMetafieldUsers', 'baseMetafieldUsers.metafieldUser', 'level', 'level.metafieldLevels'])->first();

        $pdf = PDF::loadView('export.pdf.user', [
            'user' => $user
        ]);
        return $pdf->download('user_' . $user->nama . '.pdf');
    }
}
