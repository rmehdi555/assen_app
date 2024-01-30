<?php

namespace App\Http\Resources;

use App\Enum\ProductDelivery;
use App\Enum\UserGender;
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
        if (Config::get('custom.exchange_price') == 'IRR') {
            $price = $this->price;
        } elseif (Config::get('custom.exchange_price') == 'USD' and $this->price != 0) {
            $price = round($this->price / Exchanges::find(1)->value, 2);
        } elseif (Config::get('custom.exchange_price') == 'EUR' and $this->price != 0) {
            $price = round($this->price / Exchanges::find(2)->value, 2);
        } else
            $price = 0;

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
            'place_of_delivery' => ProductDelivery::fromName($this->place_of_delivery)->value,
            'updated_at' => showDate($this->updated_at),
        ];
    }
}


