<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppConfigurationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'android_app_link' => $this->android_app_link,
            'android_app_version' => $this->android_app_version,
            'force_app_update' => $this->force_app_update,
            'app_maintenance' => $this->app_maintenance,
        ];
    }
}
