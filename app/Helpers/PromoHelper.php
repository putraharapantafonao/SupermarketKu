<?php

namespace App\Helpers;

use App\Models\Product;
use App\Models\Promotion;

class PromoHelper
{
    public static function getFinalPrice(Product $product)
    {
        $price = $product->selling_price;

        $promo = Promotion::where('product_id', $product->id)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->latest()
            ->first();

        if (!$promo) {
            $promo = Promotion::whereNull('product_id')
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->latest()
                ->first();
        }

        if ($promo) {
            if ($promo->type === 'percent') {
                $discount = min($promo->value, 100);
                $price -= ($product->selling_price * $discount) / 100;
            } else {
                $discount = min($promo->value, $price);
                $price -= $discount;
            }
        }

        return max((int) $price, 0);
    }
}
