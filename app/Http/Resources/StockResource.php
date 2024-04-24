<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
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
            'user' => isset($this->hasUser) && !empty($this->hasUser) ? new UserResource($this->hasUser) : 'null',
            'company' => isset($this->hasCompany) && !empty($this->hasCompany) ? new CompanyResource($this->hasCompany) : 'null',
            'title' => $this->title,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'created_at' => date('d, m Y',strtotime($this->created_at)),
            'images' => isset($this->hasImages) && !blank($this->hasImages) ? StockImageResource::collection($this->hasImages) : 'null',
        ];
    }
}
