<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FlowkirimService;
use App\Notifications\SendWebPush;
use App\Services\FonteeService;
use App\Services\WablasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class WhatsappController extends Controller
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
        $this->authData = User::with(['zoneAdmin', 'whatsappGateway'])->find($auth->id);
    }

    // Ini contoh penggunaan send message fontee
    public function send(Request $request)
    {
        $request->validate([
            'no_tlp' => 'required',
            'no_tlp_admin' => 'required',
            'message' => 'required',
        ]);

        $message = $request->message;

        $token = $request->token;
        $no_tlp = Utils::phoneFormat($request->no_tlp);
        $no_tlp_admin = Utils::phoneFormat($request->no_tlp_admin);

        if (!$no_tlp && !$no_tlp_admin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid phone number',
            ]);
        }

        $fontee = new FonteeService();
        $result = $fontee->sendMessage($no_tlp, $message, $no_tlp_admin, $token);

        return response()->json($result);
    }

    public function testNotif(Request $request)
    {
        $request->validate([
            'phone' => 'required',
        ]);

        $message = "*Judul test*\n\nMessage Test lorem ipsum dolor sit amet.";

        $no_tlp_admin = Utils::phoneFormat($this->authData->no_tlp);

        if (!$no_tlp_admin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nomor admin tidak tersedia',
            ]);
        }

        $provider = $this->authData->whatsapp_gateway_provider;
        $no_tlp = Utils::phoneFormat($request->phone);
        $res = [
            'status' => true,
        ];

        if ($provider === "FONTEE") {
            $config = $this->authData->whatsappGateway->config;
            $fontee = new FonteeService();
            $res = $fontee->sendMessage($no_tlp, $message, $no_tlp_admin, $config);
        } else if ($provider === "WABLAS") {
            $config = $this->authData->whatsappGateway->config;
            $wablas = new WablasService();
            $res = $wablas->sendMessage($no_tlp, $message, $config);
        }

        if (!$res['status']) {
            return response()->json([
                'status' => 'error',
                'phone' => $no_tlp,
                'message' => 'Gagal mengirim notifikasi, cek kembali akun whatsapp gateway anda.',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'phone' => $no_tlp,
            'message' => 'Berhasil mengirim notifikasi'
        ]);
    }

    public function testNotifBrowser()
    {
        // $user = User::find(1);

        // $user->notify(new SendWebPush());

        Notification::send(User::all(), new SendWebPush('judul', 'message'));

        return back();
    }
}
