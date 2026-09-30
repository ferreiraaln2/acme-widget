<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Eloquent\DeliveryRuleModel;
use App\Infrastructure\Persistence\Eloquent\OfferModel;
use App\Infrastructure\Persistence\Eloquent\ProductModel;
use Illuminate\Database\Seeder;

class AcmeWidgetSeeder extends Seeder
{
    public function run(): void
    {
        ProductModel::query()->upsert([
            ['code' => 'R01', 'name' => 'Red Widget', 'price_cents' => 3295, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'G01', 'name' => 'Green Widget', 'price_cents' => 2495, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'B01', 'name' => 'Blue Widget', 'price_cents' => 795, 'created_at' => now(), 'updated_at' => now()],
        ], ['code'], ['name', 'price_cents', 'updated_at']);

        DeliveryRuleModel::query()->delete();
        DeliveryRuleModel::query()->insert([
            ['min_cents' => 0, 'max_cents' => 5000, 'charge_cents' => 495, 'created_at' => now(), 'updated_at' => now()],
            ['min_cents' => 5000, 'max_cents' => 9000, 'charge_cents' => 295, 'created_at' => now(), 'updated_at' => now()],
            ['min_cents' => 9000, 'max_cents' => null, 'charge_cents' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);

        OfferModel::query()->upsert([
            [
                'type' => 'buy_one_get_second_half_price',
                'product_code' => 'R01',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ], ['type', 'product_code'], ['active', 'updated_at']);
    }
}
