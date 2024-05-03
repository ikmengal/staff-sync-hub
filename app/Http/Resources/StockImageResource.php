<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockImageResource extends JsonResource
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
            'image' =>isset( $this->image ) && !empty( $this->image)  ?  asset('public/admin/assets/img/stock/').'/'.$this->image : '',
            'type' => isset($this->type) ? ($this->type == 'pdf' ? 'pdf' : 'image') : null,
        ];
    }
}
