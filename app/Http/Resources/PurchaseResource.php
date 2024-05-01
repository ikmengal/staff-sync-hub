<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [
            "id" => $this->id,
            "subject" => $this->subject ?? null,
            "company_id" => $this->company_id ?? null,
            "description" => $this->description ?? null,
            "created_at" => $this->created_at->format("M d,Y"),
        ];
    }
}
