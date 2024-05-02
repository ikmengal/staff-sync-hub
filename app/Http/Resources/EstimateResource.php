<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EstimateResource extends JsonResource
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
            // 'receipt' => isset($this->receipt) && !empty($this->receipt) ? $this->receipt->title : null,
            'creator' => isset($this->creator) && !empty($this->creator) ? new UserResource($this->creator) : null,
            'company' => isset($this->company) && !empty($this->company) ? new CompanyResource($this->company) : null,
            'request' => isset($this->requestData) && !empty($this->requestData) ? new purchaseRequestResource($this->requestData) : null,
            'title' => $this->title ?? null,
            'description' => $this->description ?? null,
            'count' => $this->count ?? null,
            'price' => $this->price ?? null,
            'status' => isset($this->status) ? ($this->status == 1 ? 'Pending' : ($this->status == 2 ? 'Approved' : ($this->status == 3 ? 'Rejected' : null))) : null,
        ];
    }
}
