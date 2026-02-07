<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FlowkirimService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = env('FLOWKIRIM_BASE_URL');
        $this->token = env('FLOWKIRIM_TOKEN');
    }

    public function sendMessage($phone, $message, $config)
    {
        $token = $this->token;
        $devicesId = $config['flowkirim_deviceId'];

        $response_session = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type'  => 'application/json',
        ])->get($this->baseUrl."/api/whatsapp/sessions/{$devicesId}");

        $session = $response_session->json();

        dd($response_session);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type'  => 'application/json',
        ])->post($this->baseUrl . "/api/whatsapp/messages/text", [
            'session_id'  => $session->data->session_id,
            'message' => $message,
            'to' => $phone,
        ]);

        return $response->json();
    }
}
