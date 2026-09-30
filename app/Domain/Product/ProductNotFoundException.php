<?php

namespace App\Domain\Product;

final class ProductNotFoundException extends \RuntimeException
{
    public static function forCode(string $code): self
    {
        return new self("Product with code [{$code}] was not found.");
    }
}
