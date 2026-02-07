<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FonteeService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('FONTEE_BASE_URL');
    }

    public function sendMessage($phone, $message, $device_id, $config)
    {
        $token = $config['fontee_token'];

        $response = Http::withHeaders([
            'Authorization' => $token,
        ])->post($this->baseUrl . "/send", [
            'device_id' => $device_id,
            'target'    => $phone,
            'message'   => $message,
        ]);

        return $response->json();
    }
}
