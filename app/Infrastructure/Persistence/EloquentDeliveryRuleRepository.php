<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Delivery\DeliveryChargeRules;
use App\Domain\Delivery\DeliveryRuleRepository;
use App\Infrastructure\Persistence\Eloquent\DeliveryRuleModel;

final class EloquentDeliveryRuleRepository implements DeliveryRuleRepository
{
    public function rules(): DeliveryChargeRules
    {
        $tiers = DeliveryRuleModel::query()
            ->orderBy('min_cents')
            ->get()
            ->map(static fn (DeliveryRuleModel $row): array => [
                'min_cents' => $row->min_cents,
                'max_cents' => $row->max_cents,
                'charge_cents' => $row->charge_cents,
            ])
            ->all();

        return new DeliveryChargeRules($tiers);
    }
}
