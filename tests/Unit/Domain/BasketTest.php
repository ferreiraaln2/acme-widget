<?php

namespace Tests\Unit\Domain;

use App\Domain\Basket\Basket;
use App\Domain\Delivery\DeliveryChargeRules;
use App\Domain\Offer\BuyOneGetSecondHalfPriceOffer;
use App\Domain\Product\Product;
use App\Domain\Product\ProductCatalogue;
use App\Domain\Shared\Money;
use PHPUnit\Framework\TestCase;

class BasketTest extends TestCase
{
    private function catalogue(): ProductCatalogue
    {
        return new ProductCatalogue([
            new Product('R01', 'Red Widget', Money::fromDollars(32.95)),
            new Product('G01', 'Green Widget', Money::fromDollars(24.95)),
            new Product('B01', 'Blue Widget', Money::fromDollars(7.95)),
        ]);
    }

    private function deliveryRules(): DeliveryChargeRules
    {
        return new DeliveryChargeRules([
            ['min_cents' => 0, 'max_cents' => 5000, 'charge_cents' => 495],
            ['min_cents' => 5000, 'max_cents' => 9000, 'charge_cents' => 295],
            ['min_cents' => 9000, 'max_cents' => null, 'charge_cents' => 0],
        ]);
    }

    private function basket(): Basket
    {
        return new Basket(
            $this->catalogue(),
            $this->deliveryRules(),
            [new BuyOneGetSecondHalfPriceOffer('R01')],
        );
    }

    public function test_b01_g01_total(): void
    {
        $basket = $this->basket();
        $basket->add('B01');
        $basket->add('G01');

        $this->assertSame('$37.85', $basket->total()->formatted());
    }

    public function test_r01_r01_total(): void
    {
        $basket = $this->basket();
        $basket->add('R01');
        $basket->add('R01');

        $this->assertSame('$54.37', $basket->total()->formatted());
    }

    public function test_r01_g01_total(): void
    {
        $basket = $this->basket();
        $basket->add('R01');
        $basket->add('G01');

        $this->assertSame('$60.85', $basket->total()->formatted());
    }

    public function test_b01_b01_r01_r01_r01_total(): void
    {
        $basket = $this->basket();
        $basket->add('B01');
        $basket->add('B01');
        $basket->add('R01');
        $basket->add('R01');
        $basket->add('R01');

        $this->assertSame('$98.27', $basket->total()->formatted());
    }
}
