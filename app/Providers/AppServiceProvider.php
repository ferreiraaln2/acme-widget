<?php

namespace App\Providers;

use App\Domain\Delivery\DeliveryRuleRepository;
use App\Domain\Offer\OfferRepository;
use App\Domain\Product\ProductRepository;
use App\Infrastructure\Persistence\EloquentDeliveryRuleRepository;
use App\Infrastructure\Persistence\EloquentOfferRepository;
use App\Infrastructure\Persistence\EloquentProductRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProductRepository::class, EloquentProductRepository::class);
        $this->app->bind(DeliveryRuleRepository::class, EloquentDeliveryRuleRepository::class);
        $this->app->bind(OfferRepository::class, EloquentOfferRepository::class);
    }

    public function boot(): void
    {
    }
}
