<?php

namespace App\Domain\Offer;

use App\Domain\Product\ProductCatalogue;
use App\Domain\Shared\Money;

interface Offer
{
    public function discount(array $itemCodes, ProductCatalogue $catalogue): Money;
}
