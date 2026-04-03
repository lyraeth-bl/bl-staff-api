<?php

namespace App\Http\Controllers\Api\AppConfiguration;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppConfigurationResource;
use App\Models\AppConfiguration;
use Illuminate\Http\JsonResponse;

class AppConfigurationController extends Controller
{
    public function getAppConfiguration(): JsonResponse
    {
        $appConfiguration = AppConfiguration::first();

        if (! $appConfiguration) {
            return response()->json([
                'error'   => true,
                'message' => 'App configuration not found.',
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'AppConfiguration retrieved successfully',
            'data' => new AppConfigurationResource($appConfiguration),
        ]);
    }
}
