<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\FilterOptions;
use App\Http\Requests\FetchProductsRequest;
use App\Http\Resources\ProductResource;
use App\Repository\ProductRepository as Repository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FetchProductsController
{
    public function __invoke(FetchProductsRequest $request, Repository $repository): AnonymousResourceCollection
    {
        return ProductResource::collection(
            collect($repository->fetchAllProducts(FilterOptions::fromRequest($request)))
        );
    }
}
