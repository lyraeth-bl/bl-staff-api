<?php

namespace App\Actions\Auth;

use App\Models\UserRefreshToken;
use Illuminate\Auth\AuthenticationException;
use Pest\Support\Str;

class RefreshTokenAction
{
    public function execute(string $refreshToken): array
    {
        $token = UserRefreshToken::where('token', $refreshToken)
            ->with('user')
            ->first();

        if (! $token || $token->isExpired()) {
            throw new AuthenticationException('Invalid or expired refresh token.');
        }

        $user = $token->user;

        // Revoke semua access token lama
        $user->tokens()->each(fn ($t) => $t->revoke());

        // Rotate refresh token
        $token->delete();

        $newRefreshToken = UserRefreshToken::create([
            'user_id' => $user->id,
            'token' => Str::random(64),
            'expires_at' => now()->addDays(30),
        ]);

        return [
            'access_token' => $user->createToken('mobile-app')->accessToken,
            'refresh_token' => $newRefreshToken->token,
            'expires_in' => now()->addMinutes(15)->timestamp,
        ];
    }
}
