<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "Category" =>[
                "category_id" => $this->id,
                "category_name" => $this->name,
                'created_at' => $this->created_at->format('Y-m-d  H:i:s'),
                'updated_at' => $this->updated_at->format('Y-m-d  H:i:s'),
            ]
        ];
    }
}
