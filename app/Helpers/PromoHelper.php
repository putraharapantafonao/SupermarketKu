<?php

namespace App\Helpers;

use App\Models\Product;
use App\Models\Promotion;

class PromoHelper
{
    public static function getFinalPrice(Product $product)
    {
        $price = $product->selling_price;

        $promo = Promotion::where(function ($query) use ($product) {
                $query->where('product_id', $product->id)
                      ->orWhereNull('product_id');
            })
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->latest()
            ->first();

        if ($promo) {
            if ($promo->type === 'percent') {
                $price -= ($product->selling_price * $promo->value) / 100;
            } else {
                $price -= $promo->value;
            }
        }

        return max((int) $price, 0);
    }
}
