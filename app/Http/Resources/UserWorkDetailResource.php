<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserWorkDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'unit' => $this->unit?->name . ' - ' . $this->unit?->location,
            'position' => $this->position?->name,
            'leader' => $this->leader?->name,
        ];
    }
}
