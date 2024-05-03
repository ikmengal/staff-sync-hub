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
            "company" => isset($this->company) && !empty($this->company) ? new CompanyResource($this->company) : null,
            "description" => $this->description ?? null,
            "status" => isset($this->getStatus) && !empty($this->getStatus) ? $this->getStatus : null,
            "created_at" => $this->created_at->format("M d,Y"),
        ];
    }
}
