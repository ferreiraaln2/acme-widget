<?php

namespace App\Domain\Product;

use App\Domain\Shared\Money;

final class Product
{
    public function __construct(
        private readonly string $code,
        private readonly string $name,
        private readonly Money $price,
    ) {
    }

    public function code(): string
    {
        return $this->code;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): Money
    {
        return $this->price;
    }
}
