<?php

namespace Tests\Feature;

use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class FetchProductsTest extends TestCase
{
    private function fetchProductsResponse(array $parameters = []): TestResponse
    {
        return $this->getJson(route('getAllProducts', $parameters));
    }

    public function test_fetch_all_products(): void
    {
        $data = json_decode(file_get_contents(base_path('tests/stubs/products.json')), true)['products'];

        $dataSet = collect($data);

        $this->fetchProductsResponse()
            ->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJson(function (AssertableJson $json) use ($dataSet) {
                $json->etc()
                    ->fromArray($json->toArray()['data'])
                    ->each(function (AssertableJson $json) use ($dataSet) {
                        $product = $dataSet->where('sku', $json->toArray()['sku'])->sole();
                        $productSkuAndExpectedDiscountPrice = [
                            '000001' => 62_300,
                            '000004' => 14_000
                        ];

                        $json->etc()
                            ->where('price.currency', 'EUR')
                            ->where('name', $product['name'])
                            ->where('category', $product['category'])
                            ->where('price.original', $product['price'])
                            ->count(4)
                            ->count('price', 4);

                        if ($product['category'] === 'insurance') {
                            $json->where('price.discount_percentage', '30%');
                            $json->where('price.final', $productSkuAndExpectedDiscountPrice[$product['sku']]);
                        } elseif ($product['sku'] === '000003') {
                            $json->where('price.discount_percentage', '15%');
                            $json->where('price.final', 127_500);
                        } else {
                            $json->where('price.discount_percentage', null);
                            $json->where('price.final', $product['price']);
                        }
                    });
            })
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'sku',
                        'name',
                        'category',
                        'price' => [
                            'original',
                            'currency',
                            'discount_percentage',
                            'final'
                        ]
                    ]
                ]
            ]);
    }

    public function test_filter_products_by_category(): void
    {
        $this->fetchProductsResponse(['category' => 'insurance'])
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJson([
                'data' => [
                    ['sku' => '000001'],
                    ['sku' => '000004'],
                ]
            ]);

        $this->fetchProductsResponse(['category' => 'vehicle'])
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJson([
                'data' => [
                    ['sku' => '000002'],
                    ['sku' => '000003'],
                    ['sku' => '000005']
                ]
            ]);
    }

    public function test_filter_products_by_price(): void
    {
        $this->fetchProductsResponse(['max_price' => 89000])
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJson([
                'data' => [
                    ['sku' => '000004'],
                    ['sku' => '000001']
                ]
            ]);
    }

    public function test_category_filter_must_be_valid(): void
    {
        $this->fetchProductsResponse(['category' => 'foo'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'category' => ['The category field is invalid.']
            ]);

        $this->fetchProductsResponse(['category' => '  '])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'category' => ['The category must be a string.']
            ]);
    }

    public function test_price_filter_must_be_valid(): void
    {
        $this->fetchProductsResponse(['max_price' => 'foo'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'max_price' => ['The max price must be an integer.']
            ]);
    }
}
