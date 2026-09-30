<?php

namespace App\Domain\Offer;

use App\Domain\Product\ProductCatalogue;
use App\Domain\Shared\Money;

final class BuyOneGetSecondHalfPriceOffer implements Offer
{
    public function __construct(
        private readonly string $productCode,
    ) {
    }

    public function discount(array $itemCodes, ProductCatalogue $catalogue): Money
    {
        $count = 0;

        foreach ($itemCodes as $code) {
            if ($code === $this->productCode) {
                $count++;
            }
        }

        $pairs = intdiv($count, 2);

        if ($pairs === 0) {
            return Money::zero();
        }

        $halfPrice = $catalogue->get($this->productCode)->price()->halfRoundedUp();
        $total = Money::zero();

        for ($i = 0; $i < $pairs; $i++) {
            $total = $total->add($halfPrice);
        }

        return $total;
    }
}
