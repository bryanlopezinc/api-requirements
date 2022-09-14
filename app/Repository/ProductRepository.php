<?php

declare(strict_types=1);

namespace App\Repository;

use App\FilterOptions;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductRepository
{
    /**
     * @return array<Product>
     */
    public function fetchAllProducts(FilterOptions $options = null): array
    {
        $options = $options ?: new FilterOptions([]);

        return Product::query()
            ->with('category')
            ->whereHas('category', function (Builder $query) use ($options) {
                if ($options->hasCategory()) {
                    $query->where('name', $options->category());
                }
            })
            ->when($options->hasMaxPrice(), function (Builder $query) use ($options) {
                $query->where('price', '<=', $options->maxPrice());
            })
            ->get()
            ->all();
    }
}
