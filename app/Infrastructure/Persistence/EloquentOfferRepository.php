<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Offer\BuyOneGetSecondHalfPriceOffer;
use App\Domain\Offer\Offer;
use App\Domain\Offer\OfferRepository;
use App\Infrastructure\Persistence\Eloquent\OfferModel;
use RuntimeException;

final class EloquentOfferRepository implements OfferRepository
{
    public function activeOffers(): array
    {
        return OfferModel::query()
            ->where('active', true)
            ->orderBy('id')
            ->get()
            ->map(fn (OfferModel $row): Offer => $this->toDomain($row))
            ->all();
    }

    private function toDomain(OfferModel $row): Offer
    {
        return match ($row->type) {
            'buy_one_get_second_half_price' => new BuyOneGetSecondHalfPriceOffer($row->product_code),
            default => throw new RuntimeException("Unknown offer type [{$row->type}]."),
        };
    }
}
