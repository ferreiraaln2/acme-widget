<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Product\Product;
use App\Domain\Product\ProductCatalogue;
use App\Domain\Product\ProductRepository;
use App\Domain\Shared\Money;
use App\Infrastructure\Persistence\Eloquent\ProductModel;

final class EloquentProductRepository implements ProductRepository
{
    public function catalogue(): ProductCatalogue
    {
        $products = ProductModel::query()
            ->orderBy('code')
            ->get()
            ->map(static fn (ProductModel $row): Product => new Product(
                $row->code,
                $row->name,
                new Money($row->price_cents),
            ))
            ->all();

        return new ProductCatalogue($products);
    }
}
