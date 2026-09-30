<?php

namespace App\Domain\Offer;

interface OfferRepository
{
    public function activeOffers(): array;
}
