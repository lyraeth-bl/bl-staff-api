<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        // Validasi request body.
        User::create($request->validated());

        // Return response.
        return response()->json(
            [
                'error' => false,
                'message' => 'Registration successfully. Please login to continue',
            ]
        );
    }

    public function login(LoginRequest $request): JsonResponse
    {
        // Cari user berdasarkan email.
        $user = User::where('email', $request->email)->first();

        // Check apakah ada user memang ada atau hash password valid.
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
            ], 401);
        }

        // Masa habis access_token.
        $expiresAt = Carbon::now()->addDays(7);

        // Generate access_token.
        $accessToken = $user->createToken($request->device_name, ['*'], $expiresAt)->plainTextToken;

        // Return response.
        return response()->json([
            'error' => false,
            'message' => 'Login successfully.',
            'data' => [
                'access_token' => $accessToken,
                'token_type'   => 'Bearer',
                'expires_at'   => $expiresAt->toIso8601String(),
            ]
        ]);
    }

    public function logout(Request $request)
    {
        // Hapus token user.
        $request->user()->currentAccessToken()->delete();

        // Return response.
        return response()->json([
            'error' => false,
            'message' => 'Logged out successfully'
        ]);
    }
}
