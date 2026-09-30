<?php

namespace App\Domain\Delivery;

use App\Domain\Shared\Money;

final class DeliveryChargeRules
{
    private array $tiers;

    public function __construct(array $tiers)
    {
        usort(
            $tiers,
            static fn (array $a, array $b): int => ($a['min_cents'] ?? 0) <=> ($b['min_cents'] ?? 0)
        );
        $this->tiers = $tiers;
    }

    public function chargeFor(Money $subtotal): Money
    {
        foreach ($this->tiers as $tier) {
            $minCents = $tier['min_cents'] ?? 0;
            $maxCents = $tier['max_cents'] ?? null;
            $amount = $subtotal->cents();

            if ($amount < $minCents) {
                continue;
            }

            if ($maxCents !== null && $amount >= $maxCents) {
                continue;
            }

            return new Money($tier['charge_cents']);
        }

        return Money::zero();
    }
}
