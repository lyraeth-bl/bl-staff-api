<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'error' => false,
            'message' => 'User retrieved successfully',
            'user' => new UserResource($request->user())
        ]);
    }
}
