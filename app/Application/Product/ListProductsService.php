<?php

namespace App\Application\Product;

use App\Domain\Product\ProductRepository;

final class ListProductsService
{
    public function __construct(
        private readonly ProductRepository $products,
    ) {
    }

    public function handle(): array
    {
        return array_map(
            static fn ($product): array => [
                'code' => $product->code(),
                'name' => $product->name(),
                'price' => $product->price()->dollars(),
                'formatted_price' => $product->price()->formatted(),
            ],
            $this->products->catalogue()->all(),
        );
    }
}
