<?php

namespace Tests\Unit;

use App\Helpers\PromoHelper;
use App\Models\Promotion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesTestData;
use Tests\TestCase;

class PromoHelperTest extends TestCase
{
    use RefreshDatabase, CreatesTestData;

    public function test_returns_selling_price_when_no_promo(): void
    {
        $product = $this->createProduct(['selling_price' => 15000]);

        $price = PromoHelper::getFinalPrice($product);

        $this->assertEquals(15000, $price);
    }

    public function test_applies_percent_promo(): void
    {
        $product = $this->createProduct(['selling_price' => 20000]);
        Promotion::factory()->create([
            'product_id' => $product->id,
            'type' => 'percent',
            'value' => 20,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDays(30),
        ]);

        $price = PromoHelper::getFinalPrice($product);

        $this->assertEquals(16000, $price); // 20000 - 20%
    }

    public function test_applies_nominal_promo(): void
    {
        $product = $this->createProduct(['selling_price' => 50000]);
        Promotion::factory()->create([
            'product_id' => $product->id,
            'type' => 'nominal',
            'value' => 10000,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDays(30),
        ]);

        $price = PromoHelper::getFinalPrice($product);

        $this->assertEquals(40000, $price);
    }

    public function test_expired_promo_returns_selling_price(): void
    {
        $product = $this->createProduct(['selling_price' => 25000]);
        Promotion::factory()->create([
            'product_id' => $product->id,
            'type' => 'percent',
            'value' => 50,
            'start_date' => now()->subDays(60),
            'end_date' => now()->subDay(),
        ]);

        $price = PromoHelper::getFinalPrice($product);

        $this->assertEquals(25000, $price);
    }

    public function test_price_never_below_zero(): void
    {
        $product = $this->createProduct(['selling_price' => 5000]);
        Promotion::factory()->create([
            'product_id' => $product->id,
            'type' => 'nominal',
            'value' => 10000,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDays(30),
        ]);

        $price = PromoHelper::getFinalPrice($product);

        $this->assertEquals(0, $price);
    }
}
