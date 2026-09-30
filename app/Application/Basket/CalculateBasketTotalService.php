<?php

namespace App\Application\Basket;

use App\Domain\Basket\Basket;
use App\Domain\Delivery\DeliveryRuleRepository;
use App\Domain\Offer\OfferRepository;
use App\Domain\Product\ProductRepository;

final class CalculateBasketTotalService
{
    public function __construct(
        private readonly ProductRepository $products,
        private readonly DeliveryRuleRepository $deliveryRules,
        private readonly OfferRepository $offers,
    ) {
    }

    public function handle(array $itemCodes): BasketTotalResult
    {
        $basket = new Basket(
            $this->products->catalogue(),
            $this->deliveryRules->rules(),
            $this->offers->activeOffers(),
        );

        foreach ($itemCodes as $code) {
            $basket->add($code);
        }

        return new BasketTotalResult($basket->total());
    }
}
