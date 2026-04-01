<?php

namespace App\Actions\Auth;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Http;

class RefreshTokenAction
{
    public function execute(string $refreshToken): array
    {
        $response = Http::timeout(10)
            ->asForm()
            ->post(config('app.url').'/oauth/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
                'client_id' => config('services.passport.password_client_id'),
                'client_secret' => config('services.passport.password_client_secret'),
                'scope' => '',
            ]);

        if ($response->failed()) {
            throw new AuthenticationException('Invalid or expired refresh token.');
        }

        return [
            'access_token' => $response->json('access_token'),
            'refresh_token' => $response->json('refresh_token'),
            'expires_in' => $response->json('expires_in'),
        ];
    }
}
