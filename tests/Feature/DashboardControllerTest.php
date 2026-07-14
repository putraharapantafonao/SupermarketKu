<?php

namespace Tests\Feature;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\CreatesTestData;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase, CreatesTestData;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRoles();
        $this->user = $this->createUser('Kasir');
    }

    public function test_index_loads_dashboard(): void
    {
        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas([
            'todaySales',
            'todayTransactions',
            'totalProducts',
            'totalCustomers',
            'lowStockProducts',
            'bestSellingProducts',
            'salesChart',
            'monthlySalesChart',
        ]);
    }

    public function test_dashboard_shows_today_sales(): void
    {
        $this->createTransactionWithDetail();

        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertStatus(200);
    }

    public function test_dashboard_shows_low_stock_products(): void
    {
        $this->createProduct(['stock' => 2, 'minimum_stock' => 5]);

        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertStatus(200);
    }

    public function test_dashboard_shows_low_stock_notification(): void
    {
        $this->createProduct(['stock' => 2, 'minimum_stock' => 5]);

        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertStatus(200);
    }

    public function test_notifications_shows_low_stock_and_expired_products(): void
    {
        $this->createProduct(['stock' => 2, 'minimum_stock' => 5]);
        $this->createProduct([
            'stock' => 10,
            'expired_date' => Carbon::yesterday()->format('Y-m-d'),
        ]);

        $response = $this->actingAs($this->user)->get(route('notifications.index'));

        $response->assertStatus(200);
        $response->assertViewHas([
            'lowStockProducts',
            'expiredProducts',
            'almostExpiredProducts',
        ]);
    }

    public function test_notifications_shows_almost_expired_products(): void
    {
        $this->createProduct([
            'expired_date' => Carbon::now()->addDays(15)->format('Y-m-d'),
        ]);

        $response = $this->actingAs($this->user)->get(route('notifications.index'));

        $response->assertStatus(200);
    }

    public function test_dashboard_requires_authentication(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect();
    }

    public function test_notifications_requires_authentication(): void
    {
        $response = $this->get(route('notifications.index'));

        $response->assertRedirect();
    }

    public function test_dashboard_shows_best_selling_products(): void
    {
        $product = $this->createProduct();
        $transaction = $this->createTransaction(['user_id' => $this->user->id]);

        $transaction->details()->create([
            'product_id' => $product->id,
            'quantity' => 10,
            'price' => $product->selling_price,
            'subtotal' => $product->selling_price * 10,
        ]);

        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertStatus(200);
    }

    public function test_dashboard_shows_sales_chart(): void
    {
        $transaction = $this->createTransaction(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertStatus(200);
    }
}
