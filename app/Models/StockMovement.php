<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class StockMovement extends Model
{
    use HasFactory;
    protected $fillable = [
    'product_id',
    'type',
    'quantity',
    'description',
    ];

    protected function type(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => in_array($value, ['in', 'out']) ? $value : throw new \InvalidArgumentException('Type must be "in" or "out"'),
        );
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
