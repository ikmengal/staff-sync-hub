<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerIdResource extends JsonResource
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
            'user' => isset($this->hasUser) && !empty($this->hasUser) ? new UserResource($this->hasUser) : null,
            'player_id' => $this->player_id,
        ];
    }
}
