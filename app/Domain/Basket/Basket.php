<?php

namespace App\Domain\Basket;

use App\Domain\Delivery\DeliveryChargeRules;
use App\Domain\Product\ProductCatalogue;
use App\Domain\Shared\Money;

final class Basket
{
    private array $items = [];

    public function __construct(
        private readonly ProductCatalogue $catalogue,
        private readonly DeliveryChargeRules $deliveryRules,
        private readonly array $offers,
    ) {
    }

    public function add(string $productCode): void
    {
        $this->catalogue->get($productCode);
        $this->items[] = $productCode;
    }

    public function total(): Money
    {
        $subtotal = Money::zero();

        foreach ($this->items as $code) {
            $subtotal = $subtotal->add($this->catalogue->get($code)->price());
        }

        $discount = Money::zero();

        foreach ($this->offers as $offer) {
            $discount = $discount->add($offer->discount($this->items, $this->catalogue));
        }

        $afterOffers = $subtotal->subtract($discount);
        $delivery = $this->deliveryRules->chargeFor($afterOffers);

        return $afterOffers->add($delivery);
    }
}
