<?php

namespace App\Services;

use App\Helpers\Utils;
use Illuminate\Support\Facades\Http;

class WablasService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('WABLAS_URL');
    }

    public function sendMessage($phone, $message, $config)
    {
        $secret_key = $config['wablas_secret_key'];
        $token = $config['wablas_token'];

        $encodedMessage = urlencode($message);

        $url = "{$this->baseUrl}/api/send-message?token={$token}.{$secret_key}&phone={$phone}&message={$encodedMessage}";

        $response = Http::withoutVerifying()->get($url);

        return $response->json();
    }
}
