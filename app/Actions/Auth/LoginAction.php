<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Models\UserRefreshToken;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginAction
{
    /**
     * Create a new class instance.
     */
    public function execute(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw new AuthenticationException('The provided credentials are incorrect.');
        }

        $accessToken = $user->createToken('mobile-app')->accessToken;

        $refreshToken = UserRefreshToken::create([
            'user_id' => $user->id,
            'token' => Str::random(64),
            'expires_at' => now()->addDays(30),
        ]);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken->token,
            'expires_in' => now()->addMinutes(15)->timestamp,
        ];
    }
}
