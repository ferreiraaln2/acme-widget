<?php

namespace App\Domain\Shared;

final class Money
{
    public function __construct(
        private readonly int $cents,
    ) {
        if ($cents < 0) {
            throw new \InvalidArgumentException('Money cannot be negative.');
        }
    }

    public static function fromDollars(float $amount): self
    {
        return new self((int) round($amount * 100));
    }

    public static function zero(): self
    {
        return new self(0);
    }

    public function cents(): int
    {
        return $this->cents;
    }

    public function dollars(): float
    {
        return $this->cents / 100;
    }

    public function add(self $other): self
    {
        return new self($this->cents + $other->cents);
    }

    public function subtract(self $other): self
    {
        return new self($this->cents - $other->cents);
    }

    public function halfRoundedUp(): self
    {
        return new self((int) round($this->cents / 2));
    }

    public function isLessThan(self $other): bool
    {
        return $this->cents < $other->cents;
    }

    public function formatted(): string
    {
        return '$'.number_format($this->dollars(), 2, '.', '');
    }
}
