<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $sku
 * @property string $name
 * @property int $price
 * @property Category $category
 */
class Product extends Model
{
    /** @inheritdoc */
    protected $table = 'products';

    /** @inheritdoc */
    public $timestamps = false;

    public function category(): HasOne
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function discountPercentage(): ?int
    {
        if ($this->category->name === 'insurance') {
            return 30;
        }

        if ($this->sku === '000003') {
            return 15;
        }

        return null;
    }

    public function discountPrice(): int
    {
        if ($this->discountPercentage() === null) {
            return $this->price;
        }

        $discount = round(
            ($this->discountPercentage() / 100) * $this->price
        );

        return $this->price - (int) $discount;
    }
}
