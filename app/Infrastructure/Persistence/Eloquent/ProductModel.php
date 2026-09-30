<?php

namespace App\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'code',
        'name',
        'price_cents',
    ];

    protected function casts(): array
    {
        return [
            'price_cents' => 'integer',
        ];
    }
}
