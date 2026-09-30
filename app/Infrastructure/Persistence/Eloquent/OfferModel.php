<?php

namespace App\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

class OfferModel extends Model
{
    protected $table = 'offers';

    protected $fillable = [
        'type',
        'product_code',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
