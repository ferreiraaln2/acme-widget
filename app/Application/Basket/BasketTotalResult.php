<?php

namespace App\Application\Basket;

use App\Domain\Shared\Money;

final class BasketTotalResult
{
    public function __construct(
        private readonly Money $total,
    ) {
    }

    public function total(): float
    {
        return $this->total->dollars();
    }

    public function formatted(): string
    {
        return $this->total->formatted();
    }

    public function toArray(): array
    {
        return [
            'total' => $this->total(),
            'formatted' => $this->formatted(),
        ];
    }
}
