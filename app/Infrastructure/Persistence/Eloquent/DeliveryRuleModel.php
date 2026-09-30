<?php

namespace App\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

class DeliveryRuleModel extends Model
{
    protected $table = 'delivery_rules';

    protected $fillable = [
        'min_cents',
        'max_cents',
        'charge_cents',
    ];

    protected function casts(): array
    {
        return [
            'min_cents' => 'integer',
            'max_cents' => 'integer',
            'charge_cents' => 'integer',
        ];
    }
}
