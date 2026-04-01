<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Auth\RegisterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private RegisterAction $registerAction,
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->registerAction->execute(
            $request->validated('name'),
            $request->validated('email'),
            $request->validated('password'),
        );

        return response()->json([
            'message' => 'Registration successful.',
            'data' => [
                'user' => new UserResource($user),
            ],
        ], 201);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'data' => new UserResource($request->user()),
        ]);
    }
}
