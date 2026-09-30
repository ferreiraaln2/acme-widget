<?php

namespace App\Domain\Delivery;

interface DeliveryRuleRepository
{
    public function rules(): DeliveryChargeRules;
}
