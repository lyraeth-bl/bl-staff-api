<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Auth\LoginAction;
use App\Actions\Auth\RefreshTokenAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RefreshTokenRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private LoginAction $loginAction,
        private RefreshTokenAction $refreshTokenAction,
    ) {}

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->loginAction->execute(
                $request->validated('email'),
                $request->validated('password'),
            );
        } catch (AuthenticationException) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
            ], 401);
        }

        return response()->json([
            'message' => 'Login successful.',
            'data' => [
                'access_token' => $result['access_token'],
                'refresh_token' => $result['refresh_token'],
                'token_type' => 'Bearer',
                'expires_in' => $result['expires_in'],
            ],
        ]);
    }

    public function refresh(RefreshTokenRequest $request): JsonResponse
    {
        try {
            $result = $this->refreshTokenAction->execute(
                $request->validated('refresh_token'),
            );
        } catch (AuthenticationException) {
            return response()->json([
                'message' => 'Invalid or expired refresh token.',
            ], 401);
        }

        return response()->json([
            'message' => 'Token refreshed successfully.',
            'data' => [
                'access_token' => $result['access_token'],
                'refresh_token' => $result['refresh_token'],
                'token_type' => 'Bearer',
                'expires_in' => $result['expires_in'],
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();

        // Hapus semua refresh token user
        $request->user()->refreshTokens()->delete();

        return response()->json([
            'message' => 'Logout successful.',
        ]);
    }
}
