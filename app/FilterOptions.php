<?php

declare(strict_types=1);

namespace App;

use App\Http\Requests\FetchProductsRequest;

class FilterOptions
{
    private const CATEGORY = 'category';
    private const MAX_PRICE = 'max_price';

    public function __construct(private readonly array $options)
    {
    }

    public static function fromRequest(FetchProductsRequest $request): self
    {
        $data = [
            self::CATEGORY => $request->validated('category'),
            self::MAX_PRICE => $request->safe()->has('max_price') ? (int) $request->validated('max_price') : null,
        ];

        return new self(
            array_filter($data, fn ($value) => !is_null($value))
        );
    }

    public function hasCategory(): bool
    {
        return array_key_exists(self::CATEGORY, $this->options);
    }

    public function category(): string
    {
        return $this->options[self::CATEGORY];
    }

    public function hasMaxPrice(): bool
    {
        return array_key_exists(self::MAX_PRICE, $this->options);
    }

    public function maxPrice(): int
    {
        return $this->options[self::MAX_PRICE];
    }
}
