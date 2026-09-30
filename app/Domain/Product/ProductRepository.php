<?php

namespace App\Domain\Product;

interface ProductRepository
{
    public function catalogue(): ProductCatalogue;
}
