<?php

namespace App\Http\Resources;

use App\Enum\ProductDelivery;
use App\Enum\UserGender;
use App\Models\Exchanges;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FactoryIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
        ];
    }
}


