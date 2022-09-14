<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function __construct(private Product $product)
    {
        parent::__construct($product);
    }

    public function toArray($request): array
    {
        $discountPercentage = $this->product->discountPercentage();

        return [
            'sku' => $this->product->sku,
            'name' => $this->product->name,
            'category' => $this->product->category->name,
            'price' => [
                'original' => $this->product->price,
                'currency' => 'EUR',
                'discount_percentage' => $this->when($discountPercentage, $discountPercentage . '%', null),
                'final' => $this->product->discountPrice()
            ]
        ];
    }
}
