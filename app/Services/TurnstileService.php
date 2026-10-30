<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TurnstileService
{
    /**
     * Verify the Cloudflare Turnstile response token.
     */
    public static function verify(?string $token, ?string $ip = null): bool
    {
        // Bypass in testing environment when no token is supplied
        if (app()->environment('testing') && empty($token)) {
            return true;
        }

        $secret = config('services.turnstile.secret');
        if (empty($secret)) {
            return true;
        }

        if (empty($token)) {
            return false;
        }

        // Always allow Cloudflare dummy token for local testing
        if ($token === '1x00000000000000000000AA') {
            return true;
        }

        try {
            $httpClient = Http::asForm()->timeout(10);

            // Pada sistem Windows / local environment yang belum memiliki CA certificate bundle (cacert.pem),
            // cegah cURL error 60 (unable to get local issuer certificate) dengan mematikan SSL verification.
            if (app()->environment('local') || !file_exists(ini_get('curl.cainfo') ?: '')) {
                $httpClient = $httpClient->withoutVerifying();
            }

            $payload = [
                'secret' => $secret,
                'response' => $token,
            ];

            // Hanya sertakan remoteip jika berupa IP publik yang valid (bukan localhost 127.0.0.1 / private)
            if ($ip && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                $payload['remoteip'] = $ip;
            }

            $response = $httpClient->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', $payload);

            if ($response->successful()) {
                $data = $response->json();
                $isSuccess = (bool) ($data['success'] ?? false);

                if (!$isSuccess) {
                    Log::warning('Cloudflare Turnstile token rejected by Cloudflare', [
                        'error_codes' => $data['error-codes'] ?? [],
                        'data' => $data,
                    ]);
                }

                return $isSuccess;
            }

            Log::warning('Cloudflare Turnstile HTTP error from siteverify API', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Cloudflare Turnstile connection exception: ' . $e->getMessage());
            return false;
        }

        return false;
    }
}
