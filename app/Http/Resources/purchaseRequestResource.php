<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class purchaseRequestResource extends JsonResource
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
            'creator' => $this->creator,
            'company_id' => isset($this->company) && !empty($this->company) ? $this->company->name : null,
            'subject' => $this->subject,
            'description' => $this->description,
            'status' => isset($this->status) ? ($this->status == 1 ? 'Pending' : ($this->status == 2 ? 'Approved' : ($this->status == 3 ? 'Rejected' : null))) : null,
        ];
    }
}
