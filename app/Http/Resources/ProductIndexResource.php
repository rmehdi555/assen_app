<?php

namespace App\Http\Resources;

use App\Enum\ProductDelivery;
use App\Enum\UserGender;
use App\Helpers\Convertors;
use App\Models\Exchanges;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $price = Convertors::changePrice($this->price, Config::get('custom.exchange_price'));

        return [
            'category_title' => $this->category->title,
            'category_slug' => $this->category->slug,
            'factory_title' => $this->factory->title,
            'factory_slug' => $this->factory->slug,
            'size_title' => $this->size->title,
            'size_slug' => $this->size->slug,
            'standard_title' => $this->standard->title,
            'standard_slug' => $this->standard->slug,
            'title' => $this->title,
            'slug' => $this->slug,
            'price' => $price,
            'fluctuation_pric' => $this->fluctuationPrice(),
            'place_of_delivery' => ProductDelivery::fromName($this->place_of_delivery)->value,
            'updated_at' => showDate($this->updated_at, 'Y/m/d'),
        ];
    }
}


