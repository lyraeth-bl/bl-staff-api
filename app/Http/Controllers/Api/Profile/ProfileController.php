<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\ChangeNameRequest;
use App\Http\Requests\Profile\ChangePasswordRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function me(Request $request): JsonResponse
    {
        // Return response.
        return response()->json([
            'error' => false,
            'message' => 'User retrieved successfully',
            'user' => new UserResource($request->user()->load('userDetail'))
        ]);
    }

    public function changeName(ChangeNameRequest $request): JsonResponse
    {
        // Update user yang sedang login sesuai dengan request.
        $request->user()->update($request->validated());

        // Return response.
        return response()->json([
            'error' => false,
            'message' => 'Name updated successfully.',
            // Gunain ->fresh() supaya data yang dikirim adalah data sehabis di update (terbaru)
            // bukan data yang lama atau ada di memory.
            'user' => new UserResource($request->user()->fresh()),
        ]);
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {

        // Verifikasi current_password dulu sebelum ganti.
        if (! Hash::check($request->current_password, $request->user()->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The provided password does not match your current password.'],
            ]);
        }

        $request->user()->update($request->validated());

        // Return response.
        return response()->json([
            'error' => false,
            'message' => 'Password updated successfully.',
        ]);
    }
}
