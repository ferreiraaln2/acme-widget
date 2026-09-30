<?php

namespace App\Domain\Product;

final class ProductCatalogue
{
    private array $productsByCode = [];

    public function __construct(array $products)
    {
        foreach ($products as $product) {
            $this->productsByCode[$product->code()] = $product;
        }
    }

    public function get(string $code): Product
    {
        if (! isset($this->productsByCode[$code])) {
            throw ProductNotFoundException::forCode($code);
        }

        return $this->productsByCode[$code];
    }

    public function has(string $code): bool
    {
        return isset($this->productsByCode[$code]);
    }

    public function all(): array
    {
        return array_values($this->productsByCode);
    }
}
